<?php

namespace App\Http\Controllers;

use App\Models\FileUploader;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    //
    public function subscription_package(){
        $query = SubscriptionPackage::query();
        // search filter
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('package_name', 'LIKE', '%' . $_GET['search'] . '%');
        }
        $page_data['subscription_package'] = $query->paginate(20)->appends(request()->query());
        // $data['subscription_package'] = SubscriptionPackage::get();
        return view("admin.subscription_package.index",$page_data);
    }

    public function create(){
        return view("admin.subscription_package.create");
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // die;
        // Validate the required fields
        $validated = $request->validate([
            'package_name' => 'required|max:255',
            // 'short_description' => 'required|max:255',
            'package_type' => 'required',
            'subscription_type' => 'required',
            'package_duration' => 'required|numeric|min:1',
            'status' => 'required|in:active,inactive',
            // 'is_paid' => 'required|boolean',
            'price' => 'required_if:is_paid,1|nullable|numeric|min:1',
            'discount_flag' => 'nullable|boolean',
            'discounted_price' => 'required_if:discount_flag,1|nullable|numeric|min:1',
            'info' => 'array',
        ]);

        // Prepare the data array
        $data = [
            'user_id' => auth()->user()->id,
            'package_name' => $request->package_name,
            'short_description' => $request->short_description,
            'subscription_type' => $request->subscription_type,
            'package_type' => $request->package_type,
            'package_duration' => $request->package_duration,
            'status' => $request->status,
            'is_paid' => 1,
            'price' => $request->price,
            'discount_flag' => $request->discount_flag,
            'discounted_price' => $request->discounted_price,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (!empty($request->info) && is_array($request->info)) {
            $filtered_info = array_filter($request->info, fn($value) => !is_null($value) && $value !== '');
            $data['info'] = json_encode(array_values($filtered_info)); // ensures simple array
        }
        

        if ($request->banner) {
            $data['banner'] = "uploads/subscription-package/banner/" . nice_file_name($request->package_name, $request->banner->extension());
            FileUploader::upload($request->banner, $data['banner'], 400, null, 200, 200);
        }

        // Insert the course data
        SubscriptionPackage::insert($data);

        // Redirect back with success message
        return redirect()->route('admin.subscription_package')->with('success', 'Subscription Pacakge added successfully');
    }
    public function edit($id)
    {
        // echo $id;
        // die;
        $data['subscription_package'] = SubscriptionPackage::where('id', $id)->first();
        return view('admin.subscription_package.edit', $data);
    }
    // public function update(Request $request, $id)
    // {
    //     // Validate the required fields
    //     $validated = $request->validate([
    //         'package_name' => 'required|max:255',
    //         // 'short_description' => 'required|max:255',
    //         'package_type' => 'required',
    //         'package_duration' => 'required|numeric|min:1',
    //         'status' => 'required|in:active,pending,draft,private,upcoming,inactive',
    //         // 'is_paid' => 'required|in:0,1',
    //         'price' => 'required_if:is_paid,1|nullable|numeric|min:1',
    //         'discount_flag' => 'nullable|boolean',
    //         'discounted_price' => 'required_if:discount_flag,1|nullable|numeric|min:1',
    //         'info' => 'array',
    //     ]);

    //     // Find the subscription package by ID, or fail if not found
    //     $subscriptionPackage = SubscriptionPackage::findOrFail($id);

    //     // Prepare the data array
    //     $data = [
    //         'package_name' => $request->package_name,
    //         'short_description' => $request->short_description,
    //         'package_type' => $request->package_type,
    //         'package_duration' => $request->package_duration,
    //         'status' => $request->status,
    //         'is_paid' => 1,
    //         'price_id' => $request->price_id,
    //         'price' => $request->price,
    //         'discount_flag' => $request->discount_flag,
    //         'discounted_price' => $request->discount_flag ? $request->discounted_price : null,  // Set discounted price only if discount flag is true
    //         'package_courses' => json_encode($request->course_id),  // Store courses as JSON
    //         'updated_at' => now(),
    //     ];

    //     $data['info'] = json_encode(array_filter($request->info, fn($value) => !is_null($value) && $value !== ''));
    //     // $data['info'] = json_encode(array_filter($request->info, fn ($value) => !is_null($value) && $value !== ''));

    //     // Update the subscription package
    //     $subscriptionPackage->update($data);

    //     // Redirect back with success message
    //     return redirect()->route('admin.subscription_package')->with('success', 'Course updated successfully');
    // }


    public function update(Request $request, $id)
    {
        // Validate the required fields
        $validated = $request->validate([
            'package_name' => 'required|max:255',
            'package_type' => 'required',
            'subscription_type' => 'required',
            'package_duration' => 'required|numeric|min:1',
            'status' => 'required|in:active,pending,draft,private,upcoming,inactive',
            'price' => 'required_if:is_paid,1|nullable|numeric|min:1',
            'discount_flag' => 'nullable|boolean',
            'discounted_price' => 'required_if:discount_flag,1|nullable|numeric|min:1',
            'info' => 'array',
            'banner' => 'nullable|image|max:2048' // Validate banner image
        ]);

        // Find the subscription package by ID
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        // Prepare the data array
        $data = [
            'package_name' => $request->package_name,
            'short_description' => $request->short_description,
            'package_type' => $request->package_type,
            'subscription_type' => $request->subscription_type,
            'package_duration' => $request->package_duration,
            'status' => $request->status,
            'is_paid' => 1,
            'price' => $request->price,
            'discount_flag' => $request->discount_flag,
            'discounted_price' => $request->discount_flag ? $request->discounted_price : null,
            // 'info' => json_encode(array_filter($request->info, fn($value) => !is_null($value) && $value !== '')),
            'updated_at' => now(),
        ];
        if (!empty($request->info) && is_array($request->info)) {
            $filtered_info = array_filter($request->info, fn($value) => !is_null($value) && $value !== '');
            $data['info'] = json_encode(array_values($filtered_info)); // ensures simple array
        }
        
        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner file
            if ($subscriptionPackage->banner) {
                remove_file($subscriptionPackage->banner);
            }

            // Upload new banner
            $bannerPath = "uploads/subscription-package/banner/";
            $bannerName = nice_file_name($request->package_name, $request->file('banner')->extension());
            $data['banner'] = $bannerPath . $bannerName;

            FileUploader::upload($request->file('banner'), $data['banner'], 400, null, 200, 200);
        }

        // Update the subscription package
        $subscriptionPackage->update($data);

        return redirect()->route('admin.subscription_package')->with('success', 'Subscription package updated successfully');
    }


    public function status($type, $id)
    {
        if ($type == 'active') {
            SubscriptionPackage::where('id', $id)->update(['status' => 'active']);
        } elseif ($type == 'inactive') {
            SubscriptionPackage::where('id', $id)->update(['status' => 'inactive']);
        }

        return redirect(route('admin.subscription_package'))->with('success', get_phrase('Subscription Package status changed successfully'));
    }

    public function delete($id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        // Remove banner file if it exists
        if ($subscriptionPackage->banner) {
            remove_file($subscriptionPackage->banner);
        }

        // Delete the package from the database
        $subscriptionPackage->delete();

        return redirect(route('admin.subscription_package'))->with('success', get_phrase('Package deleted successfully'));
    }


}
