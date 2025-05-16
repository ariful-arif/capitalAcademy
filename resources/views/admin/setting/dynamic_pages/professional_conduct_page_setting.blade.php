<h4 class="title mt-4 mb-3">{{ get_phrase('Professional Conduct Page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="professional_conduct_page">
    @php
        $professional_conduct_page_setting = get_dynamic_pages_settings('professional_conduct_page');
        $professional_conduct_page_setting = json_decode($professional_conduct_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $professional_conduct_page_setting['title'] ?? '' }}">
    </div>
    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $professional_conduct_page_setting['subtitle'] ?? '' }}</textarea>
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['reliability']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="reliability_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="reliability_title" id="reliability_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['reliability']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="reliability_thumbnail">{{ get_phrase('reliability Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['reliability']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['reliability']['thumbnail']) }}"
                    alt="reliability_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="reliability_thumbnail" id="reliability_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>



        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['reliability']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="reliability_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['reliability']['features']) > 0
                                    ? $professional_conduct_page_setting['reliability']['features']
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
                                            title="{{ get_phrase('Add new') }}" onclick="reliability(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removereliability(this)">
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
        <div id="blank_reliability" class="d-none">
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
                        onclick="removereliability(this)">
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
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['professional']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professional_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="professional_title" id="professional_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['professional']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="professional_thumbnail">{{ get_phrase('professional Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['professional']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['professional']['thumbnail']) }}"
                    alt="professional_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="professional_thumbnail" id="professional_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>



        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['professional']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="professional_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['professional']['features']) > 0
                                    ? $professional_conduct_page_setting['professional']['features']
                                    : [
                                        [
                                            'professionallogos' => '',
                                            'professionaltitles' => '',
                                            'professionaldescriptions' => '',
                                            'professionall_backs_text' => '',
                                            'professionald_backs_text' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="professionaltitles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="professionaldescriptions[]"
                                            placeholder="{{ get_phrase('professionaldescriptions') }}">{{ $motivational_speech['description'] }}</textarea>
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
                                        <input type="text" class="form-control mt-1" name="professionall_backs_text[]"
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
                                        <input type="text" class="form-control mt-1" name="professionald_backs_text[]"
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
                                            <input type="file" class="form-control ol-form-control" name="professionallogos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="professional(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeprofessional(this)">
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
        <div id="blank_professional" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="professionaltitles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="professionaldescriptions[]"
                            placeholder="{{ get_phrase('professionaldescriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="professionall_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="professionall_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="professionald_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="professionallogos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeprofessional(this)">
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
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['obligation']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="obligation_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="obligation_title" id="obligation_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['obligation']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="obligation_thumbnail">{{ get_phrase('obligation Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['obligation']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['obligation']['thumbnail']) }}"
                    alt="obligation_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="obligation_thumbnail" id="obligation_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>



        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['obligation']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="obligation_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['obligation']['features']) > 0
                                    ? $professional_conduct_page_setting['obligation']['features']
                                    : [
                                        [
                                            'obligationlogos' => '',
                                            'obligationtitles' => '',
                                            'obligationdescriptions' => '',
                                            'obligationl_backs_text' => '',
                                            'obligationd_backs_text' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="obligationtitles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="obligationdescriptions[]"
                                            placeholder="{{ get_phrase('obligationdescriptions') }}">{{ $motivational_speech['description'] }}</textarea>
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
                                        <input type="text" class="form-control mt-1" name="obligationl_backs_text[]"
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
                                        <input type="text" class="form-control mt-1" name="obligationd_backs_text[]"
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
                                            <input type="file" class="form-control ol-form-control" name="obligationlogos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="obligation(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeobligation(this)">
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
        <div id="blank_obligation" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="obligationtitles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="obligationdescriptions[]"
                            placeholder="{{ get_phrase('obligationdescriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="obligationl_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="obligationl_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="obligationd_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="obligationlogos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeobligation(this)">
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
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['obligationToEmployee']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="obligationToEmployee_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="obligationToEmployee_title" id="obligationToEmployee_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['obligationToEmployee']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="obligationToEmployee_thumbnail">{{ get_phrase('obligationToEmployee Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['obligationToEmployee']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['obligationToEmployee']['thumbnail']) }}"
                    alt="obligationToEmployee_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="obligationToEmployee_thumbnail" id="obligationToEmployee_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>



        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['obligationToEmployee']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="obligationToEmployee_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['obligationToEmployee']['features']) > 0
                                    ? $professional_conduct_page_setting['obligationToEmployee']['features']
                                    : [
                                        [
                                            'obligationToEmployeelogos' => '',
                                            'obligationToEmployeetitles' => '',
                                            'obligationToEmployeedescriptions' => '',
                                            'obligationToEmployeel_backs_text' => '',
                                            'obligationToEmployeed_backs_text' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="obligationToEmployeetitles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="obligationToEmployeedescriptions[]"
                                            placeholder="{{ get_phrase('obligationToEmployeedescriptions') }}">{{ $motivational_speech['description'] }}</textarea>
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
                                        <input type="text" class="form-control mt-1" name="obligationToEmployeel_backs_text[]"
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
                                        <input type="text" class="form-control mt-1" name="obligationToEmployeed_backs_text[]"
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
                                            <input type="file" class="form-control ol-form-control" name="obligationToEmployeelogos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="obligationToEmployee(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeobligationToEmployee(this)">
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
        <div id="blank_obligationToEmployee" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="obligationToEmployeetitles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="obligationToEmployeedescriptions[]"
                            placeholder="{{ get_phrase('obligationToEmployeedescriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="obligationToEmployeel_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="obligationToEmployeel_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="obligationToEmployeed_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="obligationToEmployeelogos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeobligationToEmployee(this)">
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
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['investment']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="investment_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="investment_title" id="investment_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['investment']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="investment_thumbnail">{{ get_phrase('investment Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['investment']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['investment']['thumbnail']) }}"
                    alt="investment_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="investment_thumbnail" id="investment_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>



        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['investment']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="investment_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['investment']['features']) > 0
                                    ? $professional_conduct_page_setting['investment']['features']
                                    : [
                                        [
                                            'investmentlogos' => '',
                                            'investmenttitles' => '',
                                            'investmentdescriptions' => '',
                                            'investmentl_backs_text' => '',
                                            'investmentd_backs_text' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="investmenttitles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="investmentdescriptions[]"
                                            placeholder="{{ get_phrase('investmentdescriptions') }}">{{ $motivational_speech['description'] }}</textarea>
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
                                        <input type="text" class="form-control mt-1" name="investmentl_backs_text[]"
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
                                        <input type="text" class="form-control mt-1" name="investmentd_backs_text[]"
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
                                            <input type="file" class="form-control ol-form-control" name="investmentlogos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="investment(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeinvestment(this)">
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
        <div id="blank_investment" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="investmenttitles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="investmentdescriptions[]"
                            placeholder="{{ get_phrase('investmentdescriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="investmentl_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="investmentl_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="investmentd_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="investmentlogos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeinvestment(this)">
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
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['interest']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="interest_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="interest_title" id="interest_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['interest']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="interest_thumbnail">{{ get_phrase('interest Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['interest']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['interest']['thumbnail']) }}"
                    alt="interest_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="interest_thumbnail" id="interest_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>


        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($professional_conduct_page_setting['interest']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="interest_area">
                        @php
                            $motivational_speeches =
                                count($professional_conduct_page_setting['interest']['features']) > 0
                                    ? $professional_conduct_page_setting['interest']['features']
                                    : [
                                        [
                                            'interestlogos' => '',
                                            'interesttitles' => '',
                                            'interestdescriptions' => '',
                                            'interestl_backs_text' => '',
                                            'interestd_backs_text' => '',
                                        ],
                                    ];
                            // Default values for the first entry
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="interesttitles[]"
                                            placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea type="text" class="form-control ol-form-control" name="interestdescriptions[]"
                                            placeholder="{{ get_phrase('interestdescriptions') }}">{{ $motivational_speech['description'] }}</textarea>
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
                                        <input type="text" class="form-control mt-1" name="interestl_backs_text[]"
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
                                        <input type="text" class="form-control mt-1" name="interestd_backs_text[]"
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
                                            <input type="file" class="form-control ol-form-control" name="interestlogos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="interest(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeinterest(this)">
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
        <div id="blank_interest" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="interesttitles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea type="text" class="form-control ol-form-control" name="interestdescriptions[]"
                            placeholder="{{ get_phrase('interestdescriptions') }}"></textarea>
                    </div>
                    {{-- Light Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Light Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="l_backs[]"
                            value="#ffffff">
                        {{-- <input type="text" class="form-control mt-1" name="interestl_backs_text[]"> --}}
                        <input type="text" class="form-control mt-1" name="interestl_backs_text[]" value="#ffffff38"
                            data-alpha="38">

                    </div>

                    {{-- Dark Background Color --}}
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Dark Background Color') }}</label>
                        <input type="color" class="form-control form-control-color" name="d_backs[]"
                            value="#ffffff">
                        <input type="text" class="form-control mt-1" name="interestd_backs_text[]" value="#ffffff38">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="interestlogos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeinterest(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Partner Section -->

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

    <!-- Why Partner Section -->

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $professional_conduct_page_setting['enforcement']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enforcement_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="enforcement_title" id="enforcement_title"
                class="form-control ol-form-control"
                value="{{ $professional_conduct_page_setting['enforcement']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Subtitle') }}<span
                    class="required">*</span></label>
            <textarea name="enforcement_subtitle" id="enforcement_subtitle" class="form-control ol-form-control"
                rows="3">{{ $professional_conduct_page_setting['enforcement']['subtitle'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_thumbnail">{{ get_phrase('enforcement Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['enforcement']['thumbnail']))
                <img src="{{ asset($professional_conduct_page_setting['enforcement']['thumbnail']) }}"
                    alt="enforcement_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="enforcement_thumbnail" id="enforcement_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="enforcement_thumbnail">{{ get_phrase('enforcement Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($professional_conduct_page_setting['enforcement']['thumbnail_1']))
                <img src="{{ asset($professional_conduct_page_setting['enforcement']['thumbnail_1']) }}"
                    alt="enforcement_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="enforcement_thumbnail_1" id="enforcement_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

    </div>
    <!-- Why Partner Section -->

    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
