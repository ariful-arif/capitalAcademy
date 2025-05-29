@extends('layouts.admin')
@push('title', get_phrase('Edit Certificate'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Edit Certificate') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('admin.certificate_program.update', $certificate_programs->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pb-2">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Title') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="title"
                                            value="{{ old('title', $certificate_programs->title) }}"
                                            class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Certificate Title') }}" required>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="short_description">{{ get_phrase('Short Description') }}</label>
                                        <textarea name="short_description" class="form-control ol-form-control" rows="5"
                                            placeholder="{{ get_phrase('Enter Short Description') }}">{{ old('short_description', $certificate_programs->short_description) }}</textarea>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="description">{{ get_phrase('Description') }}</label>
                                        <textarea name="description" class="form-control ol-form-control text_editor"
                                            placeholder="{{ get_phrase('Enter Description') }}">{{ old('description', $certificate_programs->description) }}</textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Certicificete Given Course') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="number" name = "certificated_course_count"
                                            class="form-control ol-form-control"
                                            value="{{ old('certificated_course_count', $certificate_programs->certificated_course_count) }}"
                                            placeholder="{{ get_phrase('Enter Certificate Course amount') }}" required>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="final_pdf">{{ get_phrase('Final exam pdf') }}</label>
                                        <input type="file" name="final_pdf" class="form-control ol-form-control"
                                            id="final_pdf"  />
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="pass_mark_percentage">{{ get_phrase('Percentage of pass mark') }}<span class="text-danger ms-1">*</span></label>
                                        <input type="number" value="{{$certificate_programs->pass_mark_percentage}}" name="pass_mark_percentage" id="pass_mark_percentage" class="form-control ol-form-control" min="0" max="100" placeholder="{{ get_phrase('Enter percentage of pass mark') }}" required>
                                    </div>

                                    <hr class="my-3">
                                    <h6 class="mb-3">{{get_phrase('Program Overview')}}</h6>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="program_overview">{{ get_phrase('Overview') }}<span class="text-danger ms-1">*</span></label>
                                        <textarea name="program_overview" id="program_overview" class="form-control ol-form-control">{{$certificate_programs->program_overview}}</textarea>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="interactive_exercise">{{ get_phrase('Number of interactive exercise') }}<span class="text-danger ms-1">*</span></label>
                                        <input type="number" value="{{$certificate_programs->interactive_exercise}}" name="interactive_exercise" id="interactive_exercise" class="form-control ol-form-control" min="0" placeholder="{{ get_phrase('Enter number of interactive exercise') }}" required>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="certificate_type">{{ get_phrase('Type of certificate') }}<span class="text-danger ms-1">*</span></label>
                                        <input type="text" value="{{$certificate_programs->certificate_type}}" name="certificate_type" id="certificate_type" class="form-control ol-form-control" placeholder="{{ get_phrase('Certificate type') }}" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="fpb-7 mb-3">
                                    <label for="course_status"
                                        class="col-sm-2 col-form-label ol-form-label">{{ get_phrase('Create as') }}
                                        <span class="text-danger ms-1">*</span></label>
                                    <div class="eRadios">
                                        @php $status = old('status', $certificate_programs->status); @endphp
                                        <div class="form-check">
                                            <input type="radio" value="active" name="status"
                                                class="form-check-input eRadioSuccess" id="status_active" required
                                                {{ $status == 'active' ? 'checked' : '' }}>
                                            <label for="status_active"
                                                class="form-check-label">{{ get_phrase('Active') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" value="private" name="status"
                                                class="form-check-input eRadioPrimary" id="status_private" required
                                                {{ $status == 'private' ? 'checked' : '' }}>
                                            <label for="status_private"
                                                class="form-check-label">{{ get_phrase('Private') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" value="inactive" name="status"
                                                class="form-check-input eRadioDark" id="status_inactive" required
                                                {{ $status == 'inactive' ? 'checked' : '' }}>
                                            <label for="status_inactive"
                                                class="form-check-label">{{ get_phrase('Inactive') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label"
                                        for="multiple_user_id">{{ get_phrase('Certificate Program Courses') }}
                                        <span class="required text-danger">*</span>
                                    </label>
                                    <select class="ol-select2 select2-hidden-accessible" name="course_ids[]"
                                        multiple="multiple" required>
                                        @php
                                            // $selected_courses = $certificate_programs->course_ids->pluck('id')->toArray();
                                            $selected_courses = is_string($certificate_programs->course_ids)
                                                ? json_decode($certificate_programs->course_ids, true)
                                                : $certificate_programs->course_ids;
                                        @endphp
                                        @foreach (App\Models\Course::where('status', 'active')->where('user_id', auth()->user()->id)->orderBy('title', 'desc')->get() as $course)
                                            <option value="{{ $course->id }}"
                                                {{ in_array($course->id, $selected_courses) ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label"
                                        for="logo">{{ get_phrase('Logo') }}</label>
                                    <input type="file" name="logo" class="form-control ol-form-control"
                                        id="logo" accept="image/*" />
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label"
                                        for="thumbnail">{{ get_phrase('Thumbnail') }}</label>
                                    <input type="file" name="thumbnail" class="form-control ol-form-control"
                                        id="thumbnail" accept="image/*" />
                                </div>

                                <p class="title text-14px mb-3">{{ get_phrase('Certificate template') }}</p>
                                <div class="ol-card-body">
                                    <div class="form-group text-start mb-3">
                                        <img id="previewImage" class="my-2" height="200px"
                                            src="{{ asset($certificate_programs->certificate_template) }}" alt="Preview">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label ol-form-label" for="certificate_template">
                                            {{ get_phrase('Upload your certificate template') }}
                                        </label>
                                        <input type="file" class="form-control" name="certificate_template"
                                            id="certificate_template" accept="image/*">
                                    </div>
                                </div>
                            </div>

                           <div class="pt-2">
                                <button type="submit" class="btn ol-btn-primary float-end">
                                    {{ get_phrase('Update') }}
                                </button>
                                <a href="{{route('admin.certificate_program_builder', ['id' => $certificate_programs->id])}}" target="_blank" class="btn ol-btn-primary float-end me-2">
                                    {{ get_phrase('Edit Layout') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        // Image Preview for Certificate Template
        document.getElementById('certificate_template').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Initialize Select2
        $(document).ready(function() {
            $('.ol-select2').select2({
                placeholder: "Select certificate course",
                allowClear: true
            });
        });
    </script>
@endpush