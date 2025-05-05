@extends('layouts.admin')
@push('title', get_phrase('Edit Subscription Package'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Update Subscription Package') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('admin.subscription_package.update', $subscription_package->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="course_type" value="general" required>
                        <input type="hidden" name="instructors[]" value="{{ auth()->user()->id }}" required>
                        <div class="row">
                            <div class="col-md-6 pb-2">
                                <div class="eForm-layouts">
                                    <!-- Package Name -->
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="package_name">{{ get_phrase('Package Name') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="package_name"
                                            value="{{ $subscription_package->package_name }}"
                                            class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Course Package Name') }}" required>
                                    </div>

                                    <!-- Short Description -->
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="short_description">{{ get_phrase('Short Description') }}</label>
                                        <textarea name="short_description" placeholder="{{ get_phrase('Enter Short Description') }}"
                                            class="form-control ol-form-control" rows="5">{!! $subscription_package->short_description !!}</textarea>
                                    </div>

                                    <!-- Subscription Type -->
                                    <div class="fpb-7 mb-3">
                                        <label for="subscription_type"
                                            class="form-label ol-form-label">{{ get_phrase('Subscription Type') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="ol-select2" name="subscription_type" id="subscription_type" required>
                                            <option value="">{{ get_phrase('Select a Type') }}</option>
                                            <option value="individual"
                                                {{ old('subscription_type', $subscription_package->subscription_type) == 'individual' ? 'selected' : '' }}>
                                                {{ 'Individual Subscription' }}</option>
                                            <option value="team"
                                                {{ old('subscription_type', $subscription_package->subscription_type) == 'team' ? 'selected' : '' }}>
                                                {{ 'Team Subscription' }}</option>
                                        </select>
                                    </div>
                                    <!-- Package Type -->
                                    <div class="fpb-7 mb-3">
                                        <label for="package_type"
                                            class="form-label ol-form-label">{{ get_phrase('Package Type') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="ol-select2" name="package_type" id="package_type" required>
                                            <option value="">{{ get_phrase('Select a Type') }}</option>
                                            <option value="Monthly"
                                                {{ old('package_type', $subscription_package->package_type) == 'Monthly' ? 'selected' : '' }}>
                                                {{ 'Monthly' }}</option>
                                            <option value="Yearly"
                                                {{ old('package_type', $subscription_package->package_type) == 'Yearly' ? 'selected' : '' }}>
                                                {{ 'Yearly' }}</option>
                                        </select>
                                    </div>

                                    <!-- Package Duration -->
                                    <div class="fpb-7 mb-3">
                                        <label for="package_duration"
                                            class="form-label ol-form-label">{{ get_phrase('Package Duration') }}</label>
                                        <input type="number" name="package_duration" class="form-control ol-form-control"
                                            id="package_duration" value="{{ $subscription_package->package_duration }}"
                                            min="1" step=""
                                            placeholder="{{ get_phrase('Enter your package duration') }}">
                                        <small>{{ 'Monthly * 3 or Yearly * 1' }}</small>
                                    </div> <!-- Status -->
                                    <div class="fpb-7 mb-2">
                                        <label for="course_status"
                                            class="form-label ol-form-label">{{ get_phrase('Create as') }}
                                            <span class="text-danger ms-1">*</span></label>
                                        <div class="eRadios">
                                            <div class="form-check">
                                                <input type="radio" value="active" name="status"
                                                    class="form-check-input eRadioSuccess" id="status_active" required
                                                    {{ old('status', $subscription_package->status) == 'active' ? 'checked' : '' }}>
                                                <label for="status_active"
                                                    class="form-check-label">{{ get_phrase('Active') }}</label>
                                            </div>

                                            <div class="form-check">
                                                <input type="radio" value="inactive" name="status"
                                                    class="form-check-input eRadioDark" id="status_inactive" required
                                                    {{ old('status', $subscription_package->status) == 'inactive' ? 'checked' : '' }}>
                                                <label for="status_inactive"
                                                    class="form-check-label">{{ get_phrase('Inactive') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="eForm-layouts">





                                    <!-- Pricing Type -->
                                    <div class="fpb-7 mb-3">


                                        <!-- Paid Section -->
                                        <div class="paid-section" id="paid-section"
                                            style="{{ old('is_paid', $subscription_package->is_paid) == '1' ? '' : 'display: none;' }}">
                                            <div class="fpb-7 mb-3">
                                                <label for="price" class="form-label ol-form-label">
                                                    {{ get_phrase('Price') }}
                                                    <small>({{ currency() }})</small>
                                                    <span class="text-danger ms-1">*</span>
                                                </label>
                                                <input type="number" name="price" class="form-control ol-form-control"
                                                    id="price" min="1" step=".01"
                                                    value="{{ $subscription_package->price }}"
                                                    placeholder="{{ get_phrase('Enter your course price') }} ({{ currency() }})"
                                                    required>
                                            </div>

                                            <div class="fpb-7 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="discount_flag" value="1"
                                                        class="form-check-input eRadioSuccess" id="discount_flag"
                                                        {{ $subscription_package->discount_flag ? 'checked' : '' }}>
                                                    <label for="discount_flag" class="form-check-label">
                                                        {{ get_phrase('Check if this course has a discount') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="fpb-7 mb-3">
                                                <label for="discounted_price" class="form-label ol-form-label">
                                                    {{ get_phrase('Discounted Price') }}
                                                </label>
                                                <input type="number" name="discounted_price"
                                                    class="form-control ol-form-control" id="discounted_price"
                                                    value="{{ $subscription_package->discounted_price }}" min="1"
                                                    step=".01"
                                                    placeholder="{{ get_phrase('Enter your discount price') }} ({{ currency() }})">
                                            </div>
                                            <p class="title text-14px mb-3">{{ get_phrase('Banner') }}</p>
                                            <div class="ol-card-body">
                                                <div class="form-group text-start mb-3">
                                                    <img id="previewImage" class="my-2" height="150px"
                                                        src="{{ asset($subscription_package->banner) }}" alt="Preview">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label ol-form-label" for="certificate_template">
                                                        {{ get_phrase('Upload your banner') }}
                                                    </label>
                                                    <input type="file" class="form-control" name="banner"
                                                        id="banner" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="fpb-7">
                                                <div class="">
                                                    <label for="info"
                                                        class="form-label ol-form-label">{{ get_phrase('Info') }}</label>
                                                    <div class="">
                                                        <div id = "faq_area">
                                                            @php
                                                                $infos = is_string($subscription_package->info)
                                                                    ? json_decode($subscription_package->info, true)
                                                                    : $subscription_package->info;
                                                            @endphp
                                                            @if (is_array($infos) && count($infos) > 0)
                                                                @foreach ($infos as $key => $info)
                                                                    <div class="d-flex mt-2">
                                                                        <div class="flex-grow-1 pe-3">
                                                                            <div class="form-group">
                                                                                <input type="text"
                                                                                    value="{{ $info ?? '' }}"
                                                                                    class="form-control ol-form-control"
                                                                                    name="info[]"
                                                                                    id="faqs{{ $key ?? '' }}"
                                                                                    placeholder="{{ get_phrase('Add Info') }}">
                                                                                {{-- <textarea name="faq_description[]" rows="2" class="form-control ol-form-control mt-2" placeholder="{{get_phrase('Answer')}}">{{$faq['description'] ?? ''}}</textarea> --}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            @if ($key == 0)
                                                                                <button type="button"
                                                                                    class="btn ol-btn-light ol-icon-btn"
                                                                                    name="button"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ get_phrase('Add new') }}"
                                                                                    onclick="appendFaq()"> <i
                                                                                        class="fi-rr-plus-small"></i>
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn ol-btn-light ol-icon-btn mt-0"
                                                                                    name="button"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ get_phrase('Remove') }}"
                                                                                    onclick="removeFaq(this)"> <i
                                                                                        class="fi-rr-minus-small"></i>
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="d-flex mt-2">
                                                                    <div class="flex-grow-1 pe-3">
                                                                        <div class="form-group">
                                                                            <input type="text"
                                                                                class="form-control ol-form-control"
                                                                                name="info[]" id="faqs"
                                                                                placeholder="{{ get_phrase('Add Info') }}">
                                                                            {{-- <textarea name="faq_description[]" rows="2" class="form-control ol-form-control mt-2" placeholder="{{get_phrase('Answer')}}"></textarea> --}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <button type="button"
                                                                            class="btn ol-btn-light ol-icon-btn"
                                                                            name="button" data-bs-toggle="tooltip"
                                                                            title="{{ get_phrase('Add new') }}"
                                                                            onclick="appendFaq()"> <i
                                                                                class="fi-rr-plus-small"></i> </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div id = "blank_faq_field">
                                                                <div class="d-flex mt-2">
                                                                    <div class="flex-grow-1 pe-3">
                                                                        <div class="form-group">
                                                                            <input type="text"
                                                                                class="form-control ol-form-control"
                                                                                name="info[]"
                                                                                placeholder="{{ get_phrase('Add Info') }}">
                                                                            {{-- <textarea name="faq_description[]" rows="2" class="form-control ol-form-control mt-2" placeholder="{{get_phrase('Answer')}}"></textarea> --}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <button type="button"
                                                                            class="btn ol-btn-light ol-icon-btn mt-0"
                                                                            name="button" data-bs-toggle="tooltip"
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
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" class="btn ol-btn-primary float-end">
                                    {{ get_phrase('Update') }}
                                </button>
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

        // Image Preview for Certificate Template
        document.getElementById('banner').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Initialize Select2
        $(document).ready(function() {
            $('.ol-select2').select2({
                placeholder: "Select your banner",
                allowClear: true
            });
        });

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

@endpush
