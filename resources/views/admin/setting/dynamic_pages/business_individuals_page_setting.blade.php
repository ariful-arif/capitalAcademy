<h4 class="title mt-4 mb-3">{{ get_phrase('Business Individuals page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="business_individuals_page">
    @php
        $business_individuals_page_setting = get_dynamic_pages_settings('business_individuals_page');
        $business_individuals_page_setting = json_decode($business_individuals_page_setting, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $business_individuals_page_setting['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $business_individuals_page_setting['subtitle'] ?? '' }}</textarea>
    </div>
    <!--  Thumbnail -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Thumbnail') }}<span
                class="required">*</span></label>
        @if (!empty($business_individuals_page_setting['thumbnail']))
            <img src="{{ asset($business_individuals_page_setting['thumbnail']) }}" alt="Thumbnail"
                class="img-fluid mb-3" style="width: 200px; height: 150px;">
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="image/*">
    </div>
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="active_students">{{ get_phrase('active_students') }}<span
                class="required">*</span></label>
        <input type="number" name="active_students" id="active_students" class="form-control ol-form-control"
            value="{{ $business_individuals_page_setting['active_students'] ?? '' }}">
    </div>
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="students_percentage">{{ get_phrase('Students Percentage') }}<span
                class="required">*</span></label>
        <input type="text" name="students_percentage" id="students_percentage" class="form-control ol-form-control"
            value="{{ $business_individuals_page_setting['students_percentage'] ?? '' }}">
    </div>
    <!-- Video Thumbnail -->
    <div class="fpb-7 mb-3 col">
        <label class="form-label ol-form-label" for="thumbnail_video">{{ get_phrase('Video thumbnail_video') }}</label>
        @if (!empty($business_individuals_page_setting['thumbnail_video']))
            {{-- <img src="{{ asset($business_individuals_page_setting['thumbnail_video']) }}" alt="thumbnail_video" class="img-fluid mb-3"
                style="width: 200px; height: 150px;"> --}}

            <video controls class="mb-2" style="width: 200px; height: 150px;">
                <source src="{{ asset($business_individuals_page_setting['thumbnail_video']) }}" type="video/mp4">
                {{ get_phrase('Your browser does not support the video tag.') }}
            </video>
        @endif
        <input type="file" name="thumbnail_video" id="thumbnail_video" class="form-control ol-form-control"
            accept="video/*">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_individuals_page_setting['professionalChoose']['title'] ?? '' }}
        </h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionalChoose_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="professionalChoose_title" id="professionalChoose_title"
                class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['professionalChoose']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Subtitles :' }}</h4>
        @if (!empty($business_individuals_page_setting['professionalChoose']['subtitle']))
            <div class="row">
                <div class="col-md-8">
                    <div id="professionalChoose_area">
                        @php
                            $motivational_speeches =
                                count($business_individuals_page_setting['professionalChoose']['subtitle']) > 0
                                    ? $business_individuals_page_setting['professionalChoose']['subtitle']
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
                                            onclick="professionalChoose(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                                            onclick="removeprofessionalChoose(this)">
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
        <div id="blank_professionalChoose" class="d-none">
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
                        onclick="removeprofessionalChoose(this)">
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
            @if (!empty($business_individuals_page_setting['professionalChoose']['thumbnail']))
                <img src="{{ asset($business_individuals_page_setting['professionalChoose']['thumbnail']) }}"
                    alt="professionalChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="professionalChoose_thumbnail" id="professionalChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <!--  Thumbnail 1 -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="professionalChoose_thumbnail_1">{{ get_phrase('Professional Choose Thumbnail 1') }}<span
                    class="required">*</span></label>
            @if (!empty($business_individuals_page_setting['professionalChoose']['thumbnail_1']))
                <img src="{{ asset($business_individuals_page_setting['professionalChoose']['thumbnail_1']) }}"
                    alt="professionalChoose_Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="professionalChoose_thumbnail_1" id="professionalChoose_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

    </div>
    <!-- Why Partner Section -->
    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_organization_page_setting['professionals']['title'] ?? '' }}</h4>

        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="professionals_title" id="professionals_title" class="form-control ol-form-control"
                value="{{ $business_organization_page_setting['professionals']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_subtitle">{{ get_phrase('subtitle ') }}<span
                    class="required">:</span></label>
            <textarea type="text" name="professionals_subtitle" id="professionals_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['professionals']['subtitle'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_subtitle1">{{ get_phrase('subtitle 1 ') }}<span
                    class="required">:</span></label>
            <textarea type="text" name="professionals_subtitle1" id="professionals_subtitle1" class="form-control ol-form-control">{{ $business_organization_page_setting['professionals']['subtitle1'] ?? '' }}</textarea>
        </div>
       
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_subtitle">{{ get_phrase('subtitle 2') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="professionals_subtitle2" id="professionals_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['professionals']['subtitle2'] ?? '' }}</textarea>
        </div>
       
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_subtitle">{{ get_phrase('subtitle 3') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="professionals_subtitle3" id="professionals_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['professionals']['subtitle3'] ?? '' }}</textarea>
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="professionals_subtitle">{{ get_phrase('subtitle 4') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="professionals_subtitle4" id="professionals_subtitle" class="form-control ol-form-control">{{ $business_organization_page_setting['professionals']['subtitle4'] ?? '' }}</textarea>
        </div>
    </div>
    <!-- Why Partner Section -->


    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_individuals_page_setting['increased']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_percentage" id="increased_percentage"
                class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['increased']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="increased_title" id="increased_title" class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['increased']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="increased_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="increased_subtitle" id="increased_subtitle" class="form-control ol-form-control">{{ $business_individuals_page_setting['increased']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $business_individuals_page_setting['improved']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Prcentage') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_percentage" id="improved_percentage"
                class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['improved']['percentage'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="improved_title" id="improved_title" class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['improved']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="improved_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="improved_subtitle" id="improved_subtitle" class="form-control ol-form-control">{{ $business_individuals_page_setting['improved']['subtitle'] ?? '' }}</textarea>
        </div>

    </div>
    <!-- Who Can Join Section -->
    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $business_individuals_page_setting['fortuneCompany']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="fortuneCompany_title" id="fortuneCompany_title"
                class="form-control ol-form-control"
                value="{{ $business_individuals_page_setting['fortuneCompany']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="fortuneCompany_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required"> :</span></label>
            @if (!empty($business_individuals_page_setting['fortuneCompany']['thumbnail']))
                <img src="{{ asset($business_individuals_page_setting['fortuneCompany']['thumbnail']) }}"
                    alt="Thumbnail" class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="fortuneCompany_thumbnail" id="fortuneCompany_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>


        <h4 class="mb-3 border-bottom">{{ 'Company :' }}</h4>
        @if (!empty($business_individuals_page_setting['fortuneCompany']['company']))
            <div class="row">
                <div class="col-md-8">
                    <div id="company_area">
                        @php
                            $companies =
                                count($business_individuals_page_setting['fortuneCompany']['company']) > 0
                                    ? $business_individuals_page_setting['fortuneCompany']['company']
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
                                                title="{{ get_phrase('Add new') }}" onclick="appendcompany(this)">
                                                <i class="fi-rr-plus-small"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                                name="button" data-bs-toggle="tooltip"
                                                title="{{ get_phrase('Remove') }}" onclick="removecompany(this)">
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
        <div id="blank_company" class="d-none">
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
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removecompany(this)">
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
