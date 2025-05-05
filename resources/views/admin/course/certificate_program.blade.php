<div class="row mb-3">
    <label class="form-label ol-form-label col-sm-3 col-form-label">{{ get_phrase('Certificate Course') }}<span
            class="text-danger ms-1">*</span></label>
    <div class="col-sm-6">
        <div class="eRadios">
            <div class="form-check">
                <input type="radio" name="is_certificate_course" value="1" class="form-check-input eRadioSuccess"
                    id="paida" onchange="$('#is_certificate_course').slideDown(200)"
                    @if ($course_details->is_certificate_course == 1) checked @endif>
                <label for="Yes" class="form-check-label">{{ get_phrase('Yes') }}</label>
            </div>

            <div class="form-check">
                <input type="radio" name="is_certificate_course" value="0" class="form-check-input eRadioSuccess"
                    id="freea" onchange="$('#is_certificate_course').slideUp(200)"
                    @if ($course_details->is_certificate_course != 1) checked @endif>
                <label for="No" class="form-check-label">{{ get_phrase('No') }}</label>
            </div>
        </div>
    </div>
</div>
<div class="is_certificate_course @if ($course_details->is_certificate_course != 1) d-hidden @endif" id="is_certificate_course">
    <div class="row mb-3">
        <label
            class="form-label ol-form-label col-sm-3 col-form-label">{{ get_phrase('Certificate Course Type') }}<span class="required text-danger">*</span></label>
        <div class="col-sm-6">
            <div class="eRadios">
                <div class="form-check">
                    <input type="radio" id="core_course" name="certificate_course_type"
                        class="form-check-input eRadioSuccess" value="core"
                        {{ $course_details->certificate_course_type == 'core' ? 'checked' : '' }}>
                    <label class="form-check-label" for="core_course">{{ get_phrase('Core Course') }}</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="elective_course" name="certificate_course_type"
                        class="form-check-input eRadioSuccess" value="elective"
                        {{ $course_details->certificate_course_type == 'elective' ? 'checked' : '' }}>
                    <label class="form-check-label" for="elective_course">{{ get_phrase('Elective Course') }}</label>
                </div>

            </div>

        </div>
    </div>
    <div class="row mb-3">
        <label class="form-label ol-form-label col-sm-3 col-form-label"
            for="multiple_user_id">{{ get_phrase('Certificate Program') }}
            <span class="required text-danger">*</span>
        </label>
        <div class="col-sm-6">
            <select class="ol-select2 select2-hidden-accessible" name="certificate_ids[]" multiple="multiple" required
                data-placeholder="Select certificate program">
                <option value="" disabled>{{ get_phrase('Select certificate program') }}</option>
                @foreach (App\Models\CertificateProgram::where('status', 'active')
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('title', 'desc')
                    ->get() as $certificate)
                    <option value="{{ $certificate->id }}"
                        @if(in_array($course_details->id, json_decode($certificate->course_ids ?? '[]', true))) selected @endif>
                        {{ $certificate->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
