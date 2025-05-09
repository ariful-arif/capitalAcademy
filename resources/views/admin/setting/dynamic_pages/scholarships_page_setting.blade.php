<h4 class="title mt-4 mb-3">{{ get_phrase('Scholarships page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="scholarships_page">
    @php
        $scholarships_page_settings = get_dynamic_pages_settings('scholarships_page');
        $scholarships_page_settings = json_decode($scholarships_page_settings, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $scholarships_page_settings['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $scholarships_page_settings['subtitle'] ?? '' }}</textarea>
    </div>
    <!--  Thumbnail -->
    <div class="fpb-7 mb-3 col">
        <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Video Thumbnail') }}</label>
        @if (!empty($scholarships_page_settings['thumbnail']))
            {{-- <img src="{{ asset($scholarships_page_settings['thumbnail']) }}" alt="Thumbnail" class="img-fluid mb-3"
                style="width: 200px; height: 150px;"> --}}

            <video controls class="mb-2" style="width: 200px; height: 150px;">
                <source src="{{ asset($scholarships_page_settings['thumbnail']) }}" type="video/mp4">
                {{ get_phrase('Your browser does not support the video tag.') }}
            </video>
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="video/*">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $scholarships_page_settings['howItWorks']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="how_it_works_descriptions">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="how_it_works_descriptions" id="how_it_works_descriptions" class="form-control ol-form-control"
                value="{{ $scholarships_page_settings['howItWorks']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($scholarships_page_settings['howItWorks']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="howItWorks_area">
                        @php
                            $motivational_speeches =
                                count($scholarships_page_settings['howItWorks']['features']) > 0
                                    ? $scholarships_page_settings['howItWorks']['features']
                                    : [['how_it_works_descriptions' => '']];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="how_it_works_descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="howItWorks(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                                            onclick="removehowItWorks(this)">
                                            <i class="fi-rr-minus-small"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div id="blank_howItWorks" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="how_it_works_descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}"></textarea>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removehowItWorks(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Partner Section -->

    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $scholarships_page_settings['apply']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="apply_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="apply_title" id="apply_title" class="form-control ol-form-control"
                value="{{ $scholarships_page_settings['apply']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="apply_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="apply_subtitle" id="apply_subtitle" class="form-control ol-form-control">{{ $scholarships_page_settings['apply']['subtitle'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="apply_note">{{ get_phrase('Note') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="apply_note" id="apply_note" class="form-control ol-form-control">{{ $scholarships_page_settings['apply']['note'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="apply_thumbnail">{{ get_phrase('Apply Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($scholarships_page_settings['apply']['thumbnail']))
                <img src="{{ asset($scholarships_page_settings['apply']['thumbnail']) }}" alt="apply_Thumbnail"
                    class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="apply_thumbnail" id="apply_thumbnail" class="form-control ol-form-control"
                accept="image/*">
        </div>
    </div>
    <!-- Who Can Join Section -->



    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
