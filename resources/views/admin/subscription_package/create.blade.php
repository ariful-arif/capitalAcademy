@extends('layouts.admin')
@push('title', get_phrase('Create Subscription Package'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Add new subscription Package') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('admin.subscription_package.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pb-2">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="package_name">{{ get_phrase('Subscription Package Name') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name = "package_name" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter subscription package name') }}" required>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="short_description">{{ get_phrase('Short Description') }}</label>
                                        <textarea name="short_description" placeholder="{{ get_phrase('Enter Short Description') }}"
                                            class="form-control ol-form-control" rows="5"></textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label for="subscription_type"
                                            class="form-label ol-form-label">{{ get_phrase('Subscription Type') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="ol-select2" name="subscription_type" id="subscription_type" required>
                                            <option value="" selected disabled>{{ get_phrase('Select a type') }}
                                            </option>
                                            {{-- @foreach (App\Models\Category::where('parent_id', 0)->orderBy('title', 'desc')->get() as $category) --}}
                                            <option value="individual"> {{ 'Individual Subscription' }}</option>
                                            <option value="team"> {{ 'Team Subscription' }}</option>

                                            {{-- @foreach ($category->childs as $sub_category)
                                                    <option value="{{ $sub_category->id }}"> --
                                                        {{ $sub_category->title }}
                                                    </option>
                                                @endforeach
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label for="package_type"
                                            class="form-label ol-form-label">{{ get_phrase('Package Type') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="ol-select2" name="package_type" id="package_type" required>
                                            <option value="" selected disabled>{{ get_phrase('Select a type') }}
                                            </option>
                                            {{-- @foreach (App\Models\Category::where('parent_id', 0)->orderBy('title', 'desc')->get() as $category) --}}
                                            <option value="Monthly"> {{ 'Monthly' }}</option>
                                            <option value="Yearly"> {{ 'Yearly' }}</option>

                                            {{-- @foreach ($category->childs as $sub_category)
                                                    <option value="{{ $sub_category->id }}"> --
                                                        {{ $sub_category->title }}
                                                    </option>
                                                @endforeach
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label for="package_duration"
                                            class="form-label ol-form-label">{{ get_phrase('Package Duration') }}</label>
                                        <input type="number" name="package_duration" class="form-control ol-form-control"
                                            id="package_duration" min="1" step=""
                                            placeholder="{{ get_phrase('Enter your package duration') }}">
                                        <small>{{ 'Monthly * 3 or Yearly * 1' }}</small>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-2 ">
                                        <label for="course_status"
                                            class="col-sm-2 col-form-label">{{ get_phrase('Create as') }}
                                            <span class="text-danger ms-1">*</span></label>
                                        <div class="eRadios">
                                            <div class="form-check">
                                                <input type="radio" value="active" name="status"
                                                    class="form-check-input eRadioSuccess" id="status_active" required
                                                    checked>
                                                <label for="status_active"
                                                    class="form-check-label">{{ get_phrase('Active') }}</label>
                                            </div>

                                            <div class="form-check">
                                                <input type="radio" value="inactive" name="status"
                                                    class="form-check-input eRadioDark" id="status_inactive" required>
                                                <label for="status_inactive"
                                                    class="form-check-label">{{ get_phrase('Inactive') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        {{-- <label
                                            class="form-label ol-form-label col-sm-2 col-form-label">{{ get_phrase('Pricing type') }}<span
                                                class="text-danger ms-1">*</span></label> --}}

                                        <div class="eRadios">
                                            {{-- <div class="form-check">
                                                <input type="radio" name="is_paid" value="1"
                                                    class="form-check-input eRadioSuccess" id="paid"
                                                    onchange="$('#paid-section').slideDown(200)" checked>
                                                <label for="paid"
                                                    class="form-check-label">{{ get_phrase('Paid') }}</label>
                                            </div>

                                            <div class="form-check">
                                                <input type="radio" name="is_paid" value="0"
                                                    class="form-check-input eRadioSuccess" id="free"
                                                    onchange="$('#paid-section').slideUp(200)">
                                                <label for="free"
                                                    class="form-check-label">{{ get_phrase('Free') }}</label>
                                            </div> --}}
                                            <div class="paid-section" id="paid-section">
                                                <div class="fpb-7 mb-3">
                                                    <label for="price"
                                                        class="form-label ol-form-label">{{ get_phrase('Price') }}
                                                        <small>({{ currency() }})</small><span
                                                            class="text-danger ms-1">*</span></label>

                                                    <input type="number" name="price"
                                                        class="form-control ol-form-control" id="price" min="1"
                                                        step=".01"
                                                        placeholder="{{ get_phrase('Enter your course price') }} ({{ currency() }})">
                                                </div>

                                                <div class="fpb-7 mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="discount_flag" value="1"
                                                            class="form-check-input eRadioSuccess" id="discount_flag">
                                                        <label for="discount_flag"
                                                            class="form-check-label">{{ get_phrase('Check if this course has discount') }}</label>
                                                    </div>
                                                </div>

                                                <div class="fpb-7 mb-3">
                                                    <label for="discounted_price"
                                                        class="form-label ol-form-label">{{ get_phrase('Discounted price') }}</label>

                                                    <input type="number" name="discounted_price"
                                                        class="form-control ol-form-control" id="discounted_price"
                                                        min="1" step=".01"
                                                        placeholder="{{ get_phrase('Enter your discount price') }} ({{ currency() }})">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="fpb-7 mb-3">
                                    <label for="banner"
                                        class="form-label ol-form-label">{{ get_phrase('Banner') }}</label>
                                    <input type="file" name="banner" class="form-control ol-form-control"
                                        id="banner" accept="image/*" />
                                </div>
                                <div class="fpb-7">
                                    <div class="">
                                        <label for="info"
                                            class="form-label ol-form-label">{{ get_phrase('Info') }}</label>
                                        <div class="">
                                            <div id = "faq_area">

                                                <div class="d-flex mt-2">
                                                    <div class="flex-grow-1 pe-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control ol-form-control"
                                                                name="info[]" id="faqs"
                                                                placeholder="{{ get_phrase('Add Info') }}">
                                                            {{-- <textarea name="faq_description[]" rows="2" class="form-control ol-form-control mt-2" placeholder="{{get_phrase('Answer')}}"></textarea> --}}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <button type="button" class="btn ol-btn-light ol-icon-btn"
                                                            name="button" data-bs-toggle="tooltip"
                                                            title="{{ get_phrase('Add new') }}" onclick="appendFaq()"> <i
                                                                class="fi-rr-plus-small"></i> </button>
                                                    </div>
                                                </div>
                                                {{-- @endif --}}
                                                <div id = "blank_faq_field">
                                                    <div class="d-flex mt-2">
                                                        <div class="flex-grow-1 pe-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control ol-form-control"
                                                                    name="info[]"
                                                                    placeholder="{{ get_phrase('Add Info') }}">
                                                                {{-- <textarea name="faq_description[]" rows="2" class="form-control ol-form-control mt-2" placeholder="{{get_phrase('Answer')}}"></textarea> --}}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <button type="button"
                                                                class="btn ol-btn-light ol-icon-btn mt-0" name="button"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ get_phrase('Remove') }}"
                                                                onclick="removeFaq(this)"> <i
                                                                    class="fi-rr-minus-small"></i> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2">
                                <button type="submit"
                                    class="btn ol-btn-primary float-end">{{ get_phrase('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        "use strict";

        var blank_faq = jQuery('#blank_faq_field').html();
        // var blank_outcome = jQuery('#blank_outcome_field').html();
        // var blank_requirement = jQuery('#blank_requirement_field').html();
        jQuery(document).ready(function() {
            jQuery('#blank_faq_field').hide();
            //   jQuery('#blank_outcome_field').hide();
            //   jQuery('#blank_requirement_field').hide();
        });

        function appendFaq() {
            jQuery('#faq_area').append(blank_faq);
        }

        function removeFaq(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }

        // function appendOutcome() {
        //   jQuery('#outcomes_area').append(blank_outcome);
        // }
        // function removeOutcome(outcomeElem) {
        //   jQuery(outcomeElem).parent().parent().remove();
        // }

        // function appendRequirement() {
        //   jQuery('#requirement_area').append(blank_requirement);
        // }
        // function removeRequirement(requirementElem) {
        //   jQuery(requirementElem).parent().parent().remove();
        // }
    </script>
    {{-- <script>
        "use strict";

        //Start progress
        var totalSteps = $('#v-pills-tab .nav-link').length
        var progressVal = 100 / totalSteps;
        $(function() {
            var pValPerItem = progressVal;
            $('#courseFormProgress .progress-bar').attr('aria-valuemin', 0);
            $('#courseFormProgress .progress-bar').attr('aria-valuemax', pValPerItem);
            $('#courseFormProgress .progress-bar').attr('aria-valuenow', pValPerItem);
            $('#courseFormProgress .progress-bar').width(pValPerItem + '%');
            $('#courseFormProgress .progress-bar').text("Step 1 out of " + totalSteps);
        });

        $("#v-pills-tab .nav-link").on('click', function() {
            var currentStep = $("#v-pills-tab .nav-link").index(this) + 1;
            var pValPerItem = currentStep * progressVal;
            $('#courseFormProgress .progress-bar').attr('aria-valuemin', 0);
            $('#courseFormProgress .progress-bar').attr('aria-valuemax', pValPerItem);
            $('#courseFormProgress .progress-bar').attr('aria-valuenow', pValPerItem);
            $('#courseFormProgress .progress-bar').width(pValPerItem + '%');
            $('#courseFormProgress .progress-bar').text("Step " + currentStep + " out of " + totalSteps);

            if (currentStep == totalSteps) {
                $('#courseFormProgress .progress-bar').text("{{ get_phrase('Finish!') }}");
                $('#courseFormProgress .progress-bar').addClass('bg-success');
            } else {
                $('#courseFormProgress .progress-bar').removeClass('bg-success');
            }
        });
        //End progress
    </script> --}}
@endpush
