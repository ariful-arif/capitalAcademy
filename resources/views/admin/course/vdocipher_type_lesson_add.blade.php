<input type="hidden" name="lesson_provider" value="vdocipher">
<input type="hidden" name="lesson_type" value="vdocipher">

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Video Id') }}</label>
    <input type="text" id="vdocipher_video_id" name="lesson_src" class="form-control ol-form-control"
        placeholder="{{ get_phrase('Enter your vdocipher video id') }}">
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Duration') }}</label>
    <input class="form-control ol-form-control duration_picker" name="duration">
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Thumbnail') }}<small>({{ get_phrase('The image size should be') }})</small> </label>
    <div class="input-group">
        <div class="custom-file w-100">
            <input type="file" class="form-control ol-form-control" id="thumbnail" name="thumbnail"
                onchange="changeTitleOfImageUploader(this)">
        </div>
    </div>
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Caption') }}( {{ get_phrase('.vtt') }} )</label>
    <div class="input-group">
        <div class="custom-file w-100">
            <input type="file" class="form-control ol-form-control" id="caption" name="caption"
                onchange="changeTitleOfImageUploader(this)" accept=".vtt">
        </div>
    </div>
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
    initializeDurationPickers([".duration_picker"]);
</script>
