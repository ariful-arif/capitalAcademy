<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\MembershipSubscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\FileUploader;

class MembershipController extends Controller
{
    //membership_settings
    public function membership_settings(){
        $page_data = array();
        $page_data['membership_details'] = Membership::first();

        $query = MembershipPackage::query();
        // search filter
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('title', 'LIKE', '%' . $_GET['search'] . '%');
        }
        $page_data['packages'] = $query->where('status', 1)->paginate(10)->appends(request()->query());

        return view("admin.membership.settings", $page_data);
    }

    public function package_create() {
        return view("admin.membership.create");
    }

    public function package_store(Request $request)
    {
        // dd($request->all());
        // die;
        // Validate the required fields
        $validated = $request->validate([
            'title' => 'required|max:255',
            'subtitle_1' => 'required',
            'subtitle_2' => 'required',
            'price' => 'required|numeric|min:1',
            'type' => 'required',
            'period' => 'required|numeric|min:1',
            'feature' => 'array',
            'status' => 'required|in:1,0',
        ]);

        // Prepare the data array
        $data = [
            'title' => $request->title,
            'subtitle_1' => $request->subtitle_1,
            'subtitle_2' => $request->subtitle_2,
            'price' => $request->price,
            'type' => $request->type,
            'period' => $request->period,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ];


        // Ensure features and subfeatures are provided as arrays
        if (!empty($request->feature) && is_array($request->feature) && !empty($request->subfeature) && is_array($request->subfeature)) {
            // Filter out empty or null values from both arrays
            $filtered_features = array_values(array_filter($request->feature, fn($value) => !is_null($value) && $value !== ''));
            $filtered_subfeatures = array_values(array_filter($request->subfeature, fn($value) => !is_null($value) && $value !== ''));

            // Map features and subfeatures to the desired JSON structure
            $features = array_map(function ($feature, $subfeature) {
                return [
                    'title' => $feature,
                    'description' => $subfeature
                ];
            }, $filtered_features, $filtered_subfeatures);

            // Add the JSON encoded features to the data array
            $data['features'] = json_encode($features);
        }

        // Optionally remove unused fields
        unset($data['feature'], $data['subfeature']);

        // Insert the course data
        MembershipPackage::insert($data);

        // Redirect back with success message
        return redirect()->route('admin.membership.settings')->with('success', 'Membership Pacakge added successfully');
    }

    public function package_edit($id)
    {
        // echo $id;
        // die;
        $data['membership_package'] = MembershipPackage::where('id', $id)->first();
        return view('admin.membership.edit', $data);
    }

    public function package_update(Request $request, $id)
    {
        // Validate the required fields
        $validated = $request->validate([
            'title' => 'required|max:255',
            'subtitle_1' => 'required',
            'subtitle_2' => 'required',
            'price' => 'required|numeric|min:1',
            'type' => 'required',
            'period' => 'required|numeric|min:1',
            'feature' => 'array',
            'status' => 'required|in:1,0',
        ]);

        // Find the membership package by ID
        $membershipPackage = MembershipPackage::findOrFail($id);

        // Prepare the data array
        $data = [
            'title' => $request->title,
            'subtitle_1' => $request->subtitle_1,
            'subtitle_2' => $request->subtitle_2,
            'price' => $request->price,
            'type' => $request->type,
            'period' => $request->period,
            'status' => $request->status,
            'updated_at' => now(),
        ];
        
        // Ensure features and subfeatures are provided as arrays
        if (!empty($request->feature) && is_array($request->feature) && !empty($request->subfeature) && is_array($request->subfeature)) {
            // Filter out empty or null values from both arrays
            $filtered_features = array_values(array_filter($request->feature, fn($value) => !is_null($value) && $value !== ''));
            $filtered_subfeatures = array_values(array_filter($request->subfeature, fn($value) => !is_null($value) && $value !== ''));

            // Map features and subfeatures to the desired JSON structure
            $features = array_map(function ($feature, $subfeature) {
                return [
                    'title' => $feature,
                    'description' => $subfeature
                ];
            }, $filtered_features, $filtered_subfeatures);

            // Add the JSON encoded features to the data array
            $data['features'] = json_encode($features);
        }

        // Optionally remove unused fields
        unset($data['feature'], $data['subfeature']);

        // Update the membership package
        $membershipPackage->update($data);

        return redirect()->route('admin.membership.settings')->with('success', 'Membership Pacakge updated successfully');
    }

    public function package_delete($id)
    {
        $membershipPackage = MembershipPackage::findOrFail($id);

        // Delete the package from the database
        $membershipPackage->delete();

        return redirect(route('admin.membership.settings'))->with('success', get_phrase('Package deleted successfully'));
    }

    public function membership_settings_update(Request $request) {

        $rules = [
            'title'       => 'required',
            'subtitle' => 'required',
            'member_count' => 'required',
            'package_section_title' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $msg                 = 'Data updated successfully.';

        $data['title']   = $request->title;
        $data['subtitle'] = $request->subtitle;
        $data['member_count'] = $request->member_count ?? 0;
        $data['package_section_title'] = $request->package_section_title;

        if (isset($request->thumbnail) && $request->thumbnail != '') {

            $query = Membership::first();
            remove_file($query->thumbnail);

            $data['thumbnail'] = "uploads/dynamic_pages/memebership_page/" . nice_file_name($request->title, $request->thumbnail->extension());
            FileUploader::upload($request->thumbnail, $data['thumbnail'], 400, null, 200, 200);
        }

        Membership::where('id', 1)->update($data);
        Session::flash('success', get_phrase($msg));
        return redirect()->back();
    }

    public function membership_subscriptions() 
    {

        $query = MembershipSubscription::query();
        // search filter
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('package_name', 'LIKE', '%' . $_GET['search'] . '%');
        }
        $page_data['subscription_list'] = $query->paginate(20)->appends(request()->query());

        return view("admin.membership.subscription_list", $page_data);
    }
}