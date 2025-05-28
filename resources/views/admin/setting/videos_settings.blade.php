<style>
    .video-card {
        position: relative;
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: transform 0.3s ease;
        z-index: 1;
    }

    .video-card::before {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        background: linear-gradient(135deg, #eeeeee, #fdf7f7);
        border-radius: 1.2rem;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
        filter: blur(10px);
    }

    .video-card:hover::before {
        opacity: 1;
    }

    .video-card:hover {
        /* transform: translateY(-3px); */
    }
</style>

<div class="row">
    <h4 class="title mt-4 mb-3">{{ get_phrase('Home Banner videos') }}</h4>
    {{-- Banner Video --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Banner video') }}</h5>

                @php $banner_video = get_frontend_settings('banner_video'); @endphp
                @if (!empty($banner_video) && file_exists(public_path($banner_video)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($banner_video) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="banner_video">
                    <div class="mb-3">
                        <input type="file" name="banner_video" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Banner Video') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Banner video dark mode') }}</h5>

                @php $banner_video_dark = get_frontend_settings('banner_video_dark'); @endphp
                @if (!empty($banner_video_dark) && file_exists(public_path($banner_video_dark)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($banner_video_dark) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="banner_video_dark">
                    <div class="mb-3">
                        <input type="file" name="banner_video_dark" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Banner Video Dark') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Banner video light mode') }}</h5>

                @php $banner_video_light = get_frontend_settings('banner_video_light'); @endphp
                @if (!empty($banner_video_light) && file_exists(public_path($banner_video_light)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($banner_video_light) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="banner_video_light">
                    <div class="mb-3">
                        <input type="file" name="banner_video_light" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Banner Video Light') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <h5 class="card-title mb-3">{{ get_phrase('Banner video thumbnail') }}</h5>
            <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                <input type="hidden" name="type" value="banner_video_thumbnail">
                <div class="form-group mb-2">
                    <div class="wrapper-image-preview  d-flex justify-content-center">
                        <div class="box">
                            <div class="upload-options">
                                <img src="{{ asset(get_frontend_settings('banner_video_thumbnail')) }}" alt="" class="bg-dark radious-15px px-2 py-2">
                                <label for="banner_video_thumbnail" class="btn ol-card p-4-text">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 6C17.39 6 16.83 5.65 16.55 5.11L15.83 3.66C15.37 2.75 14.17 2 13.15 2H10.86C9.83005 2 8.63005 2.75 8.17005 3.66L7.45005 5.11C7.17004 5.65 6.61005 6 6.00005 6C3.83005 6 2.11005 7.83 2.25005 9.99L2.77005 18.25C2.89005 20.31 4.00005 22 6.76005 22H17.24C20 22 21.1 20.31 21.23 18.25L21.75 9.99C21.89 7.83 20.17 6 18 6ZM10.5 7.25H13.5C13.91 7.25 14.25 7.59 14.25 8C14.25 8.41 13.91 8.75 13.5 8.75H10.5C10.09 8.75 9.75005 8.41 9.75005 8C9.75005 7.59 10.09 7.25 10.5 7.25ZM12 18.12C10.14 18.12 8.62005 16.61 8.62005 14.74C8.62005 12.87 10.13 11.36 12 11.36C13.87 11.36 15.38 12.87 15.38 14.74C15.38 16.61 13.86 18.12 12 18.12Z" fill="#797c8b" />
                                    </svg>
                                    <small>{{ get_phrase('Click here to choose a video thumbnail') }}</small>
                                    <small class="d-block">(330 X 70)</small> </label>
                                <input id="banner_video_thumbnail" type="file" class="image-upload d-none" name="banner_video_thumbnail" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn ol-btn-primary w-100">{{ get_phrase('Save changes') }}</button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <h4 class="title mt-4 mb-3">{{ get_phrase('Home Body videos') }}</h4>
    {{-- Body Video --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Body video') }}</h5>

                @php $home_page_body_video = get_frontend_settings('home_page_body_video'); @endphp
                @if (!empty($home_page_body_video) && file_exists(public_path($home_page_body_video)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($home_page_body_video) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="home_page_body_video">
                    <div class="mb-3">
                        <input type="file" name="home_page_body_video" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Body Video') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Body Video dark') }}</h5>

                @php $body_video_dark = get_frontend_settings('body_video_dark'); @endphp
                @if (!empty($body_video_dark) && file_exists(public_path($body_video_dark)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($body_video_dark) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="body_video_dark">
                    <div class="mb-3">
                        <input type="file" name="body_video_dark" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Body Video Dark') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Body Video light') }}</h5>

                @php $body_video_light = get_frontend_settings('body_video_light'); @endphp
                @if (!empty($body_video_light) && file_exists(public_path($body_video_light)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($body_video_light) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="body_video_light">
                    <div class="mb-3">
                        <input type="file" name="body_video_light" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Body Video Light') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <h5 class="card-title mb-3">{{ get_phrase('Body video thumbnail') }}</h5>
            <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                <input type="hidden" name="type" value="body_video_thumbnail">
                <div class="form-group mb-2">
                    <div class="wrapper-image-preview  d-flex justify-content-center">
                        <div class="box">
                            <div class="upload-options">
                                <img src="{{ asset(get_frontend_settings('body_video_thumbnail')) }}" alt="" class="bg-dark radious-15px px-2 py-2">
                                <label for="body_video_thumbnail" class="btn ol-card p-4-text">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 6C17.39 6 16.83 5.65 16.55 5.11L15.83 3.66C15.37 2.75 14.17 2 13.15 2H10.86C9.83005 2 8.63005 2.75 8.17005 3.66L7.45005 5.11C7.17004 5.65 6.61005 6 6.00005 6C3.83005 6 2.11005 7.83 2.25005 9.99L2.77005 18.25C2.89005 20.31 4.00005 22 6.76005 22H17.24C20 22 21.1 20.31 21.23 18.25L21.75 9.99C21.89 7.83 20.17 6 18 6ZM10.5 7.25H13.5C13.91 7.25 14.25 7.59 14.25 8C14.25 8.41 13.91 8.75 13.5 8.75H10.5C10.09 8.75 9.75005 8.41 9.75005 8C9.75005 7.59 10.09 7.25 10.5 7.25ZM12 18.12C10.14 18.12 8.62005 16.61 8.62005 14.74C8.62005 12.87 10.13 11.36 12 11.36C13.87 11.36 15.38 12.87 15.38 14.74C15.38 16.61 13.86 18.12 12 18.12Z" fill="#797c8b" />
                                    </svg>
                                    <small>{{ get_phrase('Click here to choose a video thumbnail') }}</small>
                                    <small class="d-block">(330 X 70)</small> </label>
                                <input id="body_video_thumbnail" type="file" class="image-upload d-none" name="body_video_thumbnail" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn ol-btn-primary w-100">{{ get_phrase('Save changes') }}</button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <h4 class="title mt-4 mb-3">{{ get_phrase('Footer video') }}</h4>
    {{-- Footer Video --}}
    @php $footerVideoPath = get_frontend_settings('footer_video'); @endphp
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Footer Video') }}</h5>

                @if (!empty($footerVideoPath) && file_exists(public_path($footerVideoPath)))
                    <video width="100%" height="auto" controls class="mb-2 rounded">
                        <source src="{{ asset($footerVideoPath) }}" type="video/mp4">
                        {{ get_phrase('Your browser does not support the video tag.') }}
                    </video>
                @endif

                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="footer_video">
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Footer Video') }}</label>
                        <input type="file" name="footer_video" class="form-control" accept="video/*">
                    </div>
                    <button class="btn btn-primary w-100">{{ get_phrase('Upload Footer Video') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
