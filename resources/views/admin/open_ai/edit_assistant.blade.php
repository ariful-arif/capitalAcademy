@php $ai_assistant = json_decode(get_settings('openai_assistant'), true); @endphp


<form action="{{route('admin.open.ai.assistant.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
        <label class="form-label ol-form-label" for="assistant_name">{{ get_phrase('Assistant Name') }}</label>
        <input class="form-control ol-form-control" type="text" value="{{$ai_assistant['assistant_name']}}" id="assistant_name" name="assistant_name" required>
    </div>

    <div class="mb-3">
        <label class="form-label ol-form-label" for="Instructions">{{ get_phrase('Instructions') }}</label>
        <textarea class="form-control ol-form-control" id="Instructions" name="instructions" placeholder="Answer questions only using the provided documents" rows="4" required>{{$ai_assistant['instructions']}}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label ol-form-label" for="Instructions">{{ get_phrase('Upload document files') }}<small>(.pdf, .txt, .docx, .csv, .json)</small></label>
        <input class="form-control ol-form-control" type="file" id="files" name="files[]" multiple accept=".pdf,.txt,.docx,.csv,.json">

        <h6 class="mt-3 mb-1">{{get_phrase('Uploaded files')}}</h6>
        <ul>
            @foreach($ai_assistant['files']['file_paths'] as $index => $file_path)
                <li>
                    <a href="{{asset($file_path)}}" target="_blank">{{basename($file_path)}}</a>
                    <button type="button" class="btn text-danger btn-sm" onclick="confirmModal('{{ route('admin.open.ai.remove.file', ['index' => $index]) }}')">{{ get_phrase('Delete') }}</button>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update') }}</button>
    </div>
</form>