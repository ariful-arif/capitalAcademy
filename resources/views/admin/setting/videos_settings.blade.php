<h4 class="title mt-4 mb-3">{{ get_phrase('Videos') }}</h4>
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




    {{-- Banner Video --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Banner Video') }}</h5>

                @php $bannerVideoPath = get_frontend_settings('banner_video'); @endphp
                @if (!empty($bannerVideoPath) && file_exists(public_path($bannerVideoPath)))
                    <video class="w-100 rounded mb-3" controls>
                        <source src="{{ asset($bannerVideoPath) }}" type="video/mp4">
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

    {{-- Home Page Body Video --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="video-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ get_phrase('Home Page Body Video') }}</h5>

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

</div>
