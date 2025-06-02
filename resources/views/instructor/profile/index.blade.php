@extends('layouts.instructor')
@push('title', get_phrase('Manage profile'))
@push('meta')@endpush
@push('css')@endpush
@section('content')
    @php
        $auth = auth()->user();
    @endphp

    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    <span>{{ get_phrase('Manage profile') }}</span>
                </h4>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-xl-7">
            <div class="ol-card p-4">
                <div class="ol-card-body">
                    <form action="{{ route('instructor.manage.profile.update') }}" method="post" enctype="multipart/form-data">@csrf
                        <input type="hidden" name="type" value="general">
                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Name') }}</label>
                            <input type="text" class="form-control ol-form-control" name="name" value="{{ $auth->name }}" required />
                        </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Email') }}</label>
                            <input type="email" class="form-control ol-form-control" name="email" value="{{ $auth->email }}" required />
                        </div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ get_phrase('Phone Number') }}</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ $auth->phone }}" id="phone">
                                </div>

                        {{-- <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Facebook link') }}</label>
                            <input type="text" class="form-control ol-form-control" name="facebook" value="{{ $auth->facebook }}" />
                        </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Twitter link') }}</label>
                            <input type="text" class="form-control ol-form-control" name="twitter" value="{{ $auth->twitter }}" />
                        </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Linkedin link') }}</label>
                            <input type="text" class="form-control ol-form-control" name="linkedin" value="{{ $auth->linkedin }}" />
                        </div> --}}
                         {{-- Social Media --}}
                            @php
                                $socials = ['website', 'facebook', 'twitter', 'instagram', 'whatsapp', 'linkedin'];
                            @endphp
                            @foreach ($socials as $social)
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label for="{{ $social }}" class="form-label">{{ get_phrase(ucfirst($social)) }}</label>
                                        <input type="text" class="form-control" name="{{ $social }}" value="{{ $auth->$social }}" id="{{ $social }}">
                                    </div>
                                </div>
                            @endforeach

                                    {{-- Designation & Experience --}}
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="designation" class="form-label">{{ get_phrase('Designation') }}</label>
                                    <input type="text" class="form-control" name="designation" value="{{ $auth->designation }}" id="designation">
                                </div>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="experience" class="form-label">{{ get_phrase('Experience') }}</label>
                                    <input type="text" class="form-control" name="experience" value="{{ $auth->experience }}" id="experience">
                                </div>
                            </div>

                               {{-- Education Section --}}
                            @php
                                $educations = json_decode($auth->educations, true) ?? [];
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
                                    <img id="previewImage" class="my-2" height="200px" src="{{ asset($auth->video_thumbnail) }}" alt="Preview">
                                </div>
                                <input type="file" name="video_thumbnail" class="form-control ol-form-control" id="video_thumbnail" accept="image/*" />
                            </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Youtube video link for tutor intro') }}</label>
                            <input type="text" class="form-control ol-form-control" name="video_url" value="{{ $auth->video_url }}" />
                        </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('A short title about yourself') }}</label>
                            <textarea rows="5" id="short-title" class="form-control ol-form-control" name="about">{{ $auth->about }}</textarea>
                        </div>

                        <div class="fpb-7 mb-3">
                            <label class="form-label ol-form-label" for="skills">{{ get_phrase('Skills') }}</label>
                            <input type="text" name="skills" value="{{ $auth->skills }}" id="skills" class="tagify ol-form-control w-100" data-role="tagsinput">
                            <small class="text-muted">{{ get_phrase('Write your skill and click the enter button') }}</small>
                        </div>

                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Biography') }}</label>
                            <textarea rows="5" class="form-control ol-form-control text_editor" name="biography" placeholder="">{!! removeScripts($auth->biography) !!}</textarea>
                        </div>


                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Photo') }}
                                <small>({{ get_phrase('The image size should be any square image') }})</small>
                            </label>
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <img class = "rounded-circle img-thumbnail image-50" src="{{ get_image($auth->photo) }}" alt="">
                                </div>
                                <div class="col-10">
                                    <input type="file" class="form-control ol-form-control" name="photo" id="user_image" onchange="changeTitleOfImageUploader(this.id)" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="fpb7 mb-2">
                            <button type="submit" class="btn mt-4 ol-btn-primary">{{ get_phrase('Update profile') }}</button>
                        </div>
                    </form>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
        <div class="col-xl-5">
            <div class="ol-card p-4">
                <div class="ol-card-body">
                    <form action="{{ route('instructor.manage.profile.update') }}" method="post"> @csrf
                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Current password') }}</label>
                            <input type="password" class="form-control ol-form-control" name="current_password" required />
                        </div>
                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('New password') }}</label>
                            <input type="password" class="form-control ol-form-control" name="new_password" required />
                        </div>
                        <div class="fpb7 mb-2">
                            <label class="form-label ol-form-label">{{ get_phrase('Confirm password') }}</label>
                            <input type="password" class="form-control ol-form-control" name="confirm_password" required />
                        </div>
                        <div class="fpb7 mb-2">
                            <button type="submit" class="ol-btn-primary">{{ get_phrase('Update password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
