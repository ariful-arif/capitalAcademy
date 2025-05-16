<h4 class="title mt-4 mb-3">{{ get_phrase('Business Corporate page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="business_corporate_page">
    @php
        $business_corporate_page_setting = get_dynamic_pages_settings('business_corporate_page');
        $business_corporate_page_setting = json_decode($business_corporate_page_setting, true);
    @endphp

    <!-- Thumbnail Section -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">
            {{ get_phrase('Thumbnail') }}<span class="required">*</span>
        </label>

        <!-- Preview Images -->
        <div id="thumbnail-preview1" class="d-flex flex-wrap gap-3 mb-3">
            @if (!empty($business_corporate_page_setting['thumbnail']) && is_array($business_corporate_page_setting['thumbnail']))
                @foreach ($business_corporate_page_setting['thumbnail'] as $thumb)
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
        <input type="hidden" name="removed_thumbnails" id="removed_thumbnails1" value="[]">

        <!-- File input for new uploads -->
        <input type="file" name="thumbnail[]" id="thumbnail1" class="form-control ol-form-control" accept="image/*"
            multiple>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedThumbnails = [];
            const removedInput = document.getElementById('removed_thumbnails1');
            const previewContainer = document.getElementById('thumbnail-preview1');
            const fileInput = document.getElementById('thumbnail1');

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

    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $business_corporate_page_setting['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $business_corporate_page_setting['subtitle'] ?? '' }}</textarea>
    </div>

    <!-- Video Thumbnail -->
    <div class="fpb-7 mb-3 col">
        <label class="form-label ol-form-label" for="thumbnail_video">{{ get_phrase('Video thumbnail_video') }}</label>
        @if (!empty($business_corporate_page_setting['thumbnail_video']))
            {{-- <img src="{{ asset($business_corporate_page_setting['thumbnail_video']) }}" alt="thumbnail_video" class="img-fluid mb-3"
                style="width: 200px; height: 150px;"> --}}

            <video controls class="mb-2" style="width: 200px; height: 150px;">
                <source src="{{ asset($business_corporate_page_setting['thumbnail_video']) }}" type="video/mp4">
                {{ get_phrase('Your browser does not support the video tag.') }}
            </video>
        @endif
        <input type="file" name="thumbnail_video" id="thumbnail_video" class="form-control ol-form-control"
            accept="video/*">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_corporate_page_setting['corporateChoose']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="corporateChoose_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="corporateChoose_title" id="corporateChoose_title"
                class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['corporateChoose']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Subtitles :' }}</h4>
        @if (!empty($business_corporate_page_setting['corporateChoose']['subtitle']))
            <div class="row">
                <div class="col-md-8">
                    <div id="corporateChoose_area">
                        @php
                            $motivational_speeches =
                                count($business_corporate_page_setting['corporateChoose']['subtitle']) > 0
                                    ? $business_corporate_page_setting['corporateChoose']['subtitle']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $subtitle)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="corporateChoose_subtitle[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Subtitles') }}">{{ $subtitle }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="corporateChoose(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                                            onclick="removecorporateChoose(this)">
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
        <div id="blank_corporateChoose" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="corporateChoose_subtitle[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removecorporateChoose(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateChoose_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($business_corporate_page_setting['corporateChoose']['thumbnail']))
                <img src="{{ asset($business_corporate_page_setting['corporateChoose']['thumbnail']) }}"
                    alt="corporateChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="corporateChoose_thumbnail" id="corporateChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateChoose_thumbnail_1">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($business_corporate_page_setting['corporateChoose']['thumbnail_1']))
                <img src="{{ asset($business_corporate_page_setting['corporateChoose']['thumbnail_1']) }}"
                    alt="corporateChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="corporateChoose_thumbnail_1" id="corporateChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

    </div>
    <!-- Why Partner Section -->



    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_corporate_page_setting['increased']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_percentage" id="increased_percentage"
                class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['increased']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_title" id="increased_title" class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['increased']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="increased_subtitle" id="increased_subtitle" class="form-control ol-form-control">{{ $business_corporate_page_setting['increased']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_corporate_page_setting['improved']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_percentage" id="improved_percentage"
                class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['improved']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_title" id="improved_title" class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['improved']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="improved_subtitle" id="improved_subtitle" class="form-control ol-form-control">{{ $business_corporate_page_setting['improved']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_corporate_page_setting['fortuneCompany']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="fortuneCompany_title" id="fortuneCompany_title"
                class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['fortuneCompany']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required"> :</span></label>
            @if (!empty($business_corporate_page_setting['fortuneCompany']['thumbnail']))
                <img src="{{ asset($business_corporate_page_setting['fortuneCompany']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="fortuneCompany_thumbnail" id="fortuneCompany_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Company :' }}</h4>
        @if (!empty($business_corporate_page_setting['fortuneCompany']['company']))
            <div class="row">
                <div class="col-md-8">
                    <div id="company_area2">
                        @php
                            $companies =
                                count($business_corporate_page_setting['fortuneCompany']['company']) > 0
                                    ? $business_corporate_page_setting['fortuneCompany']['company']
                                    : [['company_thumbnails' => '']];
                        @endphp
                        @foreach ($companies as $key => $company)
                            <div class="company-item border-top pt-2">
                                <div class="d-flex mt-2">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Thumbnail') }}</label>
                                        @if (!empty($company['thumbnail']))
                                            <img src="{{ asset($company['thumbnail']) }}" alt="thumbnail"
                                                class="img-fluid mb-3" style="width: 50px; height: 50px;">
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="form-control ol-form-control"
                                                name="company_thumbnails[]" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        @if ($key == 0)
                                            <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                                name="button" data-bs-toggle="tooltip"
                                                title="{{ get_phrase('Add new') }}" onclick="appendcompany2(this)">
                                                <i class="fi-rr-plus-small"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                                name="button" data-bs-toggle="tooltip"
                                                title="{{ get_phrase('Remove') }}" onclick="removecompany2(this)">
                                                <i class="fi-rr-minus-small"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif
        <div id="blank_company2" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Thumbnail') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="company_thumbnails[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removecompany2(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_corporate_page_setting['corporateTraining']['title'] ?? '' }}</h4>

        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="corporateTraining_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="corporateTraining_title" id="corporateTraining_title"
                class="form-control ol-form-control"
                value="{{ $business_corporate_page_setting['corporateTraining']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateTraining_subtitle1">{{ get_phrase('subtitle 1 ') }}<span
                    class="required">:</span></label>
            <textarea type="text" name="corporateTraining_subtitle1" id="corporateTraining_subtitle1"
                class="form-control ol-form-control">{{ $business_corporate_page_setting['corporateTraining']['subtitle1'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateTraining_subtitle">{{ get_phrase('subtitle 2') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="corporateTraining_subtitle2" id="corporateTraining_subtitle"
                class="form-control ol-form-control">{{ $business_corporate_page_setting['corporateTraining']['subtitle2'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateTraining_subtitle">{{ get_phrase('subtitle 3') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="corporateTraining_subtitle3" id="corporateTraining_subtitle"
                class="form-control ol-form-control">{{ $business_corporate_page_setting['corporateTraining']['subtitle3'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="corporateTraining_subtitle">{{ get_phrase('subtitle 4') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="corporateTraining_subtitle4" id="corporateTraining_subtitle"
                class="form-control ol-form-control">{{ $business_corporate_page_setting['corporateTraining']['subtitle4'] ?? '' }}</textarea>
        </div>
    </div>
    <!-- Who Can Join Section -->



    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
