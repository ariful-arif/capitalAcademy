<h4 class="title mt-4 mb-3">{{ get_phrase('Insights Career Page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="insights_career_page">
    @php
        $insights_career_page_setting = get_dynamic_pages_settings('insights_career_page');
        $insights_career_page_setting = json_decode($insights_career_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $insights_career_page_setting['title'] ?? '' }}">
    </div>
    <!-- Thumbnail Section -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">
            {{ get_phrase('Thumbnail') }}<span class="required">*</span>
        </label>

        <!-- Preview Images -->
        <div id="thumbnail-preview2" class="d-flex flex-wrap gap-3 mb-3">
            @if (!empty($insights_career_page_setting['thumbnail']) && is_array($insights_career_page_setting['thumbnail']))
                @foreach ($insights_career_page_setting['thumbnail'] as $thumb)
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
        <input type="hidden" name="removed_thumbnails" id="removed_thumbnails2" value="[]">

        <!-- File input for new uploads -->
        <input type="file" name="thumbnail[]" id="thumbnail2" class="form-control ol-form-control" accept="image/*"
            multiple>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedThumbnails = [];
            const removedInput = document.getElementById('removed_thumbnails2');
            const previewContainer = document.getElementById('thumbnail-preview2');
            const fileInput = document.getElementById('thumbnail2');

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
        <h4 class="mb-3 border-bottom">{{ $insights_career_page_setting['aboutCapital']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="aboutCapital_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="aboutCapital_title" id="aboutCapital_title" class="form-control ol-form-control"
                value="{{ $insights_career_page_setting['aboutCapital']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Subtitle') }}<span
                    class="required">*</span></label>
            <textarea name="aboutCapital_subtitle" id="aboutCapital_subtitle" class="form-control ol-form-control" rows="3">{{ $insights_career_page_setting['aboutCapital']['subtitle'] ?? '' }}</textarea>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="aboutCapital_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($insights_career_page_setting['aboutCapital']['thumbnail']))
                <img src="{{ asset($insights_career_page_setting['aboutCapital']['thumbnail']) }}"
                    alt="aboutCapital_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="aboutCapital_thumbnail" id="aboutCapital_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>
    </div>
    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $insights_career_page_setting['employeeBenefits']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="employeeBenefits_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="employeeBenefits_title" id="employeeBenefits_title"
                class="form-control ol-form-control"
                value="{{ $insights_career_page_setting['employeeBenefits']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($insights_career_page_setting['employeeBenefits']['features']))
            <div class="row">
                <div class="col-md-8">
                    <div id="employeeBenefits_area">
                        @php
                            $motivational_speeches =
                                count($insights_career_page_setting['employeeBenefits']['features']) > 0
                                    ? $insights_career_page_setting['employeeBenefits']['features']
                                    : [['logos' => '', 'titles' => '']];
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
                                            title="{{ get_phrase('Add new') }}" onclick="employeeBenefits(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeemployeeBenefits(this)">
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
        <div id="blank_employeeBenefits" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="titles[]"
                            placeholder="{{ get_phrase('Title') }}">
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
                        onclick="removeemployeeBenefits(this)">
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
        <h4 class="mb-3 border-bottom">{{ $insights_career_page_setting['careers']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="careers_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="careers_title" id="careers_title" class="form-control ol-form-control"
                value="{{ $insights_career_page_setting['careers']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Subtitle') }}<span
                    class="required">*</span></label>
            <textarea name="careers_subtitle" id="careers_subtitle" class="form-control ol-form-control" rows="3">{{ $insights_career_page_setting['careers']['subtitle'] ?? '' }}</textarea>
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($insights_career_page_setting['careers']['careers_goal']))

            <div class="row">
                <div class="col-md-8">
                    <div id="careers_area">
                        @php
                            $motivational_speeches =
                                count($insights_career_page_setting['careers']['careers_goal']) > 0
                                    ? $insights_career_page_setting['careers']['careers_goal']
                                    : [['descriptions' => '', 'names' => '', 'durations' => '', 'times' => '']];
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
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="descriptions[]" placeholder="{{ get_phrase('descriptions') }}"
                                            value="{{ $motivational_speech['description'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('duration') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="durations[]"
                                            placeholder="{{ get_phrase('duration') }}"
                                            value="{{ $motivational_speech['duration'] }}">
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('time') }}</label>
                                        <input type="text" class="form-control ol-form-control" name="times[]"
                                            placeholder="{{ get_phrase('time') }}"
                                            value="{{ $motivational_speech['time'] }}">
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="careers(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}" onclick="removecareers(this)">
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
        <div id="blank_careers" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Name') }}</label>
                        <input type="text" class="form-control ol-form-control" name="names[]"
                            placeholder="{{ get_phrase('name') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <input type="text" class="form-control ol-form-control" name="descriptions[]"
                            placeholder="{{ get_phrase('descriptions') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('duration') }}</label>
                        <input type="text" class="form-control ol-form-control" name="durations[]"
                            placeholder="{{ get_phrase('duration') }}">
                    </div>
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('time') }}</label>
                        <input type="text" class="form-control ol-form-control" name="times[]"
                            placeholder="{{ get_phrase('time') }}">
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removecareers(this)">
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
