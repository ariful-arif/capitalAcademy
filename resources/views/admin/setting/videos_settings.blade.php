<h4 class="title mt-4 mb-3">{{ get_phrase('Videos') }}</h4>
{{-- <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf

    <!-- Footer Video -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Footer Video') }}</label>
        <input type="file" class="form-control ol-form-control" name="footer_video">
    </div>

    <!-- Banner Video -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Banner Video') }}</label>
        <input type="file" class="form-control ol-form-control" name="banner_video">
    </div>

    <!-- Body Video -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Home Page Body Video') }}</label>
        <input type="file" class="form-control ol-form-control" name="home_page_body_video">
    </div>

    <button type="submit" class="btn btn-primary mt-3">{{ get_phrase('Save Videos') }}</button>
</form> --}}

@php $footerVideoPath = get_frontend_settings('footer_video'); @endphp
<form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="footer_video">

    @if (!empty($footerVideoPath) && file_exists(public_path($footerVideoPath)))
        <video width="20%%" height="20%" controls class="mb-2">
            <source src="{{ asset($footerVideoPath) }}" type="video/mp4">
            {{ get_phrase('Your browser does not support the video tag.') }}
        </video>
    @endif

    <div class="mb-3 mt-3">
        <label class="form-label ol-form-label">{{ get_phrase('Footer Video') }}</label>
        <input type="file" name="footer_video" class="form-control" accept="video/*">
    </div>
    <button class="btn btn-primary">{{ get_phrase('Upload Footer Video') }}</button>
</form>

<form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="banner_video">
    <div class="mb-3 mt-3">
        <label class="form-label ol-form-label">{{ get_phrase('Banner Video') }}</label>
        <input type="file" name="banner_video" class="form-control" accept="video/*">
    </div>
    <button class="btn btn-primary">{{ get_phrase('Upload Banner Video') }}</button>
</form>
<form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="home_page_body_video">
    <div class="mb-3 mt-3">
        <label class="form-label ol-form-label">{{ get_phrase('Home Page Body Video') }}</label>
        <input type="file" name="home_page_body_video" class="form-control" accept="video/*">
    </div>
    <button class="btn btn-primary">{{ get_phrase('Upload Body Video') }}</button>
</form>


