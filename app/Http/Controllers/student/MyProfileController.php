<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\FileUploader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function index()
    {
        $page_data['user_details'] = User::find(auth()->user()->id);
        $view_path                 = 'frontend.' . get_frontend_settings('theme') . '.student.my_profile.index';
        return view($view_path, $page_data);
    }

    public function update(Request $request, $user_id)
    {
        $rules = [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user_id,
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name']      = $request->name;
        $data['email']     = $request->email;
        $data['phone']     = $request->phone;
        $data['website']   = $request->website;
        $data['facebook']  = $request->facebook;
        $data['twitter']   = $request->twitter;
        $data['instagram']   = $request->instagram;
        $data['designation'] = $request->designation;
        $data['experience'] = $request->experience;
        $data['video_url']   = $request->video_url;
        $data['about']   = $request->about;
        $data['linkedin']  = $request->linkedin;
        $data['skills']    = $request->skills;
        $data['biography'] = $request->biography;

        User::where('id', $user_id)->update($data);
        Session::flash('success', get_phrase('Profile updated successfully.'));
        return redirect()->back();
    }

    public function update_profile_picture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp,tiff|max:3072',
        ]);

        // process file
        $file      = $request->photo;
        $file_name = Str::random(20) . '.' . $file->extension();
        $path      = 'uploads/users/' . auth()->user()->role . '/' . $file_name;
        FileUploader::upload($file, $path, null, null, 300);

        User::where('id', auth()->user()->id)->update(['photo' => $path]);
        Session::flash('success', get_phrase('Profile picture updated.'));
        return redirect()->back();
    }

    
    public function education_add(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|string|in:ongoing,completed',
            'description' => 'nullable|string'
        ]);

        // Check if 'end_date' is empty and 'status' is 'ongoing'
        if ($request->has('status') && $request->status === 'ongoing') {
            $validatedData['end_date'] = null;
        } else {
            $validatedData['status'] = 'completed';
        }

        // Format data for new education entry
        $newEducation = [
            'title' => $validatedData['title'],
            'institute' => $validatedData['institute'],
            'country' => $validatedData['country'],
            'city' => $validatedData['city'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'status' => $validatedData['status'],
            'description' => $validatedData['description']
        ];

        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Decode the existing educations JSON data
        $educations = json_decode($user->educations, true) ?? [];

        // Append the new education entry
        $educations[] = $newEducation;

        // Save updated educations data back to the user's educations column as JSON
        $user->educations = json_encode($educations);
        $user->save();

        // Redirect or return a response, e.g., to the resume index page with a success message
        return redirect()->back()->with('success', 'Education added successfully.');
    }

    public function education_update(Request $request, $index)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|string|in:ongoing,completed',
            'description' => 'nullable|string'
        ]);

        // Check if 'end_date' is empty and 'status' is 'ongoing'
        if ($request->has('status') && $request->status === 'ongoing') {
            $validatedData['end_date'] = null;
        } else {
            $validatedData['status'] = 'completed';
        }

        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Decode the existing educations JSON data
        $educations = json_decode($user->educations, true) ?? [];

        // Check if the specified index exists in educations array
        if (isset($educations[$index])) {
            // Update the existing education entry
            $educations[$index] = [
                'title' => $validatedData['title'],
                'institute' => $validatedData['institute'],
                'country' => $validatedData['country'],
                'city' => $validatedData['city'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'status' => $validatedData['status'],
                'description' => $validatedData['description']
            ];

            // Save updated educations data back to the user's educations column as JSON
            $user->educations = json_encode($educations);
            $user->save();

            // Redirect or return a response, e.g., to the resume index page with a success message
            return redirect()->back()->with('success', 'Education updated successfully.');
        } else {
            // Handle the case where the specified education index does not exist
            return redirect()->back()->with('error', 'Education data not found for the specified index.');
        }
    }


    public function education_remove(Request $request, $index)
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Decode the existing educations JSON data
        $educations = json_decode($user->educations, true) ?? [];

        // Check if the index exists in the educations array
        if (isset($educations[$index])) {
            // Remove the specific education entry
            unset($educations[$index]);

            // Re-index the array to ensure no gaps in the indices
            $educations = array_values($educations);

            // Update the user's educations column with the modified array
            $user->educations = json_encode($educations);
            $user->save();

            return redirect()->back()->with('success', 'Education deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Education not found.');
        }
    }
}
