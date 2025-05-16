<h4 class="title mt-4 mb-3">{{ get_phrase('Full Code of Ethics page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="full_code_of_ethics_page">
    @php
        $full_code_of_ethics_page_setting = get_dynamic_pages_settings('full_code_of_ethics_page');
        $full_code_of_ethics_page_setting = json_decode($full_code_of_ethics_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $full_code_of_ethics_page_setting['title'] ?? '' }}">
    </div>
    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $full_code_of_ethics_page_setting['subtitle'] ?? '' }}</textarea>
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $full_code_of_ethics_page_setting['coreEthics']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="coreEthics_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="coreEthics_title" id="coreEthics_title" class="form-control ol-form-control"
                value="{{ $full_code_of_ethics_page_setting['coreEthics']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="coreEthics_subtitle">{{ get_phrase('subTitle') }}<span
                    class="required">*</span></label>
            <input type="text" name="coreEthics_subtitle" id="coreEthics_subtitle"
                class="form-control ol-form-control"
                value="{{ $full_code_of_ethics_page_setting['coreEthics']['subtitle'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="coreEthics_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['coreEthics']['thumbnail']))
                <img src="{{ asset($full_code_of_ethics_page_setting['coreEthics']['thumbnail']) }}"
                    alt="coreEthics_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="coreEthics_thumbnail" id="coreEthics_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="coreEthics_thumbnail">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['coreEthics']['thumbnail_1']))
                <img src="{{ asset($full_code_of_ethics_page_setting['coreEthics']['thumbnail_1']) }}"
                    alt="coreEthics_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="coreEthics_thumbnail_1" id="coreEthics_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($full_code_of_ethics_page_setting['coreEthics']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="coreEthics_area">
                        @php
                            $motivational_speeches =
                                count($full_code_of_ethics_page_setting['coreEthics']['features']) > 0
                                    ? $full_code_of_ethics_page_setting['coreEthics']['features']
                                    : [
                                        [
                                            'titles' => '',
                                            'Features' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="titles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Subtitles') }}</label>
                                        @if (!empty($motivational_speech['subtitle']) && is_array($motivational_speech['subtitle']))
                                            @foreach ($motivational_speech['subtitle'] as $sub_index => $sub)
                                                <textarea type="text" class="form-control ol-form-control mb-2" name="subtitles[{{ $key }}][]"
                                                    placeholder="{{ get_phrase('Subtitle') }}">{{ $sub }}</textarea>
                                            @endforeach
                                            {{-- <textarea type="text" class="form-control ol-form-control mb-2" name="subtitles[{{ $key }}][]"
                                                placeholder="{{ get_phrase('Subtitle') }}"></textarea> --}}
                                        @else
                                            <textarea type="text" class="form-control ol-form-control mb-2" name="subtitles[0][]"
                                                placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                                            <textarea type="text" class="form-control ol-form-control mb-2" name="subtitles[1][]"
                                                placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                                        @endif
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="coreEthics(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}" onclick="removecoreEthics(this)">
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
        <div id="blank_coreEthics" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Subtitles') }}</label>
                        <textarea class="form-control ol-form-control mb-2" name="subtitles[][]" placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                        <textarea class="form-control ol-form-control mb-2" name="subtitles[][]" placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removecoreEthics(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $full_code_of_ethics_page_setting['memberObligation']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="memberObligation_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="memberObligation_title" id="memberObligation_title"
                class="form-control ol-form-control"
                value="{{ $full_code_of_ethics_page_setting['memberObligation']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="memberObligation_subtitle">{{ get_phrase('Subsubtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="memberObligation_subtitle" id="memberObligation_subtitle"
                class="form-control ol-form-control">{{ $full_code_of_ethics_page_setting['memberObligation']['subtitle'] ?? '' }}</textarea>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="memberObligation_thumbnail">{{ get_phrase(' Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['memberObligation']['thumbnail']))
                <img src="{{ asset($full_code_of_ethics_page_setting['memberObligation']['thumbnail']) }}"
                    alt="memberObligation_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="memberObligation_thumbnail" id="memberObligation_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="memberObligation_thumbnail_1">{{ get_phrase(' Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['memberObligation']['thumbnail_1']))
                <img src="{{ asset($full_code_of_ethics_page_setting['memberObligation']['thumbnail_1']) }}"
                    alt="memberObligation_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="memberObligation_thumbnail_1" id="memberObligation_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($full_code_of_ethics_page_setting['memberObligation']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="memberObligation_area">
                        @php
                            $motivational_speeches =
                                count($full_code_of_ethics_page_setting['memberObligation']['features']) > 0
                                    ? $full_code_of_ethics_page_setting['memberObligation']['features']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $features)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="memberObligation_features[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Features') }}">{{ $features }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="memberObligation(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removememberObligation(this)">
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
        <div id="blank_memberObligation" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="memberObligation_features[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('features') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removememberObligation(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>


    </div>
    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $full_code_of_ethics_page_setting['enforcement']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enforcement_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="enforcement_title" id="enforcement_title"
                class="form-control ol-form-control"
                value="{{ $full_code_of_ethics_page_setting['enforcement']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_subtitle">{{ get_phrase('Subtitle 1') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enforcement_subtitle_1" id="enforcement_subtitle"
                class="form-control ol-form-control">{{ $full_code_of_ethics_page_setting['enforcement']['subtitle_1'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_subtitle">{{ get_phrase('Subtitle 2') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enforcement_subtitle_2" id="enforcement_subtitle"
                class="form-control ol-form-control">{{ $full_code_of_ethics_page_setting['enforcement']['subtitle_2'] ?? '' }}</textarea>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['enforcement']['thumbnail']))
                <img src="{{ asset($full_code_of_ethics_page_setting['enforcement']['thumbnail']) }}"
                    alt="enforcement_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="enforcement_thumbnail" id="enforcement_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_thumbnail_1">{{ get_phrase('Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($full_code_of_ethics_page_setting['enforcement']['thumbnail_1']))
                <img src="{{ asset($full_code_of_ethics_page_setting['enforcement']['thumbnail_1']) }}"
                    alt="enforcement_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="enforcement_thumbnail_1" id="enforcement_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($full_code_of_ethics_page_setting['enforcement']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="enforcement_area">
                        @php
                            $motivational_speeches =
                                count($full_code_of_ethics_page_setting['enforcement']['features']) > 0
                                    ? $full_code_of_ethics_page_setting['enforcement']['features']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $features)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="enforcement_features[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Features') }}">{{ $features }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="enforcement(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeenforcement(this)">
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
        <div id="blank_enforcement" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="enforcement_features[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('features') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeenforcement(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
