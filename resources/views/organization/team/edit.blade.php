@extends('layouts.organization')
@push('title', get_phrase('Edit Team'))

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
                    <form action="{{ route('organization.teams.update', $teams->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 pb-2">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="title">{{ get_phrase('Name') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $teams->name) }}"
                                            class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Team Name') }}" required>
                                    </div>


                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Certicificete Given Course') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="number" name = "team_members" class="form-control ol-form-control"
                                            value="{{ old('team_members', $teams->team_members) }}"
                                            placeholder="{{ get_phrase('Enter team member amount') }}" required>
                                    </div>

                                    {{-- <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="multiple_user_id">{{ get_phrase('Certificate Program Courses') }}
                                            <span class="required text-danger">*</span>
                                        </label>
                                        <select class="ol-select2 select2-hidden-accessible" name="member_ids[]"
                                            multiple="multiple" required>
                                            @php
                                                $selected_courses = is_string($teams->member_ids)
                                                    ? json_decode($teams->member_ids, true)
                                                    : $teams->member_ids;
                                            @endphp
                                            @foreach (App\Models\User::where('status', 1)->where('organization_id', auth()->user()->id)->orderBy('name', 'desc')->get() as $course)
                                                <option value="{{ $course->id }}"
                                                    {{ in_array($course->id, $selected_courses) ? 'selected' : '' }}>
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn ol-btn-primary float-end">
                                    {{ get_phrase('Update') }}
                                </button>
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
