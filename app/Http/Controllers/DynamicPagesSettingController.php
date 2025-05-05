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

    public function dynamic_pages_settings_update(Request $request)
    {
        // $data = $request->all();
        // dd($data);
        // die;
        if ($request->type == 'affiliate_page') {
            // Retrieve the existing data
            $dynamicPage = DynamicPage::where('key', 'affiliate_program_page')->first();
            $existingData = $dynamicPage ? json_decode($dynamicPage->value, true) : [];

            // Update the data structure with the new inputs
            $data = [
                'title' => $request->input('title', $existingData['title'] ?? ''),
                'subtitle' => $request->input('sub_title', $existingData['subtitle'] ?? ''),
                'thumbnail' => $request->hasFile('thumbnail')
                    ? $this->uploadFile($request->file('thumbnail'), 'affiliate_page')
                    : ($existingData['thumbnail'] ?? ''),
                'whyPartner' => [
                    'title' => $request->input('why_partner_title', $existingData['whyPartner']['title'] ?? ''),
                    'thumbnail' => $request->hasFile('why_partner_thumbnail')
                        ? $this->uploadFile($request->file('why_partner_thumbnail'), 'affiliate_page')
                        : ($existingData['whyPartner']['thumbnail'] ?? ''),
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
    }


    // Helper function to handle file uploads

    private function uploadFile($file, $folder)
    {
        if ($file) {
            $uploadPath = "uploads/dynamic_pages/{$folder}";
            $uploadedFile = FileUploader::upload($file, $uploadPath, 500); // Adjust width/resize param if needed
    
            // Return full asset URL to match previous logic
            return asset("{$uploadPath}/" . basename($uploadedFile));
        }
    
        return null;
    }
    
  

    // Helper function to prepare features array

    private function prepareFeatures($titles, $descriptions, $logos = null, $folder = null, $previousFeatures = [])
    {
        $features = [];
        $uploadedImages = [];
    
        foreach (array_filter($titles) as $index => $title) {
            $features[$index]['title'] = $title;
            $features[$index]['description'] = $descriptions[$index] ?? '';
    
            // Handle image upload
            if (isset($_FILES['logos']['name'][$index]) && $_FILES['logos']['name'][$index] != '') {
                $features[$index]['logo'] = FileUploader::upload(request()->logos[$index], "uploads/dynamic_pages/{$folder}", 500);
            } else {
                $features[$index]['logo'] = $previousFeatures[$index]['logo'] ?? null;
            }
    
            // Keep track of used images
            $imagePathParts = explode('/', $features[$index]['logo']);
            $uploadedImages[] = end($imagePathParts); // store just the filename
        }
    
        // Clean up unused files
        $files = glob(public_path("uploads/dynamic_pages/{$folder}/*"));
        foreach ($files as $file) {
            $fileName = basename($file);
            if (!in_array($fileName, $uploadedImages)) {
                @unlink($file); // safely remove the file
            }
        }
    
        return $features;
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