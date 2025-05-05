@extends('layouts.default')
@push('title', get_phrase('My profile'))
@push('meta')@endpush
@push('css')@endpush
@section('content')
    <!------------ My profile area start  ------------>
    <section class="course-content">
        <div class="profile-banner-area"></div>
        <div class="container profile-banner-area-container">
            <div class="row">
                @include('frontend.default.student.left_sidebar')
                <div class="col-lg-9">
                    <h4 class="g-title mb-5">{{ get_phrase('Personal Information') }}</h4>
                    <div class="my-panel message-panel edit_profile">
                        <form action="{{ route('update.profile', $user_details->id) }}" method="POST">@csrf
                            <div class="row">
                                <div class="col-lg-12 mb-20">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{ get_phrase('Full Name') }}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $user_details->name }}" id="name">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{ get_phrase('Email Address') }}</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ $user_details->email }}" id="email">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">{{ get_phrase('Phone Number') }}</label>
                                        <input type="tel" class="form-control" name="phone"
                                            value="{{ $user_details->phone }}" id="phone">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="website" class="form-label">{{ get_phrase('Website') }}</label>
                                        <input type="text" class="form-control" name="website"
                                            value="{{ $user_details->website }}" id="website">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="facebook" class="form-label">{{ get_phrase('Facebook') }}</label>
                                        <input type="text" class="form-control" name="facebook"
                                            value="{{ $user_details->facebook }}" id="facebook">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="twitter" class="form-label">{{ get_phrase('Twitter') }}</label>
                                        <input type="text" class="form-control" name="twitter"
                                            value="{{ $user_details->twitter }}" id="twitter">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="instagram" class="form-label">{{ get_phrase('Instagram') }}</label>
                                        <input type="text" class="form-control" name="instagram"
                                            value="{{ $user_details->instagram }}" id="instagram">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="linkedin" class="form-label">{{ get_phrase('Linkedin') }}</label>
                                        <input type="text" class="form-control" name="linkedin"
                                            value="{{ $user_details->linkedin }}" id="linkedin">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="designation" class="form-label">{{ get_phrase('Designation') }}</label>
                                        <input type="text" class="form-control" name="designation"
                                            value="{{ $user_details->designation }}" id="designation">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="experience" class="form-label">{{ get_phrase('Experience') }}</label>
                                        <input type="text" class="form-control" name="experience"
                                            value="{{ $user_details->experience }}" id="experience">
                                    </div>
                                </div>
                                @php
                                    $educations = json_decode($user_details->educations, true) ?? [];
                                    $countries = App\Models\Country::all();
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">{{ get_phrase('Education') }}</label>
                                        <a href="#"
                                            onclick="ajaxModal('{{ route('modal', ['frontend.default.student.my_profile.add_education']) }}', '{{ get_phrase('Add New Education') }}')"
                                            class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                                            <span class="fi-rr-plus"></span>
                                            <span>{{ get_phrase('Add New Education') }}</span>
                                        </a>

                                        <div class="row">
                                            @foreach ($educations as $key => $education)
                                                @php $index = $key @endphp
                                                <div class="col-md-6 mb-4">
                                                    <div class="card shadow-sm p-3">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h5 class="mb-1">{{ ucfirst($education['title']) }}</h5>
                                                                <p class="mb-1 text-muted">{{ get_phrase('Institute') }}:
                                                                    {{ $education['institute'] }}</p>
                                                                <p class="mb-1">{{ $education['city'] }},
                                                                    {{ $education['country'] }}</p>
                                                                <p class="mb-1">{{ get_phrase('Start Date') }}:
                                                                    {{ $education['start_date'] }}</p>
                                                                <p class="mb-1">{{ get_phrase('End Date') }}:
                                                                    {{ $education['end_date'] ?? 'N/A' }}</p>
                                                                <p class="mb-1">
                                                                    {{ get_phrase('Status') }}:
                                                                    @if ($education['status'] == 'completed')
                                                                        <span
                                                                            class="badge bg-success">{{ get_phrase('Completed') }}</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-danger">{{ get_phrase('Ongoing') }}</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-light" type="button"
                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="fi-rr-menu-dots-vertical"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        <li>
                                                                            <a class="dropdown-item" href="#"
                                                                                onclick="ajaxModal('{{ route('modal', ['frontend.default.student.my_profile.edit_education', 'index' => $index]) }}', '{{ get_phrase('Update Education') }}')">{{ get_phrase('Edit') }}</a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="javascript:void(0)"
                                                                                onclick="confirmModal('{{ route('manage1.education.remove', $index) }}')">{{ get_phrase('Delete') }}</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>

                                {{-- @foreach ($educations as $education)
                                    <div class="form-group mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" name="title[]" class="form-control ol-form-control"
                                            value="{{ $education['title'] }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Institute') }}</label>
                                        <input type="text" name="institute[]" class="form-control ol-form-control"
                                            value="{{ $education['institute'] }}">
                                    </div>

                                    <!-- Country and City in the same row -->
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label class="form-label ol-form-label">{{ get_phrase('Country') }}</label>
                                            <select class="form-control ol-select2" data-toggle="select2"
                                                name="country[]">
                                                <option value="">{{ get_phrase('Select a country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->name }}"
                                                        @if ($education['country'] == $country->name) selected @endif>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 form-group mb-3">
                                            <label class="form-label ol-form-label">{{ get_phrase('City') }}</label>
                                            <input type="text" name="city[]" class="form-control ol-form-control"
                                                value="{{ $education['city'] }}">
                                        </div>
                                    </div>

                                    <!-- Start Date and End Date in the same row -->
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label class="form-label ol-form-label"
                                                for="start_date">{{ get_phrase('Start Date') }}</label>
                                            <input type="date" name="start_date[]" id="start_date"
                                                class="form-control ol-form-control"
                                                value="{{ $education['start_date'] }}">
                                        </div>

                                        <div class="col-md-6 form-group mb-3">
                                            <label class="form-label ol-form-label"
                                                for="end_date">{{ get_phrase('End Date') }}</label>
                                            <input type="date" name="end_date[]" id="end_date"
                                                class="form-control ol-form-control"
                                                value="{{ $education['end_date'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <input type="checkbox" name="status[]" id="status" value="ongoing"
                                            class="form-check-input" @if ($education['status'] === 'ongoing') checked @endif>
                                        <label for="status"
                                            class="form-label ol-form-label">{{ get_phrase('This degree/course is currently ongoing') }}</label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="description[]" class="form-control text_editor">{{ $education['description'] }}</textarea>
                                    </div>
                                @endforeach --}}

                                <div class="fpb7 mb-2">
                                    <label
                                        class="form-label ol-form-label">{{ get_phrase('Youtube video link for intro') }}</label>
                                    <input type="text" class="form-control ol-form-control" name="video_url"
                                        value="{{ $user_details->video_url }}" />
                                </div>

                                <div class="fpb7 mb-2">
                                    <label
                                        class="form-label ol-form-label">{{ get_phrase('A short title about yourself') }}</label>
                                    <textarea rows="5" id="short-title" class="form-control ol-form-control" name="about">{{ $user_details->about }}</textarea>
                                </div>
                                <div class="col-lg-12 mb-20">
                                    <div class="form-group">
                                        <label for="skills" class="form-label">{{ get_phrase('Skills') }}</label>
                                        <input type="text" class="form-control tagify" name="skills"
                                            data-role="tagsinput" value="{{ $user_details->skills }}" id="skills">
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-20">
                                    <div class="form-group">
                                        <label for="biography" class="form-label">{{ get_phrase('Biography') }}</label>
                                        <textarea name="biography" class="form-control" id="biography" cols="30" rows="5">{{ $user_details->biography }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button class="eBtn btn gradient mt-10">{{ get_phrase('Save Changes') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!------------ My profile area end  ------------>
@endsection
@push('js')

@endpush
