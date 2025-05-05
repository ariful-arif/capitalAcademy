<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FileUploader;
use App\Models\Payout;
use App\Models\Permission;
use App\Models\Setting;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $query = User::where('role', 'organization_user')->where('organization_id', auth()->user()->id);
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $_GET['search'] . '%')
                ->orWhere('email', 'LIKE', '%' . $_GET['search'] . '%');
        }
        $page_data['organization_users'] = $query->paginate(10);
        return view("organization.users.index", $page_data);
    }
    public function create()
    {
        return view("organization.users.create");
    }

    public function store(Request $request, $id = '')
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if (get_settings('student_email_verification') != 1) {
            $data['email_verified_at'] = date('Y-m-d H:i:s');
        }

        $data['name'] = $request->name;
        $data['about'] = $request->about;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['email'] = $request->email;
        $data['facebook'] = $request->facebook;
        $data['twitter'] = $request->twitter;
        $data['website'] = $request->website;
        $data['linkedin'] = $request->linkedin;
        $data['paymentkeys'] = json_encode($request->paymentkeys);
        $data['status'] = '1';

        $data['password'] = Hash::make($request->password);
        $data['role'] = 'organization_user';
        $data['organization_id'] = auth()->user()->id;



        if (isset($request->photo) && $request->hasFile('photo')) {
            $path = "uploads/users/student/" . nice_file_name($request->name, $request->photo->extension());
            FileUploader::upload($request->photo, $path, 400, null, 200, 200);
            $data['photo'] = $path;
        }
        // dd(vars: $data);
        // dd(auth()->user()->id);
        // die;
        $user = User::create($data);

        if (get_settings('student_email_verification') == 1) {
            $user->sendEmailVerificationNotification();
        }

        Session::flash('success', get_phrase('User add successfully'));

        return redirect()->route('organization.users');
    }

    public function edit($id = '')
    {
        $page_data['organization_users'] = User::where('id', $id)->first();
        return view('organization.users.edit', $page_data);
    }

    public function update(Request $request, $id = '')
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => "required|email|unique:users,email,$id",
        ]);

        $data['name'] = $request->name;
        $data['about'] = $request->about;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['email'] = $request->email;
        $data['facebook'] = $request->facebook;
        $data['twitter'] = $request->twitter;
        $data['website'] = $request->website;
        $data['linkedin'] = $request->linkedin;
        $data['paymentkeys'] = json_encode($request->paymentkeys);

        if (isset($request->photo) && $request->hasFile('photo')) {
            remove_file(User::where('id', $id)->first()->photo);
            $path = "uploads/users/student/" . nice_file_name($request->name, $request->photo->extension());
            FileUploader::upload($request->photo, $path, 400, null, 200, 200);
            $data['photo'] = $path;
        }
        // dd($data);
        // dd(auth()->user()->id);
        // die;

        User::where('id', $id)->update($data);
        Session::flash('success', get_phrase('User update successfully'));
        return redirect()->route('organization.users');
    }


    public function delete($id)
    {
        $threads = MessageThread::where('contact_one', $id)
            ->orWhere('contact_two', $id)
            ->pluck('id');

        if ($threads->isNotEmpty()) {
            Message::whereIn('thread_id', $threads)->delete();
            MessageThread::whereIn('id', $threads)->delete();
        }

        $query = User::where('id', $id);
        if (isset($query->first()->photo)) {
            remove_file($query->first()->photo);
            $query->delete();
        }
        ;

        return redirect(route('organization.users'))->with('success', get_phrase('User deleted successfully'));
    }

}
