<h4 class="title mt-4 mb-3">{{ get_phrase('Ethics page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="ethics_page">
    @php
        $ethics_page = get_dynamic_pages_settings('ethics_page');
        $ethics_page = json_decode($ethics_page, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $ethics_page['title'] ?? '' }}">
    </div>
    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $ethics_page['subtitle'] ?? '' }}</textarea>
    </div>

    <!--  Condition -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Condition') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $ethics_page['condition'] ?? '' }}">
    </div>

    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $ethics_page['ethicalExcellence']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="ethicalExcellence_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="ethicalExcellence_title" id="ethicalExcellence_title"
                class="form-control ol-form-control"
                value="{{ $ethics_page['ethicalExcellence']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="ethicalExcellence_subtitle">{{ get_phrase('Subsubtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="ethicalExcellence_subtitle" id="ethicalExcellence_subtitle"
                class="form-control ol-form-control">{{ $ethics_page['ethicalExcellence']['subtitle'] ?? '' }}</textarea>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="ethicalExcellence_thumbnail">{{ get_phrase(' Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['ethicalExcellence']['thumbnail']))
                <img src="{{ asset($ethics_page['ethicalExcellence']['thumbnail']) }}"
                    alt="ethicalExcellence_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="ethicalExcellence_thumbnail" id="ethicalExcellence_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="ethicalExcellence_thumbnail_1">{{ get_phrase(' Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['ethicalExcellence']['thumbnail_1']))
                <img src="{{ asset($ethics_page['ethicalExcellence']['thumbnail_1']) }}"
                    alt="ethicalExcellence_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="ethicalExcellence_thumbnail_1" id="ethicalExcellence_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($ethics_page['ethicalExcellence']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="ethicalExcellence_area">
                        @php
                            $motivational_speeches =
                                count($ethics_page['ethicalExcellence']['features']) > 0
                                    ? $ethics_page['ethicalExcellence']['features']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $features)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="ethicalExcellence_features[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Features') }}">{{ $features }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="ethicalExcellence(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeethicalExcellence(this)">
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
        <div id="blank_ethicalExcellence" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="ethicalExcellence_features[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('features') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeethicalExcellence(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>


    </div>

    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $ethics_page['standProfessionalConduct']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="standProfessionalConduct_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="standProfessionalConduct_title" id="standProfessionalConduct_title" class="form-control ol-form-control"
                value="{{ $ethics_page['standProfessionalConduct']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="standProfessionalConduct_subtitle">{{ get_phrase('subTitle') }}<span
                    class="required">*</span></label>
            <input type="text" name="standProfessionalConduct_subtitle" id="standProfessionalConduct_subtitle"
                class="form-control ol-form-control"
                value="{{ $ethics_page['standProfessionalConduct']['subtitle'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="standProfessionalConduct_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['standProfessionalConduct']['thumbnail']))
                <img src="{{ asset($ethics_page['standProfessionalConduct']['thumbnail']) }}"
                    alt="standProfessionalConduct_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="standProfessionalConduct_thumbnail" id="standProfessionalConduct_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="standProfessionalConduct_thumbnail">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['standProfessionalConduct']['thumbnail_1']))
                <img src="{{ asset($ethics_page['standProfessionalConduct']['thumbnail_1']) }}"
                    alt="standProfessionalConduct_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="standProfessionalConduct_thumbnail_1" id="standProfessionalConduct_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($ethics_page['standProfessionalConduct']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="standProfessionalConduct_area">
                        @php
                            $motivational_speeches =
                                count($ethics_page['standProfessionalConduct']['features']) > 0
                                    ? $ethics_page['standProfessionalConduct']['features']
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
                                            onclick="standProfessionalConduct(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}" onclick="removestandProfessionalConduct(this)">
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
        <div id="blank_standProfessionalConduct" class="d-none">
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
                        onclick="removestandProfessionalConduct(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $ethics_page['honorPledge']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="honorPledge_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="honorPledge_title" id="honorPledge_title"
                class="form-control ol-form-control"
                value="{{ $ethics_page['honorPledge']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="honorPledge_subtitle">{{ get_phrase('Subsubtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="honorPledge_subtitle" id="honorPledge_subtitle"
                class="form-control ol-form-control">{{ $ethics_page['honorPledge']['subtitle'] ?? '' }}</textarea>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="honorPledge_thumbnail">{{ get_phrase(' Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['honorPledge']['thumbnail']))
                <img src="{{ asset($ethics_page['honorPledge']['thumbnail']) }}"
                    alt="honorPledge_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="honorPledge_thumbnail" id="honorPledge_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="honorPledge_thumbnail_1">{{ get_phrase(' Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['honorPledge']['thumbnail_1']))
                <img src="{{ asset($ethics_page['honorPledge']['thumbnail_1']) }}"
                    alt="honorPledge_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="honorPledge_thumbnail_1" id="honorPledge_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($ethics_page['honorPledge']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="honorPledge_area">
                        @php
                            $motivational_speeches =
                                count($ethics_page['honorPledge']['features']) > 0
                                    ? $ethics_page['honorPledge']['features']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $features)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="honorPledge_features[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Features') }}">{{ $features }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="honorPledge(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removehonorPledge(this)">
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
        <div id="blank_honorPledge" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="honorPledge_features[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('features') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removehonorPledge(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>


    </div>

    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $ethics_page['complaintsAction']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="complaintsAction_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="complaintsAction_title" id="complaintsAction_title"
                class="form-control ol-form-control"
                value="{{ $ethics_page['complaintsAction']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="complaintsAction_subtitle">{{ get_phrase('Sub Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="complaintsAction_subtitle" id="complaintsAction_subtitle"
                class="form-control ol-form-control"
                value="{{ $ethics_page['complaintsAction']['subtitle'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="complaintsAction_thumbnail">{{ get_phrase('complaintsAction Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['complaintsAction']['thumbnail']))
                <img src="{{ asset($ethics_page['complaintsAction']['thumbnail']) }}"
                    alt="complaintsAction_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="complaintsAction_thumbnail" id="complaintsAction_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="complaintsAction_thumbnail">{{ get_phrase('complaintsAction Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($ethics_page['complaintsAction']['thumbnail_1']))
                <img src="{{ asset($ethics_page['complaintsAction']['thumbnail_1']) }}"
                    alt="complaintsAction_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="complaintsAction_thumbnail_1" id="complaintsAction_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($ethics_page['complaintsAction']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="complaintsAction_area">
                        @php
                            $motivational_speeches =
                                count($ethics_page['complaintsAction']['features']) > 0
                                    ? $ethics_page['complaintsAction']['features']
                                    : [
                                        [
                                            'logos' => '',
                                            'titles' => '',
                                            'descriptions' => '',
                                            'l_backs_text' => '',
                                            'd_backs_text' => '',
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
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="descriptions[]"
                                            placeholder="{{ get_phrase('descriptions') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>
                                    {{-- Light Background Color --}}
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>

                                        @php
                                            $l_back = $motivational_speech['l_back'] ?? '#FFFFFF';
                                            $l_back_display = $l_back; // Full color with alpha
                                            $l_back_input = substr($l_back, 0, 7); // Only #RRGGBB
                                        @endphp

                                        <input type="color" class="form-control form-control-color"
                                            name="l_backs[]" value="{{ $l_back_input }}">
                                        <input type="text" class="form-control mt-1" name="l_backs_text[]"
                                            value="{{ $l_back_display }}">
                                    </div>

                                    {{-- Dark Background Color --}}
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>

                                        @php
                                            $d_back = $motivational_speech['d_back'] ?? '#FFFFFF';
                                            $d_back_display = $d_back;
                                            $d_back_input = substr($d_back, 0, 7);
                                        @endphp

                                        <input type="color" class="form-control form-control-color"
                                            name="d_backs[]" value="{{ $d_back_input }}">
                                        <input type="text" class="form-control mt-1" name="d_backs_text[]"
                                            value="{{ $d_back_display }}">
                                    </div>


                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Logo') }}</label>
                                        @if (!empty($motivational_speech['logo']))
                                            <img src="{{ asset($motivational_speech['logo']) }}" alt="logo"
                                                class="img-fluid mb-3"
                                                style="width: 50px; height: 50px; border: 1px solid black; color: black;">
                                        @endif
                                        <div class="custom-file">
                                            {{-- <input name="logo" type="hidden"
                                            value="{{ $motivational_speech['logo'] }}"> --}}
                                            <input type="file" class="form-control ol-form-control" name="logos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="complaintsAction(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removecomplaintsAction(this)">
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
        <div id="blank_complaintsAction" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="descriptions[]"
                            placeholder="{{ get_phrase('descriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="l_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="l_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="d_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="logos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removecomplaintsAction(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function attachColorEvents(container) {
            container.querySelectorAll('input[type="color"]').forEach(colorInput => {
                colorInput.addEventListener('input', function() {
                    // Get the next sibling input (the text input right after the color input)
                    let nextInput = colorInput.nextElementSibling;

                    // Skip non-inputs if any
                    while (nextInput && (nextInput.tagName !== 'INPUT' || nextInput.type !== 'text')) {
                        nextInput = nextInput.nextElementSibling;
                    }

                    if (nextInput) {
                        const existingAlpha = nextInput.value.slice(7) || '38';
                        nextInput.value = colorInput.value + existingAlpha;
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            attachColorEvents(document);
        });
    </script>

    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
