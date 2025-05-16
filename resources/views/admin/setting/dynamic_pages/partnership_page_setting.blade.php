<h4 class="title mt-4 mb-3">{{ get_phrase('Partnership Page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="partnership_page">
    @php
        $partnership_page_setting = get_dynamic_pages_settings('partnership_page');
        $partnership_page_setting = json_decode($partnership_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $partnership_page_setting['title'] ?? '' }}">
    </div>
    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $partnership_page_setting['subtitle'] ?? '' }}</textarea>
    </div>
    <!--  Thumbnail -->
    <div class="fpb-7 mb-3 col">
        <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Thumbnail') }}</label>
        @if (!empty($partnership_page_setting['thumbnail']))
            <img src="{{ asset($partnership_page_setting['thumbnail']) }}" alt="Thumbnail" class="img-fluid mb-3"
                style="width: 200px; height: 150px;">

            {{-- <video controls class="mb-2" style="width: 200px; height: 150px;">
                <source src="{{ asset($partnership_page_setting['thumbnail']) }}" type="video/mp4">
                {{ get_phrase('Your browser does not support the video tag.') }}
            </video> --}}
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="image/*">
    </div>
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="active_students">{{ get_phrase('Active Students') }}<span
                class="required">*</span></label>
        <input type="text" name="active_students" id="active_students" class="form-control ol-form-control"
            value="{{ $partnership_page_setting['active_students'] ?? '' }}">
    </div>
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="students_percentage">{{ get_phrase('Students Percentage') }}<span
                class="required">*</span></label>
        <input type="text" name="students_percentage" id="students_percentage" class="form-control ol-form-control"
            value="{{ $partnership_page_setting['students_percentage'] ?? '' }}">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $partnership_page_setting['professionalChoose']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionalChoose_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="professionalChoose_title" id="professionalChoose_title"
                class="form-control ol-form-control"
                value="{{ $partnership_page_setting['professionalChoose']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <h4 class="mb-3 border-bottom">{{ 'Subtitles :' }}</h4>
        @if (!empty($partnership_page_setting['professionalChoose']['subtitle']))
            <div class="row">
                <div class="col-md-8">
                    <div id="professionalChoose_area1">
                        @php
                            $motivational_speeches =
                                count($partnership_page_setting['professionalChoose']['subtitle']) > 0
                                    ? $partnership_page_setting['professionalChoose']['subtitle']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $subtitle)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="professionalChoose_subtitle[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Subtitles') }}">{{ $subtitle }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="professionalChoose12(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                                            onclick="removeprofessionalChoose1(this)">
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
        <div id="blank_professionalChoose1" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="professionalChoose_subtitle[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removeprofessionalChoose1(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="professionalChoose_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($partnership_page_setting['professionalChoose']['thumbnail']))
                <img src="{{ asset($partnership_page_setting['professionalChoose']['thumbnail']) }}"
                    alt="professionalChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="professionalChoose_thumbnail" id="professionalChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="professionalChoose_thumbnail">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($partnership_page_setting['professionalChoose']['thumbnail_1']))
                <img src="{{ asset($partnership_page_setting['professionalChoose']['thumbnail_1']) }}"
                    alt="professionalChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="professionalChoose_thumbnail_1" id="professionalChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
    </div>
    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $partnership_page_setting['partnershipOppor']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="partnershipOppor_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="partnershipOppor_title" id="partnershipOppor_title"
                class="form-control ol-form-control"
                value="{{ $partnership_page_setting['partnershipOppor']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="partnershipOppor_thumbnail">{{ get_phrase('partnershipOppor Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($partnership_page_setting['partnershipOppor']['thumbnail']))
                <img src="{{ asset($partnership_page_setting['partnershipOppor']['thumbnail']) }}"
                    alt="partnershipOppor_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="partnershipOppor_thumbnail" id="partnershipOppor_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="partnershipOppor_thumbnail">{{ get_phrase('partnershipOppor Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($partnership_page_setting['partnershipOppor']['thumbnail_1']))
                <img src="{{ asset($partnership_page_setting['partnershipOppor']['thumbnail_1']) }}"
                    alt="partnershipOppor_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="partnershipOppor_thumbnail_1" id="partnershipOppor_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($partnership_page_setting['partnershipOppor']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="partnershipOppor_area">
                        @php
                            $motivational_speeches =
                                count($partnership_page_setting['partnershipOppor']['features']) > 0
                                    ? $partnership_page_setting['partnershipOppor']['features']
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
                                            title="{{ get_phrase('Add new') }}" onclick="partnershipOppor(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removepartnershipOppor(this)">
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
        <div id="blank_partnershipOppor" class="d-none">
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
                        onclick="removepartnershipOppor(this)">
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


    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $partnership_page_setting['successStories']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="successStories_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="successStories_title" id="successStories_title"
                class="form-control ol-form-control"
                value="{{ $partnership_page_setting['successStories']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Subtitle') }}<span
                    class="required">*</span></label>
            <textarea name="successStories_subtitle" id="successStories_subtitle" class="form-control ol-form-control"
                rows="3">{{ $partnership_page_setting['successStories']['subtitle'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="successStories_thumbnail">{{ get_phrase('successStories Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($partnership_page_setting['successStories']['thumbnail']))
                <img src="{{ asset($partnership_page_setting['successStories']['thumbnail']) }}"
                    alt="successStories_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="successStories_thumbnail" id="successStories_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>


        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($partnership_page_setting['successStories']['stories']))

            <div class="row">
                <div class="col-md-8">
                    <div id="successStories_area">
                        @php
                            $motivational_speeches =
                                count($partnership_page_setting['successStories']['stories']) > 0
                                    ? $partnership_page_setting['successStories']['stories']
                                    : [
                                        [
                                            'storiesdescriptions' => '',
                                            'names' => '',
                                            'institutions' => '',
                                            'storiesthumbnails' => '',
                                        ],
                                    ];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Name') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="names[]"
                                            placeholder="{{ get_phrase('name') }}"
                                            value="{{ $motivational_speech['name'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('storiesDescription') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="storiesdescriptions[]"
                                            placeholder="{{ get_phrase('storiesdescriptions') }}"
                                            value="{{ $motivational_speech['description'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('institution') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="institutions[]" placeholder="{{ get_phrase('institution') }}"
                                            value="{{ $motivational_speech['institution'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('thumbnail') }}</label>
                                        @if (!empty($motivational_speech['thumbnail']))
                                            <img src="{{ asset($motivational_speech['thumbnail']) }}" alt="thumbnail"
                                                class="img-fluid mb-3"
                                                style="width: 50px; height: 50px; border: 1px solid black; color: black;">
                                        @endif
                                        <div class="custom-file">
                                            {{-- <input name="thumbnail" type="hidden"
                                            value="{{ $motivational_speech['thumbnail'] }}"> --}}
                                            <input type="file" class="form-control ol-form-control"
                                                name="storiesthumbnails[]" accept="image/*">
                                        </div>
                                    </div>

                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="successStories(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}" onclick="removesuccessStories(this)">
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
        <div id="blank_successStories" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Name') }}</label>
                        <input type="text" class="form-control ol-form-control" name="names[]"
                            placeholder="{{ get_phrase('name') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('storiesDescription') }}</label>
                        <input type="text" class="form-control ol-form-control" name="storiesdescriptions[]"
                            placeholder="{{ get_phrase('storiesdescriptions') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('institution') }}</label>
                        <input type="text" class="form-control ol-form-control" name="institutions[]"
                            placeholder="{{ get_phrase('institution') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Thumbnail') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="storiesthumbnails[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removesuccessStories(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Partner Section -->

    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
