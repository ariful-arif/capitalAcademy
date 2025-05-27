<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\FileUploader;
use App\Models\Setting;

class OpenAiController extends Controller
{
    function settings()
    {
        return view('admin.open_ai.settings');
    }

    function settings_update(Request $request)
    {
        $validated = $request->validate([
            'open_ai_model' => 'in:gpt-3.5-turbo-0125,gpt-4-0125-preview,gpt-4o-mini',
            'open_ai_max_token' => 'required|numeric|min:0',
            'open_ai_secret_key' => 'required|max:255',
        ]);

        foreach ($request->all() as $type => $value) {
            if (Setting::where('type', $type)->count() == 0) {
                Setting::where('type', $type)->insert(['type' => $type, 'description' => $value]);
            } else {
                Setting::where('type', $type)->update(['description' => $value]);
            }
        }

        return redirect(route('admin.open.ai.settings'))->with('success', get_phrase('Open ai settings changed successfully'));
    }

    function assistant_store(Request $request)
    {
        $validated = $request->validate([
            'assistant_name' => 'required|max:255',
            'instructions' => 'required|max:2000',
            'files' => 'array',
            'files.*' => 'sometimes|file|mimes:pdf,txt,docx,csv,json|max:50240',
        ]);


        $files = $this->upload_files_to_openai($request);
        $vector_store_id = $this->create_vectore_store($files['file_ids']);

        if (Setting::where('type', 'openai_assistant')->count() == 0) {
            $assistant_id = $this->create_open_ai_assistant($vector_store_id, $request->assistant_name, $request->instructions);

            $value = json_encode([
                'files' => $files,
                'vector_store_ids' => [$vector_store_id],
                'assistant_id' => $assistant_id,
                'assistant_name' => $request->assistant_name,
                'instructions' => $request->instructions
            ]);

            Setting::where('type', 'openai_assistant')->insert(['type' => 'openai_assistant', 'description' => $value]);
            return redirect(route('admin.open.ai.settings'))->with('success', get_phrase('Assistant created successfully'));
        } else {
            $this->update_open_ai_assistant($vector_store_id, $request->assistant_name, $request->instructions, $files);
            return redirect(route('admin.open.ai.settings'))->with('success', get_phrase('Assistant updated successfully'));
        }
    }

    


    function upload_files_to_openai($request)
    {
        if($request->file('files') == null) {
            return ['file_ids' => [], 'file_paths' => []];
        }

        $apiKey = get_settings('open_ai_secret_key');
        $fileIds = [];
        $uploaded_paths = [];

        foreach ($request->file('files') as $file) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/files');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            $data = [
                'purpose' => 'assistants',
                'file' => new \CURLFile($file->getPathname(), $file->getMimeType(), $file->getClientOriginalName())
            ];

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $apiKey"
            ]);

            $response = curl_exec($ch);
            $result = json_decode($response, true);
            curl_close($ch);

            if (isset($result['id'])) {
                $fileIds[] = $result['id']; // Save each uploaded file's ID
                $uploaded_paths[] = FileUploader::upload($file, 'uploads/ai_assistant/' . $result['id'] . '.' . $file->getClientOriginalExtension());
            }
        }

        return ['file_ids' => $fileIds, 'file_paths' => $uploaded_paths];
    }

    function create_vectore_store($fileIds = array())
    {
        $apiKey = get_settings('open_ai_secret_key');

        $payload = json_encode([
            "file_ids" => $fileIds
        ]);

        $ch = curl_init("https://api.openai.com/v1/vector_stores");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json",
                "OpenAI-Beta: assistants=v2"
            ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseArray = json_decode($response, true);
        return $responseArray['id'] ?? null;
    }

    function create_open_ai_assistant($vectorStoreId, $assistant_name, $instructions){
        $apiKey = get_settings('open_ai_secret_key');
        $model = get_settings('open_ai_model');

        $assistantPayload = json_encode([
            "name" => $assistant_name,
            "instructions" => $instructions,
            "model" => $model,
            "tools" => [["type" => "file_search"]],
            "tool_resources" => [
                "file_search" => [
                    "vector_store_ids" => [$vectorStoreId]
                ]
            ]
        ]);
        
        $ch = curl_init('https://api.openai.com/v1/assistants');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $assistantPayload,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json",
                "OpenAI-Beta: assistants=v2"
            ]
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        $responseArray = json_decode($response, true);
        return $responseArray['id'] ?? null;
    }

    function update_open_ai_assistant($vector_store_id, $assistant_name, $instructions, $files = []){
        $apiKey = get_settings('open_ai_secret_key');
        $ai_assistant = json_decode(get_settings('openai_assistant'), true);

        foreach($files as $key => $path) {
            if($key == 'file_ids') {
                $ai_assistant['files']['file_ids'] = array_merge($ai_assistant['files']['file_ids'], $path);
            } elseif($key == 'file_paths') {
                $ai_assistant['files']['file_paths'] = array_merge($ai_assistant['files']['file_paths'], $path);
            }
        }
        
        $vector_store_ids = array_merge($ai_assistant['vector_store_ids'], [$vector_store_id]);
        $ai_assistant['vector_store_ids'] = $vector_store_ids;
        $ai_assistant['assistant_name'] = $assistant_name;
        $ai_assistant['instructions'] = $instructions;


        $updatePayload = json_encode([
            "name" => $assistant_name,
            "instructions" => $instructions,
            "tool_resources" => [
                "file_search" => [
                    "vector_store_ids" => $vector_store_ids
                ]
            ]
        ]);

        $ch = curl_init("https://api.openai.com/v1/assistants/{$ai_assistant['assistant_id']}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => $updatePayload,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json",
                "OpenAI-Beta: assistants=v2"
            ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);


        Setting::where('type', 'openai_assistant')->update(['description' => json_encode($ai_assistant)]);
    }

    function remove_file(Request $request)
    {
        $ai_assistant = json_decode(get_settings('openai_assistant'), true);

        $fileId = $ai_assistant['files']['file_ids'][$request->index]; // Replace with your actual file ID
        $apiKey = get_settings('open_ai_secret_key');
        
        $ch = curl_init("https://api.openai.com/v1/files/{$fileId}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey"
            ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        
        remove_file($ai_assistant['files']['file_paths'][$request->index]);

        // Remove file from arrays
        unset($ai_assistant['files']['file_ids'][$request->index]);
        unset($ai_assistant['files']['file_paths'][$request->index]);

        // Reindex arrays to maintain proper numeric keys
        $ai_assistant['files']['file_ids'] = array_values($ai_assistant['files']['file_ids']);
        $ai_assistant['files']['file_paths'] = array_values($ai_assistant['files']['file_paths']);

        Setting::where('type', 'openai_assistant')->update(['description' => json_encode($ai_assistant)]);

        return redirect()->back()->with('success', get_phrase('File removed successfully'));
    }

    function generate(Request $request)
    {
        if ($request->service_type == 'Course thumbnail') {
            $prompt = "We have run a online LMS system. Please generate course thumbnails for me. \n Course topic: " . $request->ai_keywords;
            return $this->curl_call_to_generate_image_openai($prompt);
        } else {
            $prompt = "Write me a ";
            $prompt .= $request->service_type;
            $prompt .= " on ";
            $prompt .= $request->ai_keywords;
            $prompt .= " in ";
            $prompt .= $request->language;
            $prompt .= " language";

            $instructions = "You are a " . $request->service_type . " writer.";
            return $this->curl_call_to_generate_text_by_openai($prompt, $instructions);
        }
    }

    function curl_call_to_generate_image_openai($prompt)
    {
        $open_ai_secret_key = get_settings('open_ai_secret_key');

        $curlopt_post = ['prompt' => $prompt, 'model' => 'dall-e-3', 'size' => '1024x1024', 'n' => 1];
        $curlopt_post_url = 'https://api.openai.com/v1/images/generations';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlopt_post_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $open_ai_secret_key,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlopt_post));

        $response = curl_exec($ch);

        curl_close($ch);

        $response_arr = json_decode($response, true);
        if (array_key_exists('error', $response_arr)) {
            return 'Error: ' . $response_arr['error']['message'];
        } else {
            return json_encode($response_arr['data']);
        }
    }

    function curl_call_to_generate_text_by_openai($instructions, $prompt)
    {
        $open_ai_secret_key = get_settings('open_ai_secret_key');
        $open_ai_model = get_settings('open_ai_model');
        $endpoint = "https://api.openai.com/v1/chat/completions";

        $data = array(
            "model" => $open_ai_model,
            "messages" => array(
                array(
                    "role" => "system",
                    "content" => $instructions
                ),
                array(
                    "role" => "user",
                    "content" => "$prompt"
                )
            )
        );

        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $open_ai_secret_key
        ));

        $response = curl_exec($ch);
        curl_close($ch);


        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['error'])) {
                return json_encode($response);
            } elseif (is_array($response)) {
                return $response['choices'][0]['message']['content'] ?? '';
            }
        }
    }
}
