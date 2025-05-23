<input type="hidden" name="lesson_type" value="google_drive">
<input type="hidden" name="lesson_provider" value="drive_video">

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Video url') }}</label>
    <input type="text" id="video_url" name="lesson_src" class="form-control ol-form-control">
    <small class="form-label text-danger text-12px d-hidden mb-0" id="invalid_url">{{ get_phrase('Invalid url') }}. {{ get_phrase('Your video source has to be either Google drive') }}</small>
</div>

<div class="form-group mb-2">
    <label>{{ get_phrase('Duration') }}</label>
    <input id="duration" name="duration" class="form-control ol-form-control">
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Upload Audio file') }}</label>
    <div class="input-group">
        <div class="custom-file w-100">
            <input type="file" class="form-control ol-form-control" id="audio_file" name="audio_file"
                accept="audio/*">
        </div>
    </div>
</div>


<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Upload PDF file') }}</label>
    <div class="input-group">
        <div class="custom-file w-100">
            <input type="file" class="form-control ol-form-control" id="pdf_file" name="pdf_file">
        </div>
    </div>
</div>

<script>
    "use strict";

    initializeDurationPickers(["#duration"]);
</script>
