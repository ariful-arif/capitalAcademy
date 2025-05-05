<h4 class="title mt-4 mb-3">{{ get_phrase('Affiliate page settings') }}</h4>
<form action="{{ route('admin.dynamic_pages.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="affiliate_page">
    @php
        $affiliate_page_settings = get_dynamic_pages_settings('affiliate_program_page');
        $affiliate_page_settings = json_decode($affiliate_page_settings, true);
    @endphp
    <!--  Title -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control ol-form-control"
            value="{{ $affiliate_page_settings['title'] ?? '' }}">
    </div>

    <!--  Subtitle -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="sub_title">{{ get_phrase('Sub title') }}<span
                class="required">*</span></label>
        <textarea name="sub_title" id="sub_title" class="form-control ol-form-control" rows="3">{{ $affiliate_page_settings['subtitle'] ?? '' }}</textarea>
    </div>
    <!--  Thumbnail -->
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Thumbnail') }}<span
                class="required">*</span></label>
        @if (!empty($affiliate_page_settings['thumbnail']))
            <img src="{{ asset($affiliate_page_settings['thumbnail']) }}" alt="Thumbnail" class="img-fluid mb-3"
                style="width: 200px; height: 150px;">
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="image/*">
    </div>

    <!-- Why Partner Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $affiliate_page_settings['whyPartner']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="why_partner_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="why_partner_title" id="why_partner_title" class="form-control ol-form-control"
                value="{{ $affiliate_page_settings['whyPartner']['title'] ?? '' }}">
        </div>
        <!--  Thumbnail -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="why_partner_thumbnail">{{ get_phrase('Thumbnail') }}<span
                    class="required">*</span></label>
            @if (!empty($affiliate_page_settings['whyPartner']['thumbnail']))
                <img src="{{ asset($affiliate_page_settings['whyPartner']['thumbnail']) }}" alt="Thumbnail"
                    class="img-fluid mb-3" style="width: 200px; height: 150px;">
            @endif
            <input type="file" name="why_partner_thumbnail" id="why_partner_thumbnail"
                class="form-control ol-form-control" accept="image/*">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($affiliate_page_settings['whyPartner']['features']))

            <div class="row">
                <div class="col-md-8">
                    <div id="motivational_speech_area1">
                        @php
                            $motivational_speeches =
                                count($affiliate_page_settings['whyPartner']['features']) > 0
                                    ? $affiliate_page_settings['whyPartner']['features']
                                    : [['logos' => '', 'titles' => '', 'descriptions' => '']];
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
                                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Logo') }}</label>
                                        @if (!empty($motivational_speech['logo']))
                                            <img src="{{ asset($motivational_speech['logo']) }}" alt="logo"
                                                class="img-fluid mb-3" style="width: 50px; height: 50px;">
                                        @endif
                                        <div class="custom-file">
                                            <input name="logo" type="hidden"
                                                value="{{ $motivational_speech['logo'] }}">
                                            <input type="file" class="form-control ol-form-control" name="logos[]"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}"
                                            onclick="appendMotivational_speech1(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="removeMotivational_speech1(this)">
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
        <div id="blank_motivational_speech_field1" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}"></textarea>
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
                        onclick="removeMotivational_speech1(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Partner Section -->

    <!-- How It Works Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $affiliate_page_settings['howItWorks']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="how_it_works_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="how_it_works_title" id="how_it_works_title"
                class="form-control ol-form-control"
                value="{{ $affiliate_page_settings['howItWorks']['title'] ?? '' }}">
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($affiliate_page_settings['howItWorks']['features']))

            <div class="row">
                <div class="col-md-8">
                    <div id="how_it_works_area">
                        @php
                            $motivational_speeches =
                                count($affiliate_page_settings['howItWorks']['features']) > 0
                                    ? $affiliate_page_settings['howItWorks']['features']
                                    : [['how_it_works_titles' => '', 'how_it_works_descriptions' => '']];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="how_it_works_titles[]" placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="how_it_works_descriptions[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>

                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}" onclick="how_it_works_area(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="remove_how_it_works_area(this)">
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
        <div id="blank_how_it_works_area" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="how_it_works_titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="how_it_works_descriptions[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Description') }}"></textarea>
                    </div>

                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="remove_how_it_works_area(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- How to Earn Section -->

    <!-- Who Can Join Section -->
    <div class="fpb-7 mb-3">
        <h4 class="mb-3 border-bottom">{{ $affiliate_page_settings['whoCanJoin']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="who_can_join_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="who_can_join_title" id="who_can_join_title"
                class="form-control ol-form-control"
                value="{{ $affiliate_page_settings['whoCanJoin']['title'] ?? '' }}">
        </div>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="who_can_join_subtitle">{{ get_phrase('subtitle') }}<span
                    class="required">*</span></label>
            <textarea type="text" name="who_can_join_subtitle" id="who_can_join_subtitle"
                class="form-control ol-form-control">{{ $affiliate_page_settings['whoCanJoin']['subtitle'] ?? '' }}</textarea>
        </div>
    </div>
    <!-- Who Can Join Section -->

    <!-- Affiliate Support Section -->
    <div class="fpb-7 mb-3">
        <!--  Title -->
        <h4 class="mb-3 border-bottom">{{ $affiliate_page_settings['affiliateSupport']['title'] ?? '' }}</h4>
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label" for="affiliate_support_title">{{ get_phrase('Title') }}<span
                    class="required">*</span></label>
            <input type="text" name="affiliate_support_title" id="affiliate_support_title"
                class="form-control ol-form-control"
                value="{{ $affiliate_page_settings['affiliateSupport']['title'] ?? '' }}">
        </div>
        <!--  Subtitle -->
        <div class="fpb-7 mb-3">
            <label class="form-label ol-form-label"
                for="affiliate_support_subtitle">{{ get_phrase('Sub title') }}<span class="required">*</span></label>
            <textarea name="affiliate_support_subtitle" id="affiliate_support_subtitle" class="form-control ol-form-control"
                rows="3">{{ $affiliate_page_settings['affiliateSupport']['subtitle'] ?? '' }}</textarea>
        </div>

        <h4 class="mb-3 border-bottom">{{ 'Features :' }}</h4>
        @if (!empty($affiliate_page_settings['affiliateSupport']['features']))

            <div class="row">
                <div class="col-md-8">
                    <div id="affiliateSupport_area">
                        @php
                            $motivational_speeches =
                                count($affiliate_page_settings['affiliateSupport']['features']) > 0
                                    ? $affiliate_page_settings['affiliateSupport']['features']
                                    : [['support_logos' => '', 'support_titles' => '', 'support_descriptions' => '']];
                        @endphp
                        @foreach ($motivational_speeches as $key => $motivational_speech)
                            <div class="d-flex mt-2">
                                <div class="flex-grow-1 px-2 mb-3">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                        <input type="text" class="form-control ol-form-control"
                                            name="support_titles[]" placeholder="{{ get_phrase('Title') }}"
                                            value="{{ $motivational_speech['title'] }}">
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label
                                            class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                        <textarea name="support_descriptions[]" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Description') }}">{{ $motivational_speech['description'] }}</textarea>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label">{{ get_phrase('Logo') }}</label>
                                        @if (!empty($motivational_speech['logo']))
                                            <img src="{{ asset($motivational_speech['logo']) }}" alt="logo"
                                                class="img-fluid mb-3" style="width: 50px; height: 50px;">
                                        @endif
                                        <div class="custom-file">
                                            {{-- <input name="logo" type="hidden"
                                                value="{{ $motivational_speech['logo'] }}"> --}}
                                            <input type="file" class="form-control ol-form-control"
                                                name="support_logos" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                @if ($key == 0)
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Add new') }}"
                                            onclick="affiliateSupport_area(this)">
                                            <i class="fi-rr-plus-small"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-4">
                                        <button type="button" class="btn ol-btn-light ol-icon-btn mt-2"
                                            name="button" data-bs-toggle="tooltip"
                                            title="{{ get_phrase('Remove') }}"
                                            onclick="remove_affiliateSupport_area(this)">
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
        <div id="blank_affiliateSupport_area" class="d-none">
            <div class="d-flex mt-2 border-top pt-2">
                <div class="flex-grow-1 px-2 mb-3">
                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control ol-form-control" name="support_titles[]"
                            placeholder="{{ get_phrase('Title') }}">
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="support_descriptions[]" class="form-control ol-form-control"
                            placeholder="{{ get_phrase('Description') }}"></textarea>
                    </div>

                    <div class="fpb-7 mb-3">
                        <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="form-control ol-form-control" name="support_logos[]"
                                accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}"
                        onclick="remove_affiliateSupport_area(this)">
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
