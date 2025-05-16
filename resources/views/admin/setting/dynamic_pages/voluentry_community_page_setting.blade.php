<h4 class="title mt-4 mb-3">{{ get_phrase('Voluentry Community Page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="voluentry_community_page">
    @php
        $voluentry_community_page_setting = get_dynamic_pages_settings('voluentry_community_page');
        $voluentry_community_page_setting = json_decode($voluentry_community_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $voluentry_community_page_setting['title'] ?? '' }}">
    </div>
    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $voluentry_community_page_setting['subtitle'] ?? '' }}</textarea>
    </div>

      <!-- Thumbnail Section -->
      <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">
            {{ get_phrase('Thumbnail') }}<span class="required">*</span>
        </label>

        <!-- Preview Images -->
        <div id="thumbnail-preview3" class="d-flex flex-wrap gap-3 mb-3">
            @if (!empty($voluentry_community_page_setting['thumbnail']) && is_array($voluentry_community_page_setting['thumbnail']))
                @foreach ($voluentry_community_page_setting['thumbnail'] as $thumb)
                    <div class="position-relative thumb-item" data-thumb-path="{{ $thumb }}">
                        <img src="{{ asset($thumb) }}" alt="Thumbnail" class="img-fluid border"
                            style="width: 200px; height: 150px; object-fit: cover;">
                        <button type="button"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-thumb-btn">&times;</button>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Hidden input for removed DB images -->
        <input type="hidden" name="removed_thumbnails" id="removed_thumbnails3" value="[]">

        <!-- File input for new uploads -->
        <input type="file" name="thumbnail[]" id="thumbnail3" class="form-control ol-form-control" accept="image/*"
            multiple>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedThumbnails = [];
            const removedInput = document.getElementById('removed_thumbnails3');
            const previewContainer = document.getElementById('thumbnail-preview3');
            const fileInput = document.getElementById('thumbnail3');

            // Remove button handler
            previewContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-thumb-btn')) {
                    const item = e.target.closest('.thumb-item');
                    const path = item.getAttribute('data-thumb-path');
                    if (path) {
                        removedThumbnails.push(path);
                        removedInput.value = JSON.stringify(removedThumbnails);
                    }
                    item.remove();
                }
            });

            // Show selected files instantly
            fileInput.addEventListener('change', function() {
                const files = Array.from(fileInput.files);

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid border';
                        img.style = 'width: 200px; height: 150px; object-fit: cover;';

                        const wrapper = document.createElement('div');
                        wrapper.className = 'position-relative thumb-item';
                        wrapper.appendChild(img);

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className =
                            'btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-thumb-btn';
                        btn.textContent = '×';

                        wrapper.appendChild(btn);
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $voluentry_community_page_setting['voluentry_impect']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="voluentry_impect_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="voluentry_impect_title" id="voluentry_impect_title"
                class="form-control ol-form-control"
                value="{{ $voluentry_community_page_setting['voluentry_impect']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="voluentry_impect_thumbnail">{{ get_phrase('voluentry_impect Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($voluentry_community_page_setting['voluentry_impect']['thumbnail']))
                <img src="{{ asset($voluentry_community_page_setting['voluentry_impect']['thumbnail']) }}"
                    alt="voluentry_impect_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="voluentry_impect_thumbnail" id="voluentry_impect_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="voluentry_impect_thumbnail">{{ get_phrase('voluentry_impect Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($voluentry_community_page_setting['voluentry_impect']['thumbnail_1']))
                <img src="{{ asset($voluentry_community_page_setting['voluentry_impect']['thumbnail_1']) }}"
                    alt="voluentry_impect_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="voluentry_impect_thumbnail_1" id="voluentry_impect_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($voluentry_community_page_setting['voluentry_impect']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="voluentry_impect_area">
                        @php
                            $motivational_speeches =
                                count($voluentry_community_page_setting['voluentry_impect']['features']) > 0
                                    ? $voluentry_community_page_setting['voluentry_impect']['features']
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
                                            title="{{ get_phrase('Add new') }}" onclick="voluentry_impect(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removevoluentry_impect(this)">
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
        <div id="blank_voluentry_impect" class="d-none">
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
                        onclick="removevoluentry_impect(this)">
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

    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
