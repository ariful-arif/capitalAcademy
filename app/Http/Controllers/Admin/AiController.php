<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Http;
use App\Models\Lesson;
use Illuminate\Support\Facades\Session;
class AiController extends Controller
{
    public function ai_flashCards_generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            // 'pdf' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }
        $lesson = Lesson::find($request->lesson_id);
        $pdfPath = public_path('uploads/lesson_file/attachment/' . $lesson->attachment);
        // $flashcard = $lesson->test;

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();
        // // Parse the PDF
        // $parser = new Parser();
        // $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        // $text = $pdf->getText();
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        // $text = substr($text, 0, 12000);

        // Create OpenAI prompt
        $prompt = "Generate flashcards from the following text. Generate 50 flashcards. Format each flashcard like this:\n\nFlashcard 1:\nQuestion 1: [Your Question]\nAnswer 1: [Your Answer]\n\n---\n\nText:\n" . $text;
        // Your OpenAI API Key
        $apiKey = $chatgpt_api_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a flashcard generator. Generate minimum 50 and maximum 500 if possible. Format your response using "Flashcard 1:", "Question 1:", "Answer 1:" etc.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        // Extract flashcards
        $rawText = $response['choices'][0]['message']['content'];
        $flashcards = [];

        preg_match_all('/Flashcard (\d+):\s+Question \d+: (.*?)\s+Answer \d+: (.*?)(?=(Flashcard \d+:|$))/s', $rawText, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $flashcards[] = [
                'question' => trim($match[2]),
                'answer' => trim($match[3]),
            ];
        }

        // Update Lesson record
        // $lesson = Lesson::find($request->lesson_id);
        // $lesson->flashcards = $flashcards; // if column is json type
        // $lesson->save();

        return response()->json([
            'message' => 'Flashcards generated and saved successfully.',
            'lesson_id' => $lesson->id,
            'flashcards' => $flashcards,
        ]);
    }

    public function saveFlashcards(Request $request)
    {
        try {
            $request->validate([
                'lesson_id' => 'required|exists:lessons,id',
                'flashcards' => 'required|array',
            ]);

            $lesson = Lesson::find($request->lesson_id);

            if (!$lesson) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lesson not found.'
                ], 404);
            }

            $lesson->flashcards = $request->flashcards;
            $lesson->save();

            // Set success message in session
            session()->flash('success', 'Flashcards saved successfully!');
            // Session::flash('success', get_phrase('Flashcards saved successfully!'));
            return response()->json([
                'status' => 'success',
                'message' => session('success'),
            ], 200);
        } catch (\Exception $e) {
            // Set error message in session
            session()->flash('error', 'Failed to save flashcards.');

            return response()->json([
                'status' => 'error',
                'message' => session('error'),
                'debug' => $e->getMessage(), // Optional: helpful for debugging
            ], 500);
        }
    }


    public function ai_free_response_generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        function estimateTokensF($text)
        {
            return (int) (strlen($text) / 4);
        }

        $lesson = Lesson::find($request->lesson_id);
        $pdfPath = public_path('uploads/lesson_file/attachment/' . $lesson->attachment);
        // $flashcard = $lesson->test;

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        if (estimateTokensF($text) > 300) {
            $text = substr($text, 0, 2000);
        }

        // $prompt = "From the following educational content, create 50 free-response questions. Each question should have a short model/sample answer (2-5 sentences). Format them cleanly.\n\n" . $text;

        $apiKey = $chatgpt_api_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert free-response question writer. Create good educational questions and model answers.'],
                        ['role' => 'user', 'content' => 'From the following educational content generate 50 questions with model answers in JSON array format like [{"question": "...", "answer": "..."}]' . $text],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        return response()->json([
            'message' => 'Free Response Questions generated successfully.',
            'lesson_id' => $request->lesson_id,
            'free_responses' => json_decode($jsonString),
        ]);
    }
    public function saveFreeResponse(Request $request)
    {
        try {
            $request->validate([
                'lesson_id' => 'required|exists:lessons,id',
                'free_responses' => 'required|array',
            ]);

            $lesson = Lesson::find($request->lesson_id);

            if (!$lesson) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lesson not found.'
                ], 404);
            }

            $lesson->free_response_question = $request->free_responses;
            $lesson->save();

            // Set success message in session
            session()->flash('success', 'free_responses saved successfully!');
            // Session::flash('success', get_phrase('Flashcards saved successfully!'));
            return response()->json([
                'status' => 'success',
                'message' => session('success'),
            ], 200);
        } catch (\Exception $e) {
            // Set error message in session
            session()->flash('error', 'Failed to save free_responses.');

            return response()->json([
                'status' => 'error',
                'message' => session('error'),
                'debug' => $e->getMessage(), // Optional: helpful for debugging
            ], 500);
        }
    }
    public function ai_mcq_generate1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        function estimateTokens($text)
        {
            return (int) (strlen($text) / 4);
        }

        $lesson = Lesson::find($request->lesson_id);
        $pdfPath = public_path('uploads/lesson_file/attachment/' . $lesson->attachment);
        // $flashcard = $lesson->test;

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        if (estimateTokens($text) > 300) {
            $text = substr($text, 0, 2000);
        }

        // $prompt = "From the following educational content, create 50 free-response questions. Each question should have a short model/sample answer (2-5 sentences). Format them cleanly.\n\n" . $text;

        $apiKey = $chatgpt_api_key;
        $prompt = "From the following educational content, create 50 multiple-choice questions. Each question should have 4 options (A, B, C, D) and clearly indicate the correct answer. Format them cleanly in a json formate." . $text;

        // Make API call to OpenAI to generate MCQs
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert MCQ generator. Create high-quality multiple-choice questions with correct answers.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        return response()->json([
            'message' => 'Free Response Questions generated successfully.',
            'lesson_id' => $request->lesson_id,
            'mcqs' => json_decode($jsonString),
        ]);
    }

    public function ai_mcq_generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        function estimateToken($text)
        {
            return (int) (strlen($text) / 4);
        }

        $lesson = Lesson::find($request->lesson_id);
        $pdfPath = public_path('uploads/lesson_file/attachment/' . $lesson->attachment);

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        if (estimateToken($text) > 3000) {
            $text = substr($text, 0, 12000); // Truncate to fit token limit
        }

        $prompt = "From the following educational content, create 50 multiple-choice questions. Each question must be formatted in JSON like this:
[
  {
    \"question\": \"What is ...?\",
    \"options\": {
      \"A\": \"Option 1\",
      \"B\": \"Option 2\",
      \"C\": \"Option 3\",
      \"D\": \"Option 4\"
    },
    \"correct_answer\": \"B\"
  },
  ...
]
Make sure to only return a valid JSON array, no explanation.\n\nContent:\n" . $text;

        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $chatgpt_api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert MCQ generator. Return valid JSON.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'OpenAI API failed.',
                'details' => $response->json(),
            ]);
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        try {
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'MCQs generated successfully.',
                'lesson_id' => $request->lesson_id,
                'mcqs' => json_decode($jsonString),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to parse or save MCQs.',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function saveMCQs(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'mcqs' => 'required|array|min:1',
            'mcqs.*.question' => 'required|string',
            'mcqs.*.options' => 'required|array|size:4',
            'mcqs.*.correct_answer' => 'required|string|in:A,B,C,D',
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);

        // Ensure the MCQ options are stored cleanly
        $mcqs = collect($request->mcqs)->map(function ($mcq) {
            return [
                'question' => $mcq['question'],
                'options' => [
                    'A' => $mcq['options']['A'] ?? '',
                    'B' => $mcq['options']['B'] ?? '',
                    'C' => $mcq['options']['C'] ?? '',
                    'D' => $mcq['options']['D'] ?? '',
                ],
                'correct_answer' => $mcq['correct_answer'],
            ];
        })->toArray();

        $lesson->mcq_question = $mcqs;
        $lesson->save();

        return response()->json(['status' => 'success', 'message' => 'MCQs saved successfully.']);
    }

    public function ai_summary_generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        // Token estimation helper
        function estimateToke($text)
        {
            return (int) (strlen($text) / 4); // rough estimate: 1 token ≈ 4 characters
        }

        $lesson = Lesson::find($request->lesson_id);
        $pdfPath = public_path('uploads/lesson_file/attachment/' . $lesson->attachment);
        // $flashcard = $lesson->test;

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        // Trim text if estimated tokens exceed 3000 (~12,000 characters)
        if (estimateToke($text) > 3000) {
            $text = substr($text, 0, 12000);
        }


        // Create prompt
        $prompt = "You are an expert educational content summarizer. Return a well-structured HTML summary with proper headings, paragraphs, bold text, and bullet points. Avoid markdown. Target length: 1000 words:\n\n" . $text;

        // Your OpenAI API Key
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        $apiKey = $chatgpt_api_key;
        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert educational content summarizer. Return a well-structured HTML summary with proper headings, paragraphs, bold text, and bullet points. Avoid markdown. Target length: 1000 words.'
                        ],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $summary = trim($response['choices'][0]['message']['content']);

        return response()->json([
            'message' => 'Summary generated and saved successfully.',
            'lesson_id' => $lesson->id,
            'summary' => $summary,
        ]);
    }

    public function saveSummary(Request $request)
    {
        $lesson = Lesson::find($request->lesson_id);
        $lesson->summary = $request->summary;
        $lesson->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Summary saved successfully.'
        ]);
    }


    public function ai_final_question_generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        function estimateToken1($text)
        {
            return (int) (strlen($text) / 4);
        }

        $lesson = CertificateProgram::find($request->certificate_id);
        $pdfPath = public_path( $lesson->final_pdf);

        if (!file_exists($pdfPath)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'PDF file not found.',
            ]);
        }

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        if (estimateToken1($text) > 3000) {
            $text = substr($text, 0, 12000); // Truncate to fit token limit
        }

        $prompt = "From the following educational content, create 100 multiple-choice questions. Each question must be formatted in JSON like this:
[
  {
    \"question\": \"What is ...?\",
    \"options\": {
      \"A\": \"Option 1\",
      \"B\": \"Option 2\",
      \"C\": \"Option 3\",
      \"D\": \"Option 4\"
    },
    \"correct_answer\": \"B\"
  },
  ...
]
Make sure to only return a valid JSON array, no explanation.\n\nContent:\n" . $text;

        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $chatgpt_api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert MCQ generator. Return valid JSON.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'OpenAI API failed.',
                'details' => $response->json(),
            ]);
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        try {
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'MCQs generated successfully.',
                'certificate_id' => $request->certificate_id,
                'mcqs' => json_decode($jsonString),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to parse or save MCQs.',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function saveFinalQuestions(Request $request)
    {
        $request->validate([
            'certificate_id' => 'required|exists:lessons,id',
            'mcqs' => 'required|array|min:1',
            'mcqs.*.question' => 'required|string',
            'mcqs.*.options' => 'required|array|size:4',
            'mcqs.*.correct_answer' => 'required|string|in:A,B,C,D',
        ]);

        $lesson = CertificateProgram::findOrFail($request->certificate_id);

        // Ensure the MCQ options are stored cleanly
        $mcqs = collect($request->mcqs)->map(function ($mcq) {
            return [
                'question' => $mcq['question'],
                'options' => [
                    'A' => $mcq['options']['A'] ?? '',
                    'B' => $mcq['options']['B'] ?? '',
                    'C' => $mcq['options']['C'] ?? '',
                    'D' => $mcq['options']['D'] ?? '',
                ],
                'correct_answer' => $mcq['correct_answer'],
            ];
        })->toArray();

        $lesson->final_question = $mcqs;
        $lesson->save();

        return response()->json(['status' => 'success', 'message' => 'MCQs saved successfully.']);
    }

}
