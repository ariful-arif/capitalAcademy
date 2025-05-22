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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="insights_career-tab" data-bs-toggle="tab"
                                    data-bs-target="#insights_career" type="button" role="tab"
                                    aria-controls="insights_career" aria-selected="false">
                                    {{ get_phrase('Insights Career Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="partnership-tab" data-bs-toggle="tab"
                                    data-bs-target="#partnership" type="button" role="tab" aria-controls="partnership"
                                    aria-selected="false">
                                    {{ get_phrase('Partnership Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="full_code_of_ethics-tab" data-bs-toggle="tab"
                                    data-bs-target="#full_code_of_ethics" type="button" role="tab"
                                    aria-controls="full_code_of_ethics" aria-selected="false">
                                    {{ get_phrase('Full Code of Ethics Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="professional_conduct-tab" data-bs-toggle="tab"
                                    data-bs-target="#professional_conduct" type="button" role="tab"
                                    aria-controls="professional_conduct" aria-selected="false">
                                    {{ get_phrase('Professional Conduct Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="voluentry_community-tab" data-bs-toggle="tab"
                                    data-bs-target="#voluentry_community" type="button" role="tab"
                                    aria-controls="voluentry_community" aria-selected="false">
                                    {{ get_phrase('Voluentry Community Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="business_student-tab" data-bs-toggle="tab"
                                    data-bs-target="#business_student" type="button" role="tab"
                                    aria-controls="business_student" aria-selected="false">
                                    {{ get_phrase('Business Student Page Settings') }}
                                    <span></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="business_university-tab" data-bs-toggle="tab"
                                    data-bs-target="#business_university" type="button" role="tab"
                                    aria-controls="business_university" aria-selected="false">
                                    {{ get_phrase('Business University Page Settings') }}
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
                            <div class="tab-pane fade" id="scholarships" role="tabpanel"
                                aria-labelledby="scholarships-tab">
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
                            <div class="tab-pane fade" id="insights_career" role="tabpanel"
                                aria-labelledby="insights_career-tab">
                                @include('admin.setting.dynamic_pages.insights_career_page_setting')
                            </div>
                            <div class="tab-pane fade" id="partnership" role="tabpanel"
                                aria-labelledby="partnership-tab">
                                @include('admin.setting.dynamic_pages.partnership_page_setting')
                            </div>
                            <div class="tab-pane fade" id="full_code_of_ethics" role="tabpanel"
                                aria-labelledby="full_code_of_ethics-tab">
                                @include('admin.setting.dynamic_pages.full_code_of_ethics_page_setting')
                            </div>
                            <div class="tab-pane fade" id="professional_conduct" role="tabpanel"
                                aria-labelledby="professional_conduct-tab">
                                @include('admin.setting.dynamic_pages.professional_conduct_page_setting')
                            </div>
                            <div class="tab-pane fade" id="voluentry_community" role="tabpanel"
                                aria-labelledby="voluentry_community-tab">
                                @include('admin.setting.dynamic_pages.voluentry_community_page_setting')
                            </div>
                            <div class="tab-pane fade" id="business_student" role="tabpanel"
                                aria-labelledby="business_student-tab">
                                @include('admin.setting.dynamic_pages.business_student_page_setting')
                            </div>
                            <div class="tab-pane fade" id="business_university" role="tabpanel"
                                aria-labelledby="business_university-tab">
                                @include('admin.setting.dynamic_pages.business_university_page_setting')
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
        let employeeBenefits1 = jQuery('#blank_employeeBenefits').html();
        let careers1 = jQuery('#blank_careers').html();
        let professionalChoose2 = jQuery('#blank_professionalChoose1').html();
        let partnershipOppor1 = jQuery('#blank_partnershipOppor').html();
        let successStories1 = jQuery('#blank_successStories').html();
        let coreEthics1 = jQuery('#blank_coreEthics').html();
        let memberObligation1 = jQuery('#blank_memberObligation').html();
        let enforcement1 = jQuery('#blank_enforcement').html();
        let professional1 = jQuery('#blank_professional').html();
        let reliability1 = jQuery('#blank_reliability').html();
        let obligation1 = jQuery('#blank_obligation').html();
        let obligationToEmployee1 = jQuery('#blank_obligationToEmployee').html();
        let investment1 = jQuery('#blank_investment').html();
        let interest1 = jQuery('#blank_interest').html();
        let whyStudentGet1 = jQuery('#blank_whyStudentGet').html();
        let offerUniversity1 = jQuery('#blank_offerUniversity').html();
        let collaboration1 = jQuery('#blank_collaboration').html();

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
            jQuery('#blank_employeeBenefits').hide();
            jQuery('#blank_careers').hide();
            jQuery('#blank_professionalChoose1').hide();
            jQuery('#blank_partnershipOppor').hide();
            jQuery('#blank_successStories').hide();
            jQuery('#blank_coreEthics').hide();
            jQuery('#blank_memberObligation').hide();
            jQuery('#blank_enforcement').hide();
            jQuery('#blank_professional').hide();
            jQuery('#blank_reliability').hide();
            jQuery('#blank_obligation').hide();
            jQuery('#blank_obligationToEmployee').hide();
            jQuery('#blank_investment').hide();
            jQuery('#blank_interest').hide();
            jQuery('#blank_whyStudentGet').hide();
            jQuery('#blank_offerUniversity').hide();
            jQuery('#blank_collaboration').hide();

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
            jQuery('#getInvolved_area').append(getInvolved1);
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
        // professionalChoose area
        function employeeBenefits() {
            jQuery('#employeeBenefits_area').append(employeeBenefits1);
        }

        function removeemployeeBenefits(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function careers() {
            jQuery('#careers_area').append(careers1);
        }

        function removecareers(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function professionalChoose12() {
            jQuery('#professionalChoose_area1').append(professionalChoose2);
        }

        function removeprofessionalChoose1(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function partnershipOppor() {
            jQuery('#partnershipOppor_area').append(partnershipOppor1);
        }

        function removepartnershipOppor(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function successStories() {
            jQuery('#successStories_area').append(successStories1);
        }

        function removesuccessStories(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function coreEthics() {
            jQuery('#coreEthics_area').append(coreEthics1);
        }

        function removecoreEthics(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function memberObligation() {
            jQuery('#memberObligation_area').append(memberObligation1);
        }

        function removememberObligation(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function enforcement() {
            jQuery('#enforcement_area').append(enforcement1);
        }

        function removeenforcement(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function professional() {
            jQuery('#professional_area').append(professional1);
        }
        function removeprofessional(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function reliability() {
            jQuery('#reliability_area').append(reliability1);
        }
        function removereliability(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function obligation() {
            jQuery('#obligation_area').append(obligation1);
        }
        function removeobligation(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function obligationToEmployee() {
            jQuery('#obligationToEmployee_area').append(obligationToEmployee1);
        }
        function removeobligationToEmployee(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function investment() {
            jQuery('#investment_area').append(investment1);
        }
        function removeinvestment(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function interest() {
            jQuery('#interest_area').append(interest1);
        }
        function removeinterest(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function whyStudentGet() {
            jQuery('#whyStudentGet_area').append(whyStudentGet1);
        }
        function removewhyStudentGet(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function offerUniversity() {
            jQuery('#offerUniversity_area').append(offerUniversity1);
        }
        function removeofferUniversity(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }
        // professionalChoose area
        function collaboration() {
            jQuery('#collaboration_area').append(collaboration1);
        }
        function removecollaboration(faqElem) {
            jQuery(faqElem).parent().parent().remove();
        }


    </script>
@endpush
