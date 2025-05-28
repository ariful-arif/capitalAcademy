@extends('layouts.admin')
@push('title', get_phrase('Create Membership Package'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Add new Membership Package') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('admin.membership.package_update', $membership_package->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pb-2">
                                <div class="eForm-layouts">
                                    <!-- Package Name -->
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Package Title') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="title"
                                            value="{{ $membership_package->title }}"
                                            class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Package Title') }}" required>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="subtitle_1">{{ get_phrase('Package Title') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="subtitle_1"
                                            value="{{ $membership_package->subtitle_1 }}"
                                            class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter Package Subtitle') }}" required>
                                    </div>

                                    <!-- Short Description -->
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="subtitle_2">{{ get_phrase('Short Description') }}</label>
                                        <textarea name="subtitle_2" placeholder="{{ get_phrase('Enter Short Description') }}"
                                            class="form-control ol-form-control" rows="5">{!! $membership_package->subtitle_2 !!}</textarea>
                                    </div>
                                    
                                    <!-- Package Type -->
                                    <div class="fpb-7 mb-3">
                                        <label for="type"
                                            class="form-label ol-form-label">{{ get_phrase('Package Type') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="ol-select2" name="type" id="type" required>
                                            <option value="">{{ get_phrase('Select a Type') }}</option>
                                            <option value="Monthly"
                                                {{ old('type', $membership_package->type) == 'Monthly' ? 'selected' : '' }}>
                                                {{ 'Monthly' }}</option>
                                            <option value="Annual"
                                                {{ old('type', $membership_package->type) == 'Annual' ? 'selected' : '' }}>
                                                {{ 'Annualy' }}</option>
                                        </select>
                                    </div>

                                    <!-- Package Period -->
                                    <div class="fpb-7 mb-3">
                                        <label for="period"
                                            class="form-label ol-form-label">{{ get_phrase('Package Period') }}</label>
                                        <input type="number" name="period" class="form-control ol-form-control"
                                            id="period" value="{{ $membership_package->period }}"
                                            min="1" step=""
                                            placeholder="{{ get_phrase('Enter your package period') }}">
                                        <small>{{ 'Monthly * 3 or Yearly * 1' }}</small>
                                    </div>
                                    <!-- Status -->
                                    <div class="fpb-7 mb-2">
                                        <label for="course_status"
                                            class="form-label ol-form-label">{{ get_phrase('Create as') }}
                                            <span class="text-danger ms-1">*</span></label>
                                        <div class="eRadios">
                                            <div class="form-check">
                                                <input type="radio" value="1" name="status"
                                                    class="form-check-input eRadioSuccess" id="status_active" required
                                                    {{ old('status', $membership_package->status) == 1 ? 'checked' : '' }}>
                                                <label for="status_active"
                                                    class="form-check-label">{{ get_phrase('Active') }}</label>
                                            </div>

                                            <div class="form-check">
                                                <input type="radio" value="0" name="status"
                                                    class="form-check-input eRadioDark" id="status_inactive" required
                                                    {{ old('status', $membership_package->status) == 0 ? 'checked' : '' }}>
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
                                        <div class="paid-section" id="paid-section">
                                            <div class="fpb-7 mb-3">
                                                <label for="price" class="form-label ol-form-label">
                                                    {{ get_phrase('Price') }}
                                                    <small>({{ currency() }})</small>
                                                    <span class="text-danger ms-1">*</span>
                                                </label>
                                                <input type="number" name="price" class="form-control ol-form-control"
                                                    id="price" min="1" step=".01"
                                                    value="{{ $membership_package->price }}"
                                                    placeholder="{{ get_phrase('Enter your course price') }} ({{ currency() }})"
                                                    required>
                                            </div>

                                            <div class="fpb-7">
                                                <div class="">
                                                    <label for="info"
                                                        class="form-label ol-form-label">{{ get_phrase('Features') }}</label>
                                                    <div class="">
                                                        <div id = "faq_area">
                                                            @php
                                                                $features = is_string($membership_package->features)
                                                                    ? json_decode($membership_package->features, true)
                                                                    : $membership_package->features;
                                                            @endphp
                                                            @if (is_array($features) && count($features) > 0)
                                                                @foreach ($features as $key => $feature)
                                                                    <div class="d-flex mt-2">
                                                                        <div class="flex-grow-1 pe-3">
                                                                            <div class="form-group">
                                                                                <input type="text"
                                                                                    value="{{ $feature['title'] ?? '' }}"
                                                                                    class="form-control ol-form-control mb-2"
                                                                                    name="feature[]"
                                                                                    id="faqs{{ $key ?? '' }}"
                                                                                    placeholder="{{ get_phrase('Add feature') }}">

                                                                                <input type="text"
                                                                                    value="{{ $feature['description'] ?? '' }}"
                                                                                    class="form-control ol-form-control"
                                                                                    name="feature[]"
                                                                                    id="faqs{{ $key ?? '' }}"
                                                                                    placeholder="{{ get_phrase('Add subfeature') }}">
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
                                                                            <input type="text" class="form-control ol-form-control mb-2"
                                                                                name="feature[]" id="faqs"
                                                                                placeholder="{{ get_phrase('Add feature') }}">
                                                                                
                                                                            <input type="text" class="form-control ol-form-control"
                                                                                name="subfeature[]" id="faqs"
                                                                                placeholder="{{ get_phrase('Add subfeature') }}">
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
                                                                            <input type="text" class="form-control ol-form-control mb-2"
                                                                                name="feature[]"
                                                                                placeholder="{{ get_phrase('Add feature') }}">
                                                                            
                                                                            <input type="text" class="form-control ol-form-control mb-2"
                                                                                name="subfeature[]"
                                                                                placeholder="{{ get_phrase('Add subfeature') }}">
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

    </script>

@endpush
