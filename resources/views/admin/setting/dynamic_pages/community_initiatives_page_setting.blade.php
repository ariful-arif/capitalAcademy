<h4 class="title mt-4 mb-3">{{ get_phrase('Community Initiatives Page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="community_initiatives_page">
    @php
        $community_initiatives_page_settings = get_dynamic_pages_settings('community_initiatives_page');
        $community_initiatives_page_settings = json_decode($community_initiatives_page_settings, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $community_initiatives_page_settings['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $community_initiatives_page_settings['subtitle'] ?? '' }}</textarea>
    </div>
    <!-- Thumbnail Section -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">
            {{ get_phrase('Thumbnail') }}<span class="required">*</span>
        </label>

        <!-- Preview Images -->
        <div id="thumbnail-preview" class="d-flex flex-wrap gap-3 mb-3">
            @if (
                !empty($community_initiatives_page_settings['thumbnail']) &&
                    is_array($community_initiatives_page_settings['thumbnail']))
                @foreach ($community_initiatives_page_settings['thumbnail'] as $thumb)
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
        <input type="hidden" name="removed_thumbnails" id="removed_thumbnails" value="[]">

        <!-- File input for new uploads -->
        <input type="file" name="thumbnail[]" id="thumbnail" class="form-control ol-form-control" accept="image/*"
            multiple>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedThumbnails = [];
            const removedInput = document.getElementById('removed_thumbnails');
            const previewContainer = document.getElementById('thumbnail-preview');
            const fileInput = document.getElementById('thumbnail');

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
        <h4 class="mb-3 border-bottom">{{ $community_initiatives_page_settings['grantProgram']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="grantProgram_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="grantProgram_title" id="grantProgram_title" class="form-control ol-form-control"
                value="{{ $community_initiatives_page_settings['grantProgram']['title'] ?? '' }}">
        </div><!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="grantProgram_subtitle">{{ get_phrase('Sub title') }}<span
                    class="required">*</span></label>
            <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $community_initiatives_page_settings['grantProgram']['subtitle'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="grantProgram_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required"> :</span></label>
            @if (!empty($community_initiatives_page_settings['grantProgram']['thumbnail']))
                <img src="{{ asset($community_initiatives_page_settings['grantProgram']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="grantProgram_thumbnail" id="grantProgram_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
        <!--  Thumbnail 1-->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="grantProgram_thumbnail_1">{{ get_phrase('Thumbnail 2') }}<span
                    class="required"> :</span></label>
            @if (!empty($community_initiatives_page_settings['grantProgram']['thumbnail_1']))
                <img src="{{ asset($community_initiatives_page_settings['grantProgram']['thumbnail_1']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="grantProgram_thumbnail_1" id="grantProgram_thumbnail_1"
                class="form-control ol-form-control" accept="image/*">
        </div>

    </div>
    <!-- Why Partner Section -->

    <!-- How It Works Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $community_initiatives_page_settings['programHighlights']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="how_it_works_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="how_it_works_title" id="how_it_works_title"
                class="form-control ol-form-control"
                value="{{ $community_initiatives_page_settings['programHighlights']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($community_initiatives_page_settings['programHighlights']['features']))

            <div class="row">
                <div class="col-md-8">
                    <div id="programHighlights_area">
                        @php
                            $motivational_speeches =
                                count($community_initiatives_page_settings['programHighlights']['features']) > 0
                                    ? $community_initiatives_page_settings['programHighlights']['features']
                                    : [['programHighlights_titles' => '', 'programHighlights_descriptions' => '']];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="programHighlights_titles[]" placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="programHighlights_descriptions[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>

                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="programHighlights(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="remove_programHighlights(this)">
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
        <div id="blank_programHighlights" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="programHighlights_titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="programHighlights_descriptions[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Description') }}"></textarea>
                    </div>

                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="remove_programHighlights(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- How to Earn Section -->

    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">
            {{ $community_initiatives_page_settings['educationalPartnerships']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="educationalPartnerships_title">{{ get_phrase('Title') }}<span class="required">*</span></label>
            <input type="text" name="educationalPartnerships_title" id="educationalPartnerships_title"
                class="form-control ol-form-control"
                value="{{ $community_initiatives_page_settings['educationalPartnerships']['title'] ?? '' }}">
        </div><!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="educationalPartnerships_subtitle">{{ get_phrase('Sub title') }}<span
                    class="required">*</span></label>
            <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $community_initiatives_page_settings['educationalPartnerships']['subtitle'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="educationalPartnerships_thumbnail">{{ get_phrase('Thumbnail') }}<span class="required">
                    :</span></label>
            @if (!empty($community_initiatives_page_settings['educationalPartnerships']['thumbnail']))
                <img src="{{ asset($community_initiatives_page_settings['educationalPartnerships']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="educationalPartnerships_thumbnail" id="educationalPartnerships_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
    </div>



    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $community_initiatives_page_settings['diversityEquity']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="diversityEquity_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="diversityEquity_title" id="diversityEquity_title"
                class="form-control ol-form-control"
                value="{{ $community_initiatives_page_settings['diversityEquity']['title'] ?? '' }}">
        </div><!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="diversityEquity_subtitle">{{ get_phrase('Sub title') }}<span
                    class="required">*</span></label>
            <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $community_initiatives_page_settings['diversityEquity']['subtitle'] ?? '' }}</textarea>
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="diversityEquity_thumbnail">{{ get_phrase('Thumbnail') }}<span class="required"> :</span></label>
            @if (!empty($community_initiatives_page_settings['diversityEquity']['thumbnail']))
                <img src="{{ asset($community_initiatives_page_settings['diversityEquity']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="diversityEquity_thumbnail" id="diversityEquity_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>


    </div>

    <!-- Affiliate Support Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $community_initiatives_page_settings['getInvolved']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="getInvolved_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="getInvolved_title" id="getInvolved_title"
                class="form-control ol-form-control"
                value="{{ $community_initiatives_page_settings['getInvolved']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="getInvolved_subtitle">{{ get_phrase('Sub title') }}<span
                    class="required">*</span></label>
            <textarea name="getInvolved_subtitle" id="getInvolved_subtitle" class="form-control ol-form-control" rows="3">{{ $community_initiatives_page_settings['getInvolved']['subtitle'] ?? '' }}</textarea>
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($community_initiatives_page_settings['getInvolved']['features']))

            <div class="row">
                <div class="col-md-8">
                    <div id="getInvolved_area">
                        @php
                            $motivational_speeches =
                                count($community_initiatives_page_settings['getInvolved']['features']) > 0
                                    ? $community_initiatives_page_settings['getInvolved']['features']
                                    : [['getInvolved_titles' => '', 'getInvolved_descriptions' => '']];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="getInvolved_titles[]" placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="getInvolved_descriptions[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>


                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="getInvolved(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}" onclick="remove_getInvolved(this)">
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
        <div id="blank_getInvolved" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="getInvolved_titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="getInvolved_descriptions[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Description') }}"></textarea>
                    </div>


                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="remove_getInvolved(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Affiliate Support Section -->


    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
