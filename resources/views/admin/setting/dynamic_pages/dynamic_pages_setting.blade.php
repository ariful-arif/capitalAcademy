@extends('layouts.admin')
@push('title', get_phrase('Dynamic Pages settings'))
@push('meta')@endpush
@push('css')@endpush
@section('content')
    <!-- Mani section header and breadcrumb -->
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Dynamic Pages Settings') }}
                </h4>
            </div>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="ol-card p-4">
                <div class="ol-card-body">
                    <div class="col-md-12 pb-3">
                        <ul class="nav nav-tabs eNav-Tabs-custom eTab" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="affiliate-tab" data-bs-toggle="tab"
                                    data-bs-target="#affiliate" type="button" role="tab" aria-controls="affiliate"
                                    aria-selected="true">
                                    {{ get_phrase('Affiliate Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="scholarships-tab" data-bs-toggle="tab"
                                    data-bs-target="#scholarships" type="button" role="tab"
                                    aria-controls="scholarships" aria-selected="false">
                                    {{ get_phrase('Scholarships Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="community_initiatives-tab" data-bs-toggle="tab"
                                    data-bs-target="#community_initiatives" type="button" role="tab"
                                    aria-controls="community_initiatives" aria-selected="false">
                                    {{ get_phrase('Community Initiatives Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="business_individuals-tab" data-bs-toggle="tab"
                                    data-bs-target="#business_individuals" type="button" role="tab"
                                    aria-controls="business_individuals" aria-selected="false">
                                    {{ get_phrase('Business Individuals Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="business_organization-tab" data-bs-toggle="tab"
                                    data-bs-target="#business_organization" type="button" role="tab"
                                    aria-controls="business_organization" aria-selected="false">
                                    {{ get_phrase('Business Organization Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="business_corporate-tab" data-bs-toggle="tab"
                                    data-bs-target="#business_corporate" type="button" role="tab"
                                    aria-controls="business_corporate" aria-selected="false">
                                    {{ get_phrase('Business Corporate Page Settings') }}
                                    <span></span>
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content eNav-Tabs-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="affiliate" role="tabpanel"
                                aria-labelledby="affiliate-tab">
                                <div class="tab-pane show active" id="frontendsettings">
                                    @include('admin.setting.dynamic_pages.affiliate_page_setting')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="scholarships" role="tabpanel" aria-labelledby="scholarships-tab">
                                @include('admin.setting.dynamic_pages.scholarships_page_setting')
                            </div>
                            <div class="tab-pane fade" id="community_initiatives" role="tabpanel"
                                aria-labelledby="community_initiatives-tab">
                                @include('admin.setting.dynamic_pages.community_initiatives_page_setting')
                            </div>
                            <div class="tab-pane fade" id="business_individuals" role="tabpanel"
                                aria-labelledby="business_individuals-tab">
                                @include('admin.setting.dynamic_pages.business_individuals_page_setting')
                            </div>
                            <div class="tab-pane fade" id="business_organization" role="tabpanel"
                                aria-labelledby="business_organization-tab">
                                @include('admin.setting.dynamic_pages.business_organization_page_setting')
                            </div>
                            <div class="tab-pane fade" id="business_corporate" role="tabpanel"
                                aria-labelledby="business_corporate-tab">
                                @include('admin.setting.dynamic_pages.business_corporate_page_setting')
                            </div>

                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script type="text/javascript">
        "use strict";

        let blank_faq = jQuery('#blank_faq_field').html();
        let blank_motivational_speech1 = jQuery('#blank_motivational_speech_field1').html();
        let how_it_works_area1 = jQuery('#blank_how_it_works_area').html();
        let affiliateSupport_area1 = jQuery('#blank_affiliateSupport_area').html();
        let howItWorks1 = jQuery('#blank_howItWorks').html();
        let programHighlights1 = jQuery('#blank_programHighlights').html();
        let getInvolved1 = jQuery('#blank_getInvolved').html();
        let professionalChoose1 = jQuery('#blank_professionalChoose').html();
        let company1 = jQuery('#blank_company').html();
        let company2 = jQuery('#blank_company1').html();
        let company3 = jQuery('#blank_company2').html();
        let learningSolution1 = jQuery('#blank_learningSolution').html();
        let corporateChoose1 = jQuery('#blank_corporateChoose').html();

        $(document).ready(function() {

            jQuery('#blank_faq_field').hide();
            jQuery('#blank_motivational_speech_field1').hide();
            jQuery('#blank_how_it_works_area').hide();
            jQuery('#blank_affiliateSupport_area').hide();
            jQuery('#blank_howItWorks').hide();
            jQuery('#blank_programHighlights').hide();
            jQuery('#blank_getInvolved').hide();
            jQuery('#blank_professionalChoose').hide();
            jQuery('#blank_company').hide();
            jQuery('#blank_company1').hide();
            jQuery('#blank_company2').hide();
            jQuery('#blank_learningSolution').hide();
            jQuery('#blank_corporateChoose').hide();

            <?php if(isset($_GET['tab'])): ?>
            $('a[href="#<?php echo $_GET['tab']; ?>"]').trigger('click');
            <?php endif; ?>
        });
        //       faq area
        function appendFaq() {
            jQuery('#faq_area').append(blank_faq);
        }

        function removeFaq(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        //      motivational speech area
        function appendMotivational_speech1() {
            jQuery('#motivational_speech_area1').append(blank_motivational_speech1);
        }

        function removeMotivational_speech1(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // how it works area
        function how_it_works_area() {
            jQuery('#how_it_works_area').append(how_it_works_area1);
        }

        function remove_how_it_works_area(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }

        // how it works area
        function affiliateSupport_area() {
            jQuery('#affiliateSupport_area').append(affiliateSupport_area1);
        }

        function remove_affiliateSupport_area(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // how it works area
        function howItWorks() {
            jQuery('#howItWorks_area').append(howItWorks1);
        }

        function removehowItWorks(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }

        function programHighlights() {
            jQuery('#programHighlights_area').append(programHighlights1);
        }

        function remove_programHighlights(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // program highlights area
        function getInvolved() {
            jQuery('#getInvolved_area').append(programHighlights1);
        }

        function remove_getInvolved(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function professionalChoose() {
            jQuery('#professionalChoose_area').append(professionalChoose1);
        }

        function removeprofessionalChoose(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function appendcompany() {
            jQuery('#company_area').append(company1);
        }

        function removecompany(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function appendcompany1() {
            jQuery('#company_area1').append(company2);
        }

        function removecompany1(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function appendcompany2() {
            jQuery('#company_area2').append(company3);
        }

        function removecompany2(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function learningSolution() {
            jQuery('#learningSolution_area').append(learningSolution1);
        }

        function removelearningSolution(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function corporateChoose() {
            jQuery('#corporateChoose_area').append(corporateChoose1);
        }

        function removecorporateChoose(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
    </script>
@endpush
