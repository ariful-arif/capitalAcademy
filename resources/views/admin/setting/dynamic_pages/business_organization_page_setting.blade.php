<h4 class="title mt-4 mb-3">{{ get_phrase('Business Organization page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="business_organization_page">
    @php
        $business_organization_page_setting = get_dynamic_pages_settings('business_organization_page');
        $business_organization_page_setting = json_decode($business_organization_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $business_organization_page_setting['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $business_organization_page_setting['subtitle'] ?? '' }}</textarea>
    </div>
    <!--  Thumbnail -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Thumbnail') }}<span
                class="required">*</span></label>
        @if (!empty($business_organization_page_setting['thumbnail']))
            <img src="{{ asset($business_organization_page_setting['thumbnail']) }}" alt="Thumbnail"
                class="img-fluid mb-3" style="width: 200px; height: 150px;">
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="image/*">
    </div>

    <!-- Video Thumbnail -->
    <div class="fpb-7 mb-3 col">
        <label class="form-label ol-form-label" for="thumbnail_video">{{ get_phrase('Video thumbnail_video') }}</label>
        @if (!empty($business_organization_page_setting['thumbnail_video']))
            {{-- <img src="{{ asset($business_organization_page_setting['thumbnail_video']) }}" alt="thumbnail_video" class="img-fluid mb-3"
                style="width: 200px; height: 150px;"> --}}

            <video controls class="mb-2" style="width: 200px; height: 150px;">
                <source src="{{ asset($business_organization_page_setting['thumbnail_video']) }}" type="video/mp4">
                {{ get_phrase('Your browser does not support the video tag.') }}
            </video>
        @endif
        <input type="file" name="thumbnail_video" id="thumbnail_video" class="form-control ol-form-control"
            accept="video/*">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['learningSolution']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="learningSolution_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="learningSolution_title" id="learningSolution_title"
                class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['learningSolution']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Subtitles :' }}</h4>
        @if (!empty($business_organization_page_setting['learningSolution']['subtitle']))
            <div class="row">
                <div class="col-md-8">
                    <div id="learningSolution_area">
                        @php
                            $motivational_speeches =
                                count($business_organization_page_setting['learningSolution']['subtitle']) > 0
                                    ? $business_organization_page_setting['learningSolution']['subtitle']
                                    : [''];
                        @endphp
                        @foreach ($motivational_speeches as $key => $subtitle)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        {{-- <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label> --}}
                                        <textarea name="learningSolution_subtitle[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Subtitles') }}">{{ $subtitle }}</textarea>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="learningSolution(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                                            onclick="removelearningSolution(this)">
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
        <div id="blank_learningSolution" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <textarea name="learningSolution_subtitle[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Subtitle') }}"></textarea>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="removelearningSolution(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>

        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="learningSolution_thumbnail">{{ get_phrase('Professional Choose Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($business_organization_page_setting['learningSolution']['thumbnail']))
                <img src="{{ asset($business_organization_page_setting['learningSolution']['thumbnail']) }}"
                    alt="learningSolution_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="learningSolution_thumbnail" id="learningSolution_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="learningSolution_thumbnail_1">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($business_organization_page_setting['learningSolution']['thumbnail_1']))
                <img src="{{ asset($business_organization_page_setting['learningSolution']['thumbnail_1']) }}"
                    alt="learningSolution_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="learningSolution_thumbnail_1" id="learningSolution_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

    </div>
    <!-- Why Partner Section -->


    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['enterPrise']['title'] ?? '' }}</h4>

        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="enterPrise_title" id="enterPrise_title" class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['enterPrise']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle ') }}<span
                    class="required">:</span></label>
            <textarea type="text" name="enterPrise_subtitle" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle1">{{ get_phrase('subtitle 1 ') }}<span
                    class="required">:</span></label>
            <textarea type="text" name="enterPrise_subtitle1" id="enterPrise_subtitle1" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle1'] ?? '' }}</textarea>
        </div>
       
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle 2') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enterPrise_subtitle2" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle2'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle 2_1') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enterPrise_subtitle2_1" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle2_1'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle 2_2') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enterPrise_subtitle2_2" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle2_2'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle 3') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enterPrise_subtitle3" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle3'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="enterPrise_subtitle">{{ get_phrase('subtitle 4') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="enterPrise_subtitle4" id="enterPrise_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['enterPrise']['subtitle4'] ?? '' }}</textarea>
        </div>
    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['increased']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_percentage" id="increased_percentage"
                class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['increased']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_title" id="increased_title" class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['increased']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="increased_subtitle" id="increased_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['increased']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['improved']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_percentage" id="improved_percentage"
                class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['improved']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_title" id="improved_title" class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['improved']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="improved_subtitle" id="improved_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['improved']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['fortuneCompany']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="fortuneCompany_title" id="fortuneCompany_title"
                class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['fortuneCompany']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required"> :</span></label>
            @if (!empty($business_organization_page_setting['fortuneCompany']['thumbnail']))
                <img src="{{ asset($business_organization_page_setting['fortuneCompany']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="fortuneCompany_thumbnail" id="fortuneCompany_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Company :' }}</h4>
        @if (!empty($business_organization_page_setting['fortuneCompany']['company']))
            <div class="row">
                <div class="col-md-8">
                    <div id="company_area1">
                        @php
                            $companies =
                                count($business_organization_page_setting['fortuneCompany']['company']) > 0
                                    ? $business_organization_page_setting['fortuneCompany']['company']
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
                                                title="{{ get_phrase('Add new') }}" onclick="appendcompany1(this)">
                                                <i class="fi-rr-plus-small"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                                name="button" data-bs-toggle="tooltip"
                                                title="{{ get_phrase('Remove') }}" onclick="removecompany1(this)">
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
        <div id="blank_company1" class="d-none">
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
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removecompany1(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Who Can Join Section -->



    <!-- Submit Button -->
    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Settings') }}</button>
    </div>
</form>
