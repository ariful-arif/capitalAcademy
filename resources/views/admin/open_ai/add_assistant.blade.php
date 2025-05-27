<form action="{{route('admin.open.ai.assistant.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
        <label class="form-label ol-form-label" for="assistant_name">{{ get_phrase('Assistant Name') }}</label>
        <input class="form-control ol-form-control" type="text" id="assistant_name" name="assistant_name" required>
    </div>

    <div class="mb-3">
        <label class="form-label ol-form-label" for="Instructions">{{ get_phrase('Instructions') }}</label>
        <textarea class="form-control ol-form-control" id="Instructions" name="instructions" placeholder="Answer questions only using the provided documents" rows="4" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label ol-form-label" for="Instructions">{{ get_phrase('Upload document files') }}<small>(.pdf, .txt, .docx, .csv, .json)</small></label>
        <input class="form-control ol-form-control" type="file" id="files" name="files[]" multiple accept=".pdf,.txt,.docx,.csv,.json" required>
    </div>
    
    <div class="mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Create') }}</button>
    </div>
</form>