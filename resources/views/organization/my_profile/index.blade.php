@extends('layouts.organization')

@push('title', get_phrase('My profile'))
@push('meta')
@endpush

@push('css')
<style>
    .edit_profile form {
        padding: 30px 40px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .edit_profile .form-group {
        margin-bottom: 25px;
    }

    .edit_profile label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .edit_profile input.form-control,
    .edit_profile textarea.form-control,
    .edit_profile select.form-control {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
    }

    .edit_profile .eBtn {
        background: linear-gradient(90deg, #007bff, #00c6ff);
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 6px;
        border: none;
        display: block;
        margin: 30px auto 10px auto; /* Center and add margin */
        transition: 0.3s ease-in-out;
    }

    .edit_profile .eBtn:hover {
        background: linear-gradient(90deg, #0056b3, #0099cc);
        color: white;
    }

    .profile-banner-area-container {
        padding: 30px 15px;
    }

    @media (min-width: 768px) {
        .profile-banner-area-container {
            padding: 30px 50px;
        }
    }
</style>
@endpush


@section('content')
    <!------------ My profile area start ------------>
    <div class="profile-banner-area"></div>
    <div class="container profile-banner-area-container">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="g-title mb-5">{{ get_phrase('Personal Information') }}</h4>

                <div class="my-panel message-panel edit_profile">
                    <form action="{{ route('update.profile', $user_details->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Basic Info --}}
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ get_phrase('Full Name') }}</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user_details->name }}" id="name">
                                </div>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ get_phrase('Email Address') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user_details->email }}" id="email">
                                </div>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ get_phrase('Phone Number') }}</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ $user_details->phone }}" id="phone">
                                </div>
                            </div>

                            {{-- Social Media --}}
                            @php
                                $socials = ['website', 'facebook', 'twitter', 'instagram', 'whatsapp', 'linkedin'];
                            @endphp
                            @foreach ($socials as $social)
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="{{ $social }}" class="form-label">{{ get_phrase(ucfirst($social)) }}</label>
                                        <input type="text" class="form-control" name="{{ $social }}" value="{{ $user_details->$social }}" id="{{ $social }}">
                                    </div>
                                </div>
                            @endforeach

                            {{-- Designation & Experience --}}
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="designation" class="form-label">{{ get_phrase('Designation') }}</label>
                                    <input type="text" class="form-control" name="designation" value="{{ $user_details->designation }}" id="designation">
                                </div>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="experience" class="form-label">{{ get_phrase('Experience') }}</label>
                                    <input type="text" class="form-control" name="experience" value="{{ $user_details->experience }}" id="experience">
                                </div>
                            </div>

                            {{-- Education Section --}}
                            @php
                                $educations = json_decode($user_details->educations, true) ?? [];
                                $countries = App\Models\Country::all();
                            @endphp

                            <div class="col-md-12 mb-4">
                                <label class="form-label">{{ get_phrase('Education') }}</label>

                                <a href="#" onclick="ajaxModal('{{ route('modal', ['frontend.default.student.my_profile.add_education']) }}', '{{ get_phrase('Add New Education') }}')" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px mb-3">
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
                                                        <p class="mb-1 text-muted">{{ get_phrase('Institute') }}: {{ $education['institute'] }}</p>
                                                        <p class="mb-1">{{ $education['city'] }}, {{ $education['country'] }}</p>
                                                        <p class="mb-1">{{ get_phrase('Start Date') }}: {{ $education['start_date'] }}</p>
                                                        <p class="mb-1">{{ get_phrase('End Date') }}: {{ $education['end_date'] ?? 'N/A' }}</p>
                                                        <p class="mb-1">
                                                            {{ get_phrase('Status') }}:
                                                            @if ($education['status'] == 'completed')
                                                                <span class="badge bg-success">{{ get_phrase('Completed') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ get_phrase('Ongoing') }}</span>
                                                            @endif
                                                        </p>
                                                    </div>

                                                    <div class="text-end">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fi-rr-menu-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="ajaxModal('{{ route('modal', ['frontend.default.student.my_profile.edit_education', 'index' => $index]) }}', '{{ get_phrase('Update Education') }}')">{{ get_phrase('Edit') }}</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="confirmModal('{{ route('manage1.education.remove', $index) }}')">{{ get_phrase('Delete') }}</a>
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

                            {{-- Video Thumbnail --}}
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="video_thumbnail">{{ get_phrase('Video Thumbnail') }}</label>
                                <div class="form-group text-start mb-3">
                                    <img id="previewImage" class="my-2" height="200px" src="{{ asset($user_details->video_thumbnail) }}" alt="Preview">
                                </div>
                                <input type="file" name="video_thumbnail" class="form-control ol-form-control" id="video_thumbnail" accept="image/*" />
                            </div>

                            {{-- Video URL --}}
                            <div class="fpb7 mb-2">
                                <label class="form-label ol-form-label">{{ get_phrase('Youtube video link for intro') }}</label>
                                <input type="text" class="form-control ol-form-control" name="video_url" value="{{ $user_details->video_url }}" />
                            </div>

                            {{-- Short Title --}}
                            <div class="fpb7 mb-2">
                                <label class="form-label ol-form-label">{{ get_phrase('A short title about yourself') }}</label>
                                <textarea rows="5" id="short-title" class="form-control ol-form-control" name="about">{{ $user_details->about }}</textarea>
                            </div>

                            {{-- Skills --}}
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="skills" class="form-label">{{ get_phrase('Skills') }}</label>
                                    <input type="text" class="form-control tagify" name="skills" data-role="tagsinput" value="{{ $user_details->skills }}" id="skills">
                                </div>
                            </div>

                            {{-- Biography --}}
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
    <!------------ My profile area end ------------>
@endsection

@push('js')
@endpush
