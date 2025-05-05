<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackageEnrollment;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    //
    public function index()
    {
        $page_data['subscriptions'] = SubscriptionPackageEnrollment::where('user_id', auth()->user()->id)->get();
        return view('organization.subscription.index', $page_data);
    }
}
