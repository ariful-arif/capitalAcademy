@extends('layouts.admin')
@push('title', get_phrase('Create Certificate'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Add new Certificate') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('admin.certificate_program.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pb-2">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Title') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name = "title" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Certificate Title') }}" required>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="short_description">{{ get_phrase('Short Description') }}</label>
                                        <textarea name="short_description" placeholder="{{ get_phrase('Enter Short Description') }}"
                                            class="form-control ol-form-control" rows="5"></textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="description">{{ get_phrase('Description') }}</label>
                                        <textarea name="description" placeholder="{{ get_phrase('Enter Description') }}"
                                            class="form-control ol-form-control text_editor"></textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Certicificete Given Course') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="number" name = "certificated_course_count" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Certificate Course amount') }}" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fpb-7 mb-3 ">
                                    <label for="course_status"
                                        class="col-sm-2 col-form-label ol-form-label">{{ get_phrase('Create as') }}
                                        <span class="text-danger ms-1">*</span></label>
                                    <div class="eRadios">
                                        <div class="form-check">
                                            <input type="radio" value="active" name="status"
                                                class="form-check-input eRadioSuccess" id="status_active" required checked>
                                            <label for="status_active"
                                                class="form-check-label">{{ get_phrase('Active') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" value="private" name="status"
                                                class="form-check-input eRadioPrimary" id="status_private" required>
                                            <label for="status_private"
                                                class="form-check-label">{{ get_phrase('Private') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" value="inactive" name="status"
                                                class="form-check-input eRadioDark" id="status_inactive" required>
                                            <label for="status_inactive"
                                                class="form-check-label">{{ get_phrase('Inactive') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label"
                                        for="multiple_user_id">{{ get_phrase('Certificate Program Courses') }}<span
                                            class="required text-danger">*</span>
                                    </label>
                                    <select class="ol-select2 select2-hidden-accessible" name="course_ids[]"
                                        multiple="multiple" required data-placeholder="Select certificate course">
                                        {{-- <option value="" disabled>{{ get_phrase('Select courses') }}</option> --}}
                                        <option value=""  disabled>{{ get_phrase('Select certificate course') }}</option>
                                        @foreach (App\Models\Course::where('status', 'active')->where('user_id', auth()->user()->id)->orderBy('title', 'desc')->get() as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fpb-7 mb-3">
                                    <label for="thumbnail"
                                        class="form-label ol-form-label">{{ get_phrase('Thumbnail') }}</label>
                                    <input type="file" name="thumbnail" class="form-control ol-form-control"
                                        id="thumbnail" accept="image/*" />
                                </div>

                                <p class="title text-14px mb-3">{{ get_phrase('Certificate template') }}</p>
                                <div class="ol-card-body">
                                    <div class="form-group text-start mb-3">
                                        <div class="">
                                            <img id="previewImage" class="my-2" height="200px"
                                                src="{{ asset('uploads/certificate-template/placeholder/placeholder.png') }}"
                                                alt="Preview">
                                        </div>
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
                                <button type="submit"
                                    class="btn ol-btn-primary float-end">{{ get_phrase('Submit') }}</button>
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
    </script>
@endpush
