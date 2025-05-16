<?php

namespace App\Http\Controllers;
use App\Models\FileUploader;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DynamicPagesSettingController extends Controller
{
    public function dynamic_pages_settings()
    {
        return view("admin.setting.dynamic_pages.dynamic_pages_setting");
    }

    public function dynamic_pages_settings_update1(Request $request)
    {
        // $data = $request->all();
        // // array_shift($data);
        // dd($data);
        // die;
        if ($request->type == 'affiliate_page') {
            // Prepare the data structure
            $data = [
                'title' => $request->input('title'),
                'subtitle' => $request->input('sub_title'),
                'thumbnail' => $this->uploadFile($request->file('thumbnail'), 'affiliate_page'),
                'whyPartner' => [
                    'title' => $request->input('why_partner_title'),
                    'thumbnail' => $this->uploadFile($request->file('why_partner_thumbnail'), 'affiliate_page'),
                    'features' => $this->prepareFeatures(
                        $request->input('titles'),
                        $request->input('descriptions'),
                        $request->file('logos'),
                        'affiliate_page'
                    ),
                ],
                'howItWorks' => [
                    'title' => $request->input('how_it_works_title'),
                    'features' => $this->prepareFeatures(
                        $request->input('how_it_works_titles'),
                        $request->input('how_it_works_descriptions')
                    ),
                ],
                'whoCanJoin' => [
                    'title' => $request->input('who_can_join_title'),
                    'subtitle' => $request->input('who_can_join_subtitle'),
                ],
                'affiliateSupport' => [
                    'title' => $request->input('affiliate_support_title'),
                    'subtitle' => $request->input('affiliate_support_subtitle'),
                    'features' => $this->prepareFeatures(
                        $request->input('support_titles'),
                        $request->input('support_descriptions'),
                        $request->file('support_logos'),
                        'affiliate_page'
                    ),
                ],
            ];

            // Save the data in the database
            DynamicPage::where('key', 'affiliate_program_page')->update([
                'value' => json_encode($data)
            ]);


            // Flash success message
            Session::flash('success', get_phrase('Affiliate page updated successfully'));
            return redirect()->back();
        }

        return redirect()->back();
    }
    // $data = $request->all();
    // dd($data);
    // die;
    public function dynamic_pages_settings_update(Request $request)
    {

        if ($request->type == 'affiliate_page') {
            $dynamicPage = DynamicPage::where('key', 'affiliate_program_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'affiliate_page', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),

                'whyPartner' => [
                    'title' => $request->input('why_partner_title', $existingData['whyPartner']['title'] ?? ''),

                    'thumbnail' => $request->hasFile('why_partner_thumbnail')
                        ? $this->uploadFile($request->file('why_partner_thumbnail'), 'affiliate_page', $existingData['whyPartner']['thumbnail'] ?? null)
                        : ($existingData['whyPartner']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('why_partner_thumbnail_1')
                        ? $this->uploadFile($request->file('why_partner_thumbnail_1'), 'affiliate_page', $existingData['whyPartner']['thumbnail_1'] ?? null)
                        : ($existingData['whyPartner']['thumbnail_1'] ?? ''),

                    'features' => $this->prepareFeatures(
                        $request->input('titles', []),
                        $request->input('descriptions', []),
                        $request->file('logos', []),
                        'affiliate_page',
                        $existingData['whyPartner']['features'] ?? []
                    ),
                ],
                'howItWorks' => [
                    'title' => $request->input('how_it_works_title', $existingData['howItWorks']['title'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('how_it_works_titles', []),
                        $request->input('how_it_works_descriptions', []),
                        null,
                        null,
                        $existingData['howItWorks']['features'] ?? []
                    ),
                ],
                'whoCanJoin' => [
                    'title' => $request->input('who_can_join_title', $existingData['whoCanJoin']['title'] ?? ''),
                    'subtitle' => $request->input('who_can_join_subtitle', $existingData['whoCanJoin']['subtitle'] ?? ''),
                ],
                'affiliateSupport' => [
                    'title' => $request->input('affiliate_support_title', $existingData['affiliateSupport']['title'] ?? ''),
                    'subtitle' => $request->input('affiliate_support_subtitle', $existingData['affiliateSupport']['subtitle'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('support_titles', []),
                        $request->input('support_descriptions', []),
                        $request->file('support_logos', []),
                        'affiliate_page',
                        $existingData['affiliateSupport']['features'] ?? []
                    ),
                ],
            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Affiliate page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'scholarships_page') {
            $dynamicPage = DynamicPage::where('key', 'scholarships_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'scholarships_page', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),

                'howItWorks' => [
                    'title' => $request->input('how_it_works_title', $existingData['howItWorks']['title'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('how_it_works_descriptions', []),
                        null,
                        null,
                        null,
                        $existingData['howItWorks']['features'] ?? []
                    ),
                ],
                'apply' => [
                    'title' => $request->input('apply_title', $existingData['apply']['title'] ?? ''),
                    'subtitle' => $request->input('apply_subtitle', $existingData['apply']['subtitle'] ?? ''),
                    'note' => $request->input('apply_note', $existingData['apply']['note'] ?? ''),
                    'thumbnail' => $request->hasFile('apply_thumbnail')
                        ? $this->uploadFile($request->file('apply_thumbnail'), 'scholarships_page', $existingData['apply']['thumbnail'] ?? null)
                        : ($existingData['apply']['thumbnail'] ?? ''),
                ],

            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Scholarships Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'community_initiatives_page') {
            $dynamicPage = DynamicPage::where('key', 'community_initiatives_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $existingThumbnails = isset($existingData['thumbnail']) && is_array($existingData['thumbnail'])
                ? $existingData['thumbnail']
                : [];

            $removedThumbnails = json_decode($request->input('removed_thumbnails', '[]'), true);
            $updatedThumbnails = array_filter($existingThumbnails, function ($thumb) use ($removedThumbnails) {
                return !in_array($thumb, $removedThumbnails);
            });

            foreach ($removedThumbnails as $thumbToDelete) {
                $fullPath = public_path($thumbToDelete);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            $newThumbnails = [];
            if ($request->hasFile('thumbnail')) {
                foreach ($request->file('thumbnail') as $file) {
                    $uploadedPath = $this->uploadFile($file, 'community_page'); // using correct folder
                    $newThumbnails[] = $uploadedPath;
                }
            }

            $finalThumbnails = array_merge($updatedThumbnails, $newThumbnails);

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $finalThumbnails,
                'grantProgram' => [
                    'title' => $request->input('grantProgram_title', $existingData['grantProgram']['title'] ?? ''),
                    'subtitle' => $request->input('grantProgram_subtitle', $existingData['grantProgram']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('grantProgram_thumbnail')
                        ? $this->uploadFile($request->file('grantProgram_thumbnail'), 'community_page', $existingData['grantProgram']['thumbnail'] ?? null)
                        : ($existingData['grantProgram']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('grantProgram_thumbnail_1')
                        ? $this->uploadFile($request->file('grantProgram_thumbnail_1'), 'community_page', $existingData['grantProgram']['thumbnail_1'] ?? null)
                        : ($existingData['grantProgram']['thumbnail_1'] ?? ''),
                ],
                'programHighlights' => [
                    'title' => $request->input('programHighlights_title', $existingData['programHighlights']['title'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('programHighlights_titles', []),
                        $request->input('programHighlights_descriptions', []),
                        null,
                        null,
                        $existingData['programHighlights']['features'] ?? []
                    ),
                ],
                'educationalPartnerships' => [
                    'title' => $request->input('educationalPartnerships_title', $existingData['educationalPartnerships']['title'] ?? ''),
                    'subtitle' => $request->input('educationalPartnerships_subtitle', $existingData['educationalPartnerships']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('educationalPartnerships_thumbnail')
                        ? $this->uploadFile($request->file('educationalPartnerships_thumbnail'), 'community_page', $existingData['educationalPartnerships']['thumbnail'] ?? null)
                        : ($existingData['educationalPartnerships']['thumbnail'] ?? ''),
                ],
                'diversityEquity' => [
                    'title' => $request->input('diversityEquity_title', $existingData['diversityEquity']['title'] ?? ''),
                    'subtitle' => $request->input('diversityEquity_subtitle', $existingData['diversityEquity']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('diversityEquity_thumbnail')
                        ? $this->uploadFile($request->file('diversityEquity_thumbnail'), 'community_page', $existingData['diversityEquity']['thumbnail'] ?? null)
                        : ($existingData['diversityEquity']['thumbnail'] ?? ''),
                ],
                'getInvolved' => [
                    'title' => $request->input('getInvolved_title', $existingData['getInvolved']['title'] ?? ''),
                    'subtitle' => $request->input('getInvolved_subtitle', $existingData['getInvolved']['subtitle'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('getInvolved_titles', []),
                        $request->input('getInvolved_descriptions', []),
                        null,
                        null,
                        $existingData['getInvolved']['features'] ?? []
                    ),
                ],
            ];

            $dynamicPage->update(['value' => json_encode($data)]);

            Session::flash('success', get_phrase('Community Initiatives Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'business_individuals_page') {
            $dynamicPage = DynamicPage::where('key', 'business_individuals_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'business_individuals', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),
                'thumbnail_video' => $request->hasFile('thumbnail_video')
                    ? $this->uploadFile($request->file('thumbnail_video'), 'business_individuals', $existingData['thumbnail_video'] ?? null)
                    : ($existingData['thumbnail_video'] ?? ''),
                'active_students' => $request->input('active_students', $existingData['active_students'] ?? ''),
                'students_percentage' => $request->input('students_percentage', $existingData['students_percentage'] ?? ''),
                'professionalChoose' => [
                    'title' => $request->input('professionalChoose_title', $existingData['professionalChoose']['title'] ?? ''),
                    // 'subtitle' => $request->input('professionalChoose_subtitle', $existingData['professionalChoose']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('professionalChoose_subtitle', $existingData['professionalChoose']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),

                    'thumbnail' => $request->hasFile('professionalChoose_thumbnail')
                        ? $this->uploadFile($request->file('professionalChoose_thumbnail'), 'business_individuals', $existingData['professionalChoose']['thumbnail'] ?? null)
                        : ($existingData['professionalChoose']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('professionalChoose_thumbnail_1')
                        ? $this->uploadFile($request->file('professionalChoose_thumbnail_1'), 'business_individuals', $existingData['professionalChoose']['thumbnail_1'] ?? null)
                        : ($existingData['professionalChoose']['thumbnail_1'] ?? ''),
                ],
                'professionals' => [
                    'title' => $request->input('professionals_title', $existingData['professionals']['title'] ?? ''),
                    'subtitle' => $request->input('professionals_subtitle', $existingData['professionals']['subtitle'] ?? ''),
                    'subtitle1' => $request->input('professionals_subtitle1', $existingData['professionals']['subtitle1'] ?? ''),
                    'subtitle2' => $request->input('professionals_subtitle2', $existingData['professionals']['subtitle2'] ?? ''),
                    'subtitle3' => $request->input('professionals_subtitle3', $existingData['professionals']['subtitle3'] ?? ''),
                    'subtitle4' => $request->input('professionals_subtitle4', $existingData['professionals']['subtitle4'] ?? ''),
                ],
                'increased' => [
                    'percentage' => $request->input('increased_percentage', $existingData['increased']['percentage'] ?? ''),
                    'title' => $request->input('increased_title', $existingData['increased']['title'] ?? ''),
                    'subtitle' => $request->input('increased_subtitle', $existingData['increased']['subtitle'] ?? ''),
                ],
                'improved' => [
                    'percentage' => $request->input('improved_percentage', $existingData['improved']['percentage'] ?? ''),
                    'title' => $request->input('improved_title', $existingData['improved']['title'] ?? ''),
                    'subtitle' => $request->input('improved_subtitle', $existingData['improved']['subtitle'] ?? ''),
                ],
                'fortuneCompany' => [
                    'title' => $request->input('fortuneCompany_title', $existingData['fortuneCompany']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('fortuneCompany_thumbnail')
                        ? $this->uploadFile($request->file('fortuneCompany_thumbnail'), 'business_individuals', $existingData['fortuneCompany']['thumbnail'] ?? null)
                        : ($existingData['fortuneCompany']['thumbnail'] ?? ''),
                    'company' => $this->prepareCompany(
                        $request->file('company_thumbnails', []),
                        'business_individuals',
                        $existingData['fortuneCompany']['company'] ?? []
                    ),
                ],


            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Business Individuals Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'business_organization_page') {
            $dynamicPage = DynamicPage::where('key', 'business_organization_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'business_individuals', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),
                'thumbnail_video' => $request->hasFile('thumbnail_video')
                    ? $this->uploadFile($request->file('thumbnail_video'), 'business_individuals', $existingData['thumbnail_video'] ?? null)
                    : ($existingData['thumbnail_video'] ?? ''),
                'learningSolution' => [
                    'title' => $request->input('learningSolution_title', $existingData['learningSolution']['title'] ?? ''),
                    // 'subtitle' => $request->input('learningSolution_subtitle', $existingData['learningSolution']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('learningSolution_subtitle', $existingData['learningSolution']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),

                    'thumbnail' => $request->hasFile('learningSolution_thumbnail')
                        ? $this->uploadFile($request->file('learningSolution_thumbnail'), 'business_individuals', $existingData['learningSolution']['thumbnail'] ?? null)
                        : ($existingData['learningSolution']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('learningSolution_thumbnail_1')
                        ? $this->uploadFile($request->file('learningSolution_thumbnail_1'), 'business_individuals', $existingData['learningSolution']['thumbnail_1'] ?? null)
                        : ($existingData['learningSolution']['thumbnail_1'] ?? ''),
                ],
                'enterPrise' => [
                    'title' => $request->input('enterPrise_title', $existingData['enterPrise']['title'] ?? ''),
                    'subtitle' => $request->input('enterPrise_subtitle', $existingData['enterPrise']['subtitle'] ?? ''),
                    'subtitle1' => $request->input('enterPrise_subtitle1', $existingData['enterPrise']['subtitle1'] ?? ''),
                    'subtitle2' => $request->input('enterPrise_subtitle2', $existingData['enterPrise']['subtitle2'] ?? ''),
                    'subtitle2_1' => $request->input('enterPrise_subtitle2_1', $existingData['enterPrise']['subtitle2_1'] ?? ''),
                    'subtitle2_2' => $request->input('enterPrise_subtitle2_2', $existingData['enterPrise']['subtitle2_2'] ?? ''),
                    'subtitle3' => $request->input('enterPrise_subtitle3', $existingData['enterPrise']['subtitle3'] ?? ''),
                    'subtitle4' => $request->input('enterPrise_subtitle4', $existingData['enterPrise']['subtitle4'] ?? ''),
                ],
                'increased' => [
                    'percentage' => $request->input('increased_percentage', $existingData['increased']['percentage'] ?? ''),
                    'title' => $request->input('increased_title', $existingData['increased']['title'] ?? ''),
                    'subtitle' => $request->input('increased_subtitle', $existingData['increased']['subtitle'] ?? ''),
                ],
                'improved' => [
                    'percentage' => $request->input('improved_percentage', $existingData['improved']['percentage'] ?? ''),
                    'title' => $request->input('improved_title', $existingData['improved']['title'] ?? ''),
                    'subtitle' => $request->input('improved_subtitle', $existingData['improved']['subtitle'] ?? ''),
                ],
                'fortuneCompany' => [
                    'title' => $request->input('fortuneCompany_title', $existingData['fortuneCompany']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('fortuneCompany_thumbnail')
                        ? $this->uploadFile($request->file('fortuneCompany_thumbnail'), 'business_individuals', $existingData['fortuneCompany']['thumbnail'] ?? null)
                        : ($existingData['fortuneCompany']['thumbnail'] ?? ''),
                    'company' => $this->prepareCompany(
                        $request->file('company_thumbnails', []),
                        'business_individuals',
                        $existingData['fortuneCompany']['company'] ?? []
                    ),
                ],


            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Scholarships Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'business_corporate_page') {
            $dynamicPage = DynamicPage::where('key', 'business_corporate_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $existingThumbnails = isset($existingData['thumbnail']) && is_array($existingData['thumbnail'])
                ? $existingData['thumbnail']
                : [];

            // 1. Get removed thumbnails from the form
            $removedThumbnails = json_decode($request->input('removed_thumbnails', '[]'), true);

            // 2. Filter out removed thumbnails
            $updatedThumbnails = array_filter($existingThumbnails, function ($thumb) use ($removedThumbnails) {
                return !in_array($thumb, $removedThumbnails);
            });

            // 3. Delete removed thumbnail files from disk
            foreach ($removedThumbnails as $thumbToDelete) {
                $fullPath = public_path($thumbToDelete);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // 4. Process new uploads
            $newThumbnails = [];
            if ($request->hasFile('thumbnail')) {
                foreach ($request->file('thumbnail') as $file) {
                    $uploadedPath = $this->uploadFile($file, 'business_individuals');
                    $newThumbnails[] = $uploadedPath;
                }
            }

            // 5. Final thumbnail list (existing - removed + new)
            $finalThumbnail = array_merge($updatedThumbnails, $newThumbnails);

            // 6. Save back to database
            $data = [
                'thumbnail' => $finalThumbnail,
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail_video' => $request->hasFile('thumbnail_video')
                    ? $this->uploadFile($request->file('thumbnail_video'), 'business_individuals', $existingData['thumbnail_video'] ?? null)
                    : ($existingData['thumbnail_video'] ?? ''),
                'corporateChoose' => [
                    'title' => $request->input('corporateChoose_title', $existingData['corporateChoose']['title'] ?? ''),
                    // 'subtitle' => $request->input('corporateChoose_subtitle', $existingData['corporateChoose']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('corporateChoose_subtitle', $existingData['corporateChoose']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),
                    'thumbnail' => $request->hasFile('corporateChoose_thumbnail')
                        ? $this->uploadFile($request->file('corporateChoose_thumbnail'), 'business_individuals', $existingData['corporateChoose']['thumbnail'] ?? null)
                        : ($existingData['corporateChoose']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('corporateChoose_thumbnail_1')
                        ? $this->uploadFile($request->file('corporateChoose_thumbnail_1'), 'business_individuals', $existingData['corporateChoose']['thumbnail_1'] ?? null)
                        : ($existingData['corporateChoose']['thumbnail_1'] ?? ''),
                ],

                'increased' => [
                    'percentage' => $request->input('increased_percentage', $existingData['increased']['percentage'] ?? ''),
                    'title' => $request->input('increased_title', $existingData['increased']['title'] ?? ''),
                    'subtitle' => $request->input('increased_subtitle', $existingData['increased']['subtitle'] ?? ''),
                ],
                'improved' => [
                    'percentage' => $request->input('improved_percentage', $existingData['improved']['percentage'] ?? ''),
                    'title' => $request->input('improved_title', $existingData['improved']['title'] ?? ''),
                    'subtitle' => $request->input('improved_subtitle', $existingData['improved']['subtitle'] ?? ''),
                ],
                'fortuneCompany' => [
                    'title' => $request->input('fortuneCompany_title', $existingData['fortuneCompany']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('fortuneCompany_thumbnail')
                        ? $this->uploadFile($request->file('fortuneCompany_thumbnail'), 'business_individuals', $existingData['fortuneCompany']['thumbnail'] ?? null)
                        : ($existingData['fortuneCompany']['thumbnail'] ?? ''),
                    'company' => $this->prepareCompany(
                        $request->file('company_thumbnails', []),
                        'business_individuals',
                        $existingData['fortuneCompany']['company'] ?? []
                    ),
                ],
                'corporateTraining' => [
                    'title' => $request->input('corporateTraining_title', $existingData['corporateTraining']['title'] ?? ''),
                    'subtitle' => $request->input('corporateTraining_subtitle', $existingData['corporateTraining']['subtitle'] ?? ''),
                    'subtitle1' => $request->input('corporateTraining_subtitle1', $existingData['corporateTraining']['subtitle1'] ?? ''),
                    'subtitle2' => $request->input('corporateTraining_subtitle2', $existingData['corporateTraining']['subtitle2'] ?? ''),
                    'subtitle3' => $request->input('corporateTraining_subtitle3', $existingData['corporateTraining']['subtitle3'] ?? ''),
                    'subtitle4' => $request->input('corporateTraining_subtitle4', $existingData['corporateTraining']['subtitle4'] ?? ''),
                ],
            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Corporate Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'insights_career_page') {
            $dynamicPage = DynamicPage::where('key', 'insights_career_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $existingThumbnails = isset($existingData['thumbnail']) && is_array($existingData['thumbnail'])
                ? $existingData['thumbnail']
                : [];

            // 1. Get removed thumbnails from the form
            $removedThumbnails = json_decode($request->input('removed_thumbnails', '[]'), true);

            // 2. Filter out removed thumbnails
            $updatedThumbnails = array_filter($existingThumbnails, function ($thumb) use ($removedThumbnails) {
                return !in_array($thumb, $removedThumbnails);
            });

            // 3. Delete removed thumbnail files from disk
            foreach ($removedThumbnails as $thumbToDelete) {
                $fullPath = public_path($thumbToDelete);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // 4. Process new uploads
            $newThumbnails = [];
            if ($request->hasFile('thumbnail')) {
                foreach ($request->file('thumbnail') as $file) {
                    $uploadedPath = $this->uploadFile($file, 'insights_career_page');
                    $newThumbnails[] = $uploadedPath;
                }
            }

            // 5. Final thumbnail list (existing - removed + new)
            $finalThumbnail = array_merge($updatedThumbnails, $newThumbnails);

            // 6. Save back to database
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'thumbnail' => $finalThumbnail,

                'aboutCapital' => [
                    'title' => $request->input('aboutCapital_title', $existingData['aboutCapital']['title'] ?? ''),
                    'subtitle' => $request->input('aboutCapital_subtitle', $existingData['aboutCapital']['subtitle'] ?? []),
                    // 'subtitle' => array_filter(
                    //     $request->input('aboutCapital_subtitle', $existingData['aboutCapital']['subtitle'] ?? []),
                    //     fn($value) => !is_null($value) && trim($value) !== ''
                    // ),
                    'thumbnail' => $request->hasFile('aboutCapital_thumbnail')
                        ? $this->uploadFile($request->file('aboutCapital_thumbnail'), 'insights_career_page', $existingData['aboutCapital']['thumbnail'] ?? null)
                        : ($existingData['aboutCapital']['thumbnail'] ?? ''),
                ],
                'employeeBenefits' => [
                    'title' => $request->input('employeeBenefits_title', $existingData['employeeBenefits']['title'] ?? ''),
                    'features' => $this->prepareFeatures(
                        $request->input('titles', []),
                        null,
                        $request->file('logos', []),
                        "insights_career_page",
                        $existingData['employeeBenefits']['features'] ?? []
                    ),

                ],
                'careers' => [
                    'title' => $request->input('careers_title', $existingData['careers']['title'] ?? ''),
                    'subtitle' => $request->input('careers_subtitle', $existingData['careers']['subtitle'] ?? ''),
                    'careers_goal' => $this->prepareCareers_goal(
                        $request->input('names', []),
                        $request->input('descriptions', []),
                        $request->input('durations', []),
                        $request->input('times', []),
                    ),
                ],

            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Insights career Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'partnership_page') {
            $dynamicPage = DynamicPage::where('key', 'partnership_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];


            // 6. Save back to database
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('subtitle', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'partnership_page', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),
                'active_students' => $request->input('active_students', $existingData['active_students'] ?? ''),
                'students_percentage' => $request->input('students_percentage', $existingData['students_percentage'] ?? ''),
                'professionalChoose' => [
                    'title' => $request->input('professionalChoose_title', $existingData['professionalChoose']['title'] ?? ''),
                    // 'subtitle' => $request->input('professionalChoose_subtitle', $existingData['professionalChoose']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('professionalChoose_subtitle', $existingData['professionalChoose']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),

                    'thumbnail' => $request->hasFile('professionalChoose_thumbnail')
                        ? $this->uploadFile($request->file('professionalChoose_thumbnail'), 'partnership_page', $existingData['professionalChoose']['thumbnail'] ?? null)
                        : ($existingData['professionalChoose']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('professionalChoose_thumbnail_1')
                        ? $this->uploadFile($request->file('professionalChoose_thumbnail_1'), 'partnership_page', $existingData['professionalChoose']['thumbnail_1'] ?? null)
                        : ($existingData['professionalChoose']['thumbnail_1'] ?? ''),
                ],
                'partnershipOppor' => [
                    'title' => $request->input('partnershipOppor_title', $existingData['partnershipOppor']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('partnershipOppor_thumbnail')
                        ? $this->uploadFile($request->file('partnershipOppor_thumbnail'), 'partnership_page', $existingData['partnershipOppor']['thumbnail'] ?? null)
                        : ($existingData['partnershipOppor']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('partnershipOppor_thumbnail_1')
                        ? $this->uploadFile($request->file('partnershipOppor_thumbnail_1'), 'partnership_page', $existingData['partnershipOppor']['thumbnail_1'] ?? null)
                        : ($existingData['partnershipOppor']['thumbnail_1'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('titles', []),
                        $request->input('descriptions', []),
                        $request->input('l_backs_text', []),
                        $request->input('d_backs_text', []),
                        $request->file('logos', []),
                        "partnership_page",
                        $existingData['partnershipOppor']['features'] ?? []
                    ),

                ],
                'successStories' => [
                    'title' => $request->input('successStories_title', $existingData['successStories']['title'] ?? ''),
                    'subtitle' => $request->input('successStories_subtitle', $existingData['successStories']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('successStories_thumbnail')
                        ? $this->uploadFile($request->file('successStories_thumbnail'), 'partnership_page', $existingData['successStories']['thumbnail'] ?? null)
                        : ($existingData['successStories']['thumbnail'] ?? ''),
                    'stories' => $this->preparestories(
                        $request->input('names', []),
                        $request->input('storiesdescriptions', []),
                        $request->input('institutions', []),
                        $request->file('storiesthumbnails', []),
                        "partnership_page",
                        $existingData['successStories']['stories'] ?? []
                    ),
                ],

            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Partnership Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'full_code_of_ethics_page') {
            $dynamicPage = DynamicPage::where('key', 'full_code_of_ethics_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];


            // 6. Save back to database
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('subtitle', $existingData['subtitle'] ?? ''),
                // 'thumbnail' => $request->hasFile('thumbnail')
                //     ? $this->uploadFile($request->file('thumbnail'), 'ethics_page', $existingData['thumbnail'] ?? null)
                //     : ($existingData['thumbnail'] ?? ''),
                // 'active_students' => $request->input('active_students', $existingData['active_students'] ?? ''),
                // 'students_percentage' => $request->input('students_percentage', $existingData['students_percentage'] ?? ''),

                'coreEthics' => [
                    'title' => $request->input('coreEthics_title', $existingData['coreEthics']['title'] ?? ''),
                    'subtitle' => $request->input('coreEthics_subtitle', $existingData['coreEthics']['subtitle'] ?? []),
                    // 'subtitle' => array_filter(
                    //     $request->input('coreEthics_subtitle', $existingData['coreEthics']['subtitle'] ?? []),
                    //     fn($value) => !is_null($value) && trim($value) !== ''
                    // ),
                    'thumbnail' => $request->hasFile('coreEthics_thumbnail')
                        ? $this->uploadFile($request->file('coreEthics_thumbnail'), 'ethics_page', $existingData['coreEthics']['thumbnail'] ?? null)
                        : ($existingData['coreEthics']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('coreEthics_thumbnail_1')
                        ? $this->uploadFile($request->file('coreEthics_thumbnail_1'), 'ethics_page', $existingData['coreEthics']['thumbnail_1'] ?? null)
                        : ($existingData['coreEthics']['thumbnail_1'] ?? ''),
                    'features' => $this->prepareCoreEthicsFeatures(
                        $request->input('titles', []),
                        $request->input('subtitles', [])
                    ),

                ],
                'memberObligation' => [
                    'title' => $request->input('memberObligation_title', $existingData['memberObligation']['title'] ?? ''),
                    'subtitle' => $request->input('memberObligation_subtitle', $existingData['memberObligation']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('memberObligation_thumbnail')
                        ? $this->uploadFile($request->file('memberObligation_thumbnail'), 'ethics_page', $existingData['memberObligation']['thumbnail'] ?? null)
                        : ($existingData['memberObligation']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('memberObligation_thumbnail_1')
                        ? $this->uploadFile($request->file('memberObligation_thumbnail_1'), 'ethics_page', $existingData['memberObligation']['thumbnail_1'] ?? null)
                        : ($existingData['memberObligation']['thumbnail_1'] ?? ''),
                    'features' => array_filter(
                        $request->input('memberObligation_features', $existingData['memberObligation']['features'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),
                ],
                'enforcement' => [
                    'title' => $request->input('enforcement_title', $existingData['enforcement']['title'] ?? ''),
                    'subtitle_1' => $request->input('enforcement_subtitle_1', $existingData['enforcement']['subtitle_1'] ?? ''),
                    'subtitle_2' => $request->input('enforcement_subtitle_2', $existingData['enforcement']['subtitle_2'] ?? ''),
                    'thumbnail' => $request->hasFile('enforcement_thumbnail')
                        ? $this->uploadFile($request->file('enforcement_thumbnail'), 'ethics_page', $existingData['enforcement']['thumbnail'] ?? null)
                        : ($existingData['enforcement']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('enforcement_thumbnail_1')
                        ? $this->uploadFile($request->file('enforcement_thumbnail_1'), 'ethics_page', $existingData['enforcement']['thumbnail_1'] ?? null)
                        : ($existingData['enforcement']['thumbnail_1'] ?? ''),
                    'features' => array_filter(
                        $request->input('enforcement_features', $existingData['enforcement']['features'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),
                ],

            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Ethics Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'professional_conduct_page') {
            $dynamicPage = DynamicPage::where('key', 'professional_conduct_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];


            // 6. Save back to database
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('subtitle', $existingData['subtitle'] ?? ''),
                'reliability' => [
                    'title' => $request->input('reliability_title', $existingData['reliability']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('reliability_thumbnail')
                        ? $this->uploadFile($request->file('reliability_thumbnail'), 'professional_conduct_page', $existingData['reliability']['thumbnail'] ?? null)
                        : ($existingData['reliability']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('titles', []),
                        $request->input('descriptions', []),
                        $request->input('l_backs_text', []),
                        $request->input('d_backs_text', []),
                        $request->file('logos', []),
                        "professional_conduct_page",
                        $existingData['reliability']['features'] ?? []
                    ),
                ],
                'professional' => [
                    'title' => $request->input('professional_title', $existingData['professional']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('professional_thumbnail')
                        ? $this->uploadFile($request->file('professional_thumbnail'), 'professional_conduct_page', $existingData['professional']['thumbnail'] ?? null)
                        : ($existingData['professional']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('professionaltitles', []),
                        $request->input('professionaldescriptions', []),
                        $request->input('professionall_backs_text', []),
                        $request->input('professionald_backs_text', []),
                        $request->file('professionallogos', []),
                        "professional_conduct_page",
                        $existingData['professional']['features'] ?? []
                    ),
                ],
                'obligation' => [
                    'title' => $request->input('obligation_title', $existingData['obligation']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('obligation_thumbnail')
                        ? $this->uploadFile($request->file('obligation_thumbnail'), 'professional_conduct_page', $existingData['obligation']['thumbnail'] ?? null)
                        : ($existingData['obligation']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('obligationtitles', []),
                        $request->input('obligationdescriptions', []),
                        $request->input('obligationl_backs_text', []),
                        $request->input('obligationd_backs_text', []),
                        $request->file('obligationlogos', []),
                        "professional_conduct_page",
                        $existingData['obligation']['features'] ?? []
                    ),
                ],
                'obligationToEmployee' => [
                    'title' => $request->input('obligationToEmployee_title', $existingData['obligationToEmployee']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('obligationToEmployee_thumbnail')
                        ? $this->uploadFile($request->file('obligationToEmployee_thumbnail'), 'professional_conduct_page', $existingData['obligationToEmployee']['thumbnail'] ?? null)
                        : ($existingData['obligationToEmployee']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('obligationToEmployeetitles', []),
                        $request->input('obligationToEmployeedescriptions', []),
                        $request->input('obligationToEmployeel_backs_text', []),
                        $request->input('obligationToEmployeed_backs_text', []),
                        $request->file('obligationToEmployeelogos', []),
                        "professional_conduct_page",
                        $existingData['obligationToEmployee']['features'] ?? []
                    ),
                ],
                'investment' => [
                    'title' => $request->input('investment_title', $existingData['investment']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('investment_thumbnail')
                        ? $this->uploadFile($request->file('investment_thumbnail'), 'professional_conduct_page', $existingData['investment']['thumbnail'] ?? null)
                        : ($existingData['investment']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('investmenttitles', []),
                        $request->input('investmentdescriptions', []),
                        $request->input('investmentl_backs_text', []),
                        $request->input('investmentd_backs_text', []),
                        $request->file('investmentlogos', []),
                        "professional_conduct_page",
                        $existingData['investment']['features'] ?? []
                    ),
                ],
                'interest' => [
                    'title' => $request->input('interest_title', $existingData['interest']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('interest_thumbnail')
                        ? $this->uploadFile($request->file('interest_thumbnail'), 'professional_conduct_page', $existingData['interest']['thumbnail'] ?? null)
                        : ($existingData['interest']['thumbnail'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('interesttitles', []),
                        $request->input('interestdescriptions', []),
                        $request->input('interestl_backs_text', []),
                        $request->input('interestd_backs_text', []),
                        $request->file('interestlogos', []),
                        "professional_conduct_page",
                        $existingData['interest']['features'] ?? []
                    ),
                ],
                'enforcement' => [
                    'title' => $request->input('enforcement_title', $existingData['enforcement']['title'] ?? ''),
                    'subtitle' => $request->input('enforcement_subtitle', $existingData['enforcement']['subtitle'] ?? ''),
                    'thumbnail' => $request->hasFile('enforcement_thumbnail')
                        ? $this->uploadFile($request->file('enforcement_thumbnail'), 'professional_conduct_page', $existingData['enforcement']['thumbnail'] ?? null)
                        : ($existingData['enforcement']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('enforcement_thumbnail_1')
                        ? $this->uploadFile($request->file('enforcement_thumbnail_1'), 'professional_conduct_page', $existingData['enforcement']['thumbnail_1'] ?? null)
                        : ($existingData['enforcement']['thumbnail_1'] ?? ''),

                ],

            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Professional Conduct Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'voluentry_community_page') {
            $dynamicPage = DynamicPage::where('key', 'voluentry_community_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $existingThumbnails = isset($existingData['thumbnail']) && is_array($existingData['thumbnail'])
                ? $existingData['thumbnail']
                : [];

            // 1. Get removed thumbnails from the form
            $removedThumbnails = json_decode($request->input('removed_thumbnails', '[]'), true);

            // 2. Filter out removed thumbnails
            $updatedThumbnails = array_filter($existingThumbnails, function ($thumb) use ($removedThumbnails) {
                return !in_array($thumb, $removedThumbnails);
            });

            // 3. Delete removed thumbnail files from disk
            foreach ($removedThumbnails as $thumbToDelete) {
                $fullPath = public_path($thumbToDelete);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // 4. Process new uploads
            $newThumbnails = [];
            if ($request->hasFile('thumbnail')) {
                foreach ($request->file('thumbnail') as $file) {
                    $uploadedPath = $this->uploadFile($file, 'voluentry_comunity_page');
                    $newThumbnails[] = $uploadedPath;
                }
            }

            // 5. Final thumbnail list (existing - removed + new)
            $finalThumbnail = array_merge($updatedThumbnails, $newThumbnails);
            // 6. Save back to database
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('subtitle', $existingData['subtitle'] ?? ''),
                'thumbnail' => $finalThumbnail,
                'voluentry_impect' => [
                    'title' => $request->input('voluentry_impect_title', $existingData['voluentry_impect']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('voluentry_impect_thumbnail')
                        ? $this->uploadFile($request->file('voluentry_impect_thumbnail'), 'voluentry_comunity_page', $existingData['voluentry_impect']['thumbnail'] ?? null)
                        : ($existingData['voluentry_impect']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('voluentry_impect_thumbnail_1')
                        ? $this->uploadFile($request->file('voluentry_impect_thumbnail_1'), 'voluentry_comunity_page', $existingData['voluentry_impect']['thumbnail_1'] ?? null)
                        : ($existingData['voluentry_impect']['thumbnail_1'] ?? ''),
                    'features' => $this->prepareFeaturesColor(
                        $request->input('titles', []),
                        $request->input('descriptions', []),
                        $request->input('l_backs_text', []),
                        $request->input('d_backs_text', []),
                        $request->file('logos', []),
                        "voluentry_comunity_page",
                        $existingData['voluentry_impect']['features'] ?? []
                    ),
                ],
            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Voluentry Community Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'business_student_page') {
            $dynamicPage = DynamicPage::where('key', 'business_student_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'business_individuals', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),
                'thumbnail_video' => $request->hasFile('thumbnail_video')
                    ? $this->uploadFile($request->file('thumbnail_video'), 'business_individuals', $existingData['thumbnail_video'] ?? null)
                    : ($existingData['thumbnail_video'] ?? ''),
                'whyStudentGet' => [
                    'title' => $request->input('whyStudentGet_title', $existingData['whyStudentGet']['title'] ?? ''),
                    // 'subtitle' => $request->input('whyStudentGet_subtitle', $existingData['whyStudentGet']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('whyStudentGet_subtitle', $existingData['whyStudentGet']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),

                    'thumbnail' => $request->hasFile('whyStudentGet_thumbnail')
                        ? $this->uploadFile($request->file('whyStudentGet_thumbnail'), 'business_individuals', $existingData['whyStudentGet']['thumbnail'] ?? null)
                        : ($existingData['whyStudentGet']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('whyStudentGet_thumbnail_1')
                        ? $this->uploadFile($request->file('whyStudentGet_thumbnail_1'), 'business_individuals', $existingData['whyStudentGet']['thumbnail_1'] ?? null)
                        : ($existingData['whyStudentGet']['thumbnail_1'] ?? ''),
                ],
                'learningOpportunities' => [
                    'title' => $request->input('learningOpportunities_title', $existingData['learningOpportunities']['title'] ?? ''),
                    'subtitle1' => $request->input('learningOpportunities_subtitle1', $existingData['learningOpportunities']['subtitle1'] ?? ''),
                    'subtitle2' => $request->input('learningOpportunities_subtitle2', $existingData['learningOpportunities']['subtitle2'] ?? ''),
                    'subtitle3' => $request->input('learningOpportunities_subtitle3', $existingData['learningOpportunities']['subtitle3'] ?? ''),
                    'subtitle4' => $request->input('learningOpportunities_subtitle4', $existingData['learningOpportunities']['subtitle4'] ?? ''),
                ],
                'increased' => [
                    'percentage' => $request->input('increased_percentage', $existingData['increased']['percentage'] ?? ''),
                    'title' => $request->input('increased_title', $existingData['increased']['title'] ?? ''),
                    'subtitle' => $request->input('increased_subtitle', $existingData['increased']['subtitle'] ?? ''),
                ],
                'improved' => [
                    'percentage' => $request->input('improved_percentage', $existingData['improved']['percentage'] ?? ''),
                    'title' => $request->input('improved_title', $existingData['improved']['title'] ?? ''),
                    'subtitle' => $request->input('improved_subtitle', $existingData['improved']['subtitle'] ?? ''),
                ],
                'fortuneCompany' => [
                    'title' => $request->input('fortuneCompany_title', $existingData['fortuneCompany']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('fortuneCompany_thumbnail')
                        ? $this->uploadFile($request->file('fortuneCompany_thumbnail'), 'business_individuals', $existingData['fortuneCompany']['thumbnail'] ?? null)
                        : ($existingData['fortuneCompany']['thumbnail'] ?? ''),
                    'company' => $this->prepareCompany(
                        $request->file('company_thumbnails', []),
                        'business_individuals',
                        $existingData['fortuneCompany']['company'] ?? []
                    ),
                ],
            ];

            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);

            // Flash success message
            Session::flash('success', get_phrase('Business Student Page updated successfully'));
            return redirect()->back();
        }
        if ($request->type == 'business_university_page') {
            $dynamicPage = DynamicPage::where('key', 'business_university_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'business_individuals', $existingData['thumbnail'] ?? null)
                    : ($existingData['thumbnail'] ?? ''),
                'thumbnail_1' => $request->hasFile('thumbnail_1')
                    ? $this->uploadFile($request->file('thumbnail_1'), 'business_individuals', $existingData['thumbnail_1'] ?? null)
                    : ($existingData['thumbnail_1'] ?? ''),
                'thumbnail_video' => $request->hasFile('thumbnail_video')
                    ? $this->uploadFile($request->file('thumbnail_video'), 'business_individuals', $existingData['thumbnail_video'] ?? null)
                    : ($existingData['thumbnail_video'] ?? ''),
                'name' => $request->input('name', $existingData['name'] ?? ''),
                'experience' => $request->input('experience', $existingData['experience'] ?? ''),
                'offerUniversity' => [
                    'title' => $request->input('offerUniversity_title', $existingData['offerUniversity']['title'] ?? ''),
                    // 'subtitle' => $request->input('offerUniversity_subtitle', $existingData['offerUniversity']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('offerUniversity_subtitle', $existingData['offerUniversity']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),
                    'thumbnail' => $request->hasFile('offerUniversity_thumbnail')
                        ? $this->uploadFile($request->file('offerUniversity_thumbnail'), 'business_individuals', $existingData['offerUniversity']['thumbnail'] ?? null)
                        : ($existingData['offerUniversity']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('offerUniversity_thumbnail_1')
                        ? $this->uploadFile($request->file('offerUniversity_thumbnail_1'), 'business_individuals', $existingData['offerUniversity']['thumbnail_1'] ?? null)
                        : ($existingData['offerUniversity']['thumbnail_1'] ?? ''),
                ],
                'collaboration' => [
                    'title' => $request->input('collaboration_title', $existingData['collaboration']['title'] ?? ''),
                    // 'subtitle' => $request->input('collaboration_subtitle', $existingData['collaboration']['subtitle'] ?? []),
                    'subtitle' => array_filter(
                        $request->input('collaboration_subtitle', $existingData['collaboration']['subtitle'] ?? []),
                        fn($value) => !is_null($value) && trim($value) !== ''
                    ),
                    'thumbnail' => $request->hasFile('collaboration_thumbnail')
                        ? $this->uploadFile($request->file('collaboration_thumbnail'), 'business_individuals', $existingData['collaboration']['thumbnail'] ?? null)
                        : ($existingData['collaboration']['thumbnail'] ?? ''),
                    'thumbnail_1' => $request->hasFile('collaboration_thumbnail_1')
                        ? $this->uploadFile($request->file('collaboration_thumbnail_1'), 'business_individuals', $existingData['collaboration']['thumbnail_1'] ?? null)
                        : ($existingData['collaboration']['thumbnail_1'] ?? ''),
                ],
                'increased' => [
                    'percentage' => $request->input('increased_percentage', $existingData['increased']['percentage'] ?? ''),
                    'title' => $request->input('increased_title', $existingData['increased']['title'] ?? ''),
                    'subtitle' => $request->input('increased_subtitle', $existingData['increased']['subtitle'] ?? ''),
                ],
                'improved' => [
                    'percentage' => $request->input('improved_percentage', $existingData['improved']['percentage'] ?? ''),
                    'title' => $request->input('improved_title', $existingData['improved']['title'] ?? ''),
                    'subtitle' => $request->input('improved_subtitle', $existingData['improved']['subtitle'] ?? ''),
                ],
                'fortuneCompany' => [
                    'title' => $request->input('fortuneCompany_title', $existingData['fortuneCompany']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('fortuneCompany_thumbnail')
                        ? $this->uploadFile($request->file('fortuneCompany_thumbnail'), 'business_individuals', $existingData['fortuneCompany']['thumbnail'] ?? null)
                        : ($existingData['fortuneCompany']['thumbnail'] ?? ''),
                    'company' => $this->prepareCompany(
                        $request->file('company_thumbnails', []),
                        'business_individuals',
                        $existingData['fortuneCompany']['company'] ?? []
                    ),
                ],
            ];
            // Update the database
            $dynamicPage->update(['value' => json_encode($data)]);
            // Flash success message
            Session::flash('success', get_phrase('Business University Page updated successfully'));
            return redirect()->back();
        }
    }


    // Helper function to handle file uploads

    private function uploadFile($file, $folder, $oldFilePath = null)
    {
        if ($file) {
            // Delete old file if it exists
            if ($oldFilePath) {
                $fullOldPath = public_path($oldFilePath);
                if (file_exists($fullOldPath)) {
                    @unlink($fullOldPath);
                }
            }

            $uploadPath = "uploads/dynamic_pages/{$folder}";
            $uploadedFile = FileUploader::upload($file, $uploadPath, 500); // Adjust resize width if needed

            return "{$uploadPath}/" . basename($uploadedFile);
        }

        return null;
    }



    // Helper function to prepare features array

    private function prepareFeatures($titles, $descriptions, $logos = null, $folder = null, $previousFeatures = [])
    {
        $features = [];

        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['title'] = $title;

            // Only add description if provided or previously exists
            $existingDescription = $descriptions[$index] ?? $previousFeatures[$index]['description'] ?? null;
            if (!is_null($existingDescription) && $existingDescription !== '') {
                $features[$index]['description'] = $existingDescription;
            }

            // Handle logo if new logo uploaded
            if (is_array($logos) && isset($logos[$index]) && $logos[$index]) {
                // Delete old logo if exists
                if (!empty($previousFeatures[$index]['logo'])) {
                    $oldPath = public_path($previousFeatures[$index]['logo']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $uploadedPath = FileUploader::upload($logos[$index], "uploads/dynamic_pages/{$folder}", 500);
                $features[$index]['logo'] = "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath);
            } elseif (!empty($previousFeatures[$index]['logo'])) {
                // Only add logo if previous logo exists
                $features[$index]['logo'] = $previousFeatures[$index]['logo'];
            }
        }

        return $features;
    }
    private function prepareFeaturesColor($titles, $descriptions, $l_backs_text, $d_backs_text, $logos = null, $folder = null, $previousFeatures = [])
    {
        $features = [];

        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['title'] = $title;

            // Only add description if provided or previously exists
            $existingDescription = $descriptions[$index] ?? $previousFeatures[$index]['description'] ?? null;
            if (!is_null($existingDescription) && $existingDescription !== '') {
                $features[$index]['description'] = $existingDescription;
            }
            $existingl_back = $l_backs_text[$index] ?? $previousFeatures[$index]['l_back'] ?? null;
            if (!is_null($existingl_back) && $existingl_back !== '') {
                $features[$index]['l_back'] = $existingl_back;
            }
            $existingd_back = $d_backs_text[$index] ?? $previousFeatures[$index]['d_back'] ?? null;
            if (!is_null($existingd_back) && $existingd_back !== '') {
                $features[$index]['d_back'] = $existingd_back;
            }

            // Handle logo if new logo uploaded
            if (is_array($logos) && isset($logos[$index]) && $logos[$index]) {
                // Delete old logo if exists
                if (!empty($previousFeatures[$index]['logo'])) {
                    $oldPath = public_path($previousFeatures[$index]['logo']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $uploadedPath = FileUploader::upload($logos[$index], "uploads/dynamic_pages/{$folder}", 500);
                $features[$index]['logo'] = "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath);
            } elseif (!empty($previousFeatures[$index]['logo'])) {
                // Only add logo if previous logo exists
                $features[$index]['logo'] = $previousFeatures[$index]['logo'];
            }
        }

        return $features;
    }
    private function preparestories($titles, $descriptions, $institutions, $logos = null, $folder = null, $previousFeatures = [])
    {
        $features = [];

        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['name'] = $title;

            // Only add description if provided or previously exists
            $existingDescription = $descriptions[$index] ?? $previousFeatures[$index]['description'] ?? null;
            if (!is_null($existingDescription) && $existingDescription !== '') {
                $features[$index]['description'] = $existingDescription;
            }
            $existingl_back = $institutions[$index] ?? $previousFeatures[$index]['institution'] ?? null;
            if (!is_null($existingl_back) && $existingl_back !== '') {
                $features[$index]['institution'] = $existingl_back;
            }
            // Handle logo if new logo uploaded
            if (is_array($logos) && isset($logos[$index]) && $logos[$index]) {
                // Delete old logo if exists
                if (!empty($previousFeatures[$index]['thumbnail'])) {
                    $oldPath = public_path($previousFeatures[$index]['thumbnail']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $uploadedPath = FileUploader::upload($logos[$index], "uploads/dynamic_pages/{$folder}", 500);
                $features[$index]['thumbnail'] = "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath);
            } elseif (!empty($previousFeatures[$index]['thumbnail'])) {
                // Only add t if previous t exists
                $features[$index]['thumbnail'] = $previousFeatures[$index]['thumbnail'];
            }
        }

        return $features;
    }
    private function prepareCoreEthicsFeatures($titles, $subtitles)
    {
        $features = [];

        foreach ($titles as $index => $title) {
            if (trim($title) === '')
                continue;

            $features[] = [
                'title' => $title,
                'subtitle' => array_filter($subtitles[$index] ?? []), // safe even if empty
            ];
        }

        return $features;
    }

    private function prepareFeatures1($titles, $descriptions, $logos = null, $folder = null, $previousFeatures = [])
    {
        $features = [];

        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['title'] = $title;
            $features[$index]['description'] = $descriptions[$index] ?? '';

            // Check if a new logo is uploaded at this index
            if (is_array($logos) && isset($logos[$index]) && $logos[$index]) {
                // Delete previous logo if it exists
                if (!empty($previousFeatures[$index]['logo'])) {
                    $oldPath = public_path($previousFeatures[$index]['logo']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                // Upload new logo
                $uploadedPath = FileUploader::upload($logos[$index], "uploads/dynamic_pages/{$folder}", 500);
                $features[$index]['logo'] = "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath);
            } else {
                // Keep the old logo if no new one was uploaded
                // $features[$index]['logo'] = $previousFeatures[$index]['logo'] ?? null;
            }
        }

        return $features;
    }

    private function prepareCareers_goal($titles, $descriptions, $durations, $times)
    {
        $features = [];

        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['name'] = $title;
            $features[$index]['description'] = $descriptions[$index] ?? '';
            $features[$index]['duration'] = $durations[$index] ?? '';
            $features[$index]['time'] = $times[$index] ?? '';

        }

        return $features;
    }

    private function prepareCompany($logos, $folder = null, $previousFeatures = [])
    {
        $features = [];

        foreach ($previousFeatures as $index => $feature) {
            $features[$index]['thumbnail'] = $feature['thumbnail'] ?? null;

            // Update thumbnail if new file is uploaded at this index
            if (is_array($logos) && isset($logos[$index]) && $logos[$index]->isValid()) {
                // Delete old file if it exists
                if (!empty($feature['thumbnail'])) {
                    $oldPath = public_path($feature['thumbnail']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                // Upload and replace with new thumbnail
                $uploadedPath = FileUploader::upload($logos[$index], "uploads/dynamic_pages/{$folder}", 500);
                $features[$index]['thumbnail'] = "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath);
            }
        }

        // Handle newly added logos (beyond existing indexes)
        foreach ($logos as $index => $logo) {
            if (!isset($features[$index]) && $logo && $logo->isValid()) {
                $uploadedPath = FileUploader::upload($logo, "uploads/dynamic_pages/{$folder}", 500);
                $features[$index] = [
                    'thumbnail' => "uploads/dynamic_pages/{$folder}/" . basename($uploadedPath),
                ];
            }
        }

        return array_values($features); // Re-index the array to be zero-based
    }


}

//   if ($request->type == 'motivational_speech') {
//             array_shift($data);

//             $motivations = array();
//             $images = array();
//             foreach (array_filter($data['titles']) as $key => $title) {
//                 $motivations[$key]['title'] = $title;
//                 $motivations[$key]['designation'] = $data['designation'][$key];
//                 $motivations[$key]['description'] = $data['descriptions'][$key];

//                 if ($_FILES['images']['name'][$key] != "") {
//                     $motivations[$key]['image'] = FileUploader::upload($request->images[$key], "uploads/motivational_speech", 500);
//                 } else {
//                     $motivations[$key]['image'] = $data['previous_images'][$key];
//                 }
//                 $images[$key] = $motivations[$key]['image'];
//             }
//             $files = glob('uploads/motivational_speech/' . '*');
//             foreach ($files as $file) {
//                 $file_name_arr = explode('/', $file);
//                 $file_name = end($file_name_arr);
//                 if (!in_array($file_name, $images)) {
//                     remove_file($file);
//                 }
//             }
//             $data['value'] = json_encode($motivations);
//             DynamicPage::where('key', 'motivational_speech')->update(['value' => $data['value']]);
//             Session::flash('success', get_phrase('Motivational speech update successfully'));
//         }
//         if ($request->type == 'websitefaqs') {
//             array_shift($data);

//             $faqs = array();
//             foreach (array_filter($data['questions']) as $key => $question) {
//                 $faqs[$key]['question'] = $question;
//                 $faqs[$key]['answer'] = $data['answers'][$key];
//             }

//             $data['value'] = json_encode($faqs);
//             $faq = $data['value'];
//             DynamicPage::where('key', 'website_faqs')->update(['value' => $faq]);
//             Session::flash('success', get_phrase('Website Faqs update successfully'));
//         }
//         if ($request->type == 'contact_info') {

//             array_shift($data);
//             $contact_information = json_encode($data);
//             $row = DynamicPage::where('key', 'contact_info')->get();
//             if ($row->count() > 0) {
//                 DynamicPage::where('key', 'contact_info')->update(['value' => $contact_information]);
//             } else {
//                 DynamicPage::where('key', 'contact_info')->update(['value' => $contact_information]);
//             }
//             Session::flash('success', get_phrase('Contact information update successfully'));
//         }

//         if ($request->type == 'recaptcha_settings') {
//             array_shift($data);

//             DynamicPage::where('key', 'recaptcha_status')->update(['value' => $data['recaptcha_status']]);
//             DynamicPage::where('key', 'recaptcha_sitekey')->update(['value' => $data['recaptcha_sitekey']]);
//             DynamicPage::where('key', 'recaptcha_secretkey')->update(['value' => $data['recaptcha_secretkey']]);

//             Session::flash('success', get_phrase('Recaptcha setting update successfully'));
//         }

//         if ($request->type == 'banner_image') {
//             array_shift($data);

//             if (isset($request->banner_image) && $request->banner_image != '') {

//                 $banner = $request->banner_image->extension();

//                 $data = "uploads/banner_image/" . nice_file_name('banner_image', $banner);
//                 FileUploader::upload($request->banner_image, $data);

//                 if (get_frontend_settings('home_page')) {
//                     $active_banner = array(
//                         get_frontend_settings('home_page') => $data
//                     );
//                     DynamicPage::where('key', $request->type)->update(['value' => json_encode($active_banner)]);
//                 } else {
//                     DynamicPage::where('key', $request->type)->update(['value' => $data]);
//                 }

//                 Session::flash('success', get_phrase('Banner image update successfully'));
//             }
//         }
//         if ($request->type == 'footer_video' || $request->type == 'banner_video' || $request->type == 'home_page_body_video') {
//             if (isset($request->{$request->type}) && $request->{$request->type} != '') {
//                 $video = $request->{$request->type}->extension();

//                 // Example filename: uploads/frontend_videos/footer_video.mp4
//                 $video_path = "uploads/frontend_videos/" . nice_file_name($request->type, $video);

//                 FileUploader::upload($request->{$request->type}, $video_path);

//                 // Save in frontend settings
//                 DynamicPage::where('key', $request->type)->update(['value' => $video_path]);

//                 Session::flash('success', get_phrase(ucwords(str_replace('_', ' ', $request->type)) . ' updated successfully'));
//             }
//         }

//         if ($request->type == 'light_logo') {
//             array_shift($data);

//             if (isset($request->light_logo) && $request->light_logo != '') {

//                 $data = "uploads/light_logo/" . nice_file_name('light_logo', $request->light_logo->extension());
//                 FileUploader::upload($request->light_logo, $data, 400, null, 200, 200);

//                 DynamicPage::where('key', $request->type)->update(['value' => $data]);
//                 Session::flash('success', get_phrase('Light logo update successfully'));
//             }
//         }
//         if ($request->type == 'dark_logo') {
//             array_shift($data);

//             if (isset($request->dark_logo) && $request->dark_logo != '') {

//                 $data = "uploads/dark_logo/" . nice_file_name('dark_logo', $request->dark_logo->extension());
//                 FileUploader::upload($request->dark_logo, $data, 400, null, 200, 200);

//                 DynamicPage::where('key', $request->type)->update(['value' => $data]);
//                 Session::flash('success', get_phrase('Dark logo update successfully'));
//             }
//         }
//         if ($request->type == 'small_logo') {
//             array_shift($data);

//             if (isset($request->small_logo) && $request->small_logo != '') {

//                 $data = "uploads/small_logo/" . nice_file_name('small_logo', $request->small_logo->extension());
//                 FileUploader::upload($request->small_logo, $data, 400, null, 200, 200);

//                 DynamicPage::where('key', $request->type)->update(['value' => $data]);
//                 Session::flash('success', get_phrase('Small logo update successfully'));
//             }
//         }
//         if ($request->type == 'favicon') {
//             array_shift($data);

//             if (isset($request->favicon) && $request->favicon != '') {

//                 $data = "uploads/favicon/" . nice_file_name('favicon', $request->favicon->extension());
//                 FileUploader::upload($request->favicon, $data, 400, null, 200, 200);

//                 DynamicPage::where('key', $request->type)->update(['value' => $data]);
//                 Session::flash('success', get_phrase('Favicon logo update successfully'));
//             }
//         }
