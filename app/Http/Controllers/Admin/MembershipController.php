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
}




// {
//   "title": "Elevate Your Career with Exclusive Membership Benefits",
//   "subtitle": "Capital Academy Membership is designed for ambitious professionals, executives, and finance enthusiasts who demand more from their learning experience. This is not just another membership; it’s a gateway to elite knowledge, networking, and career advancement in the world of finance and investment.",
//   "thumbnail": "uploads/dynamic_pages/memebership_page/member.jpg",
//   "memeber_count": "7000000",
//   "pricing": {
//     "title": "Membership Pricing Plan",
//     "plans": [
//       {
//         "title": "Standard Membership",
//         "subtitle_1": "For Learners & Career Builders",
//         "subtitle_2": "Designed for finance professionals, students, and individuals looking for expert-led education, networking, and career acceleration.",
//         "price": "$299",
//         "type": "Annual",
//         "features": [
//           {
//             "title": "Priority Access to Courses & Certifications",
//             "description": "Be the first to access new programs and certifications."
//           },
//           {
//             "title": "Exclusive Industry Insights & Market Reports",
//             "description": "Stay informed with premium financial research."
//           },
//           {
//             "title": "Networking & Discussion Forums",
//             "description": "Engage with professionals in the finance industry."
//           },
//           {
//             "title": "Member-Only Webinars & Masterclasses",
//             "description": "Join exclusive training sessions led by industry experts."
//           },
//           {
//             "title": "Mentorship & Career Support",
//             "description": "Connect with finance professionals for career guidance."
//           },
//           {
//             "title": "Exclusive Partner Discounts",
//             "description": "Get special rates on finance tools, investment platforms, and educational services."
//           }
//         ]
//       },
//       {
//         "title": "Premium Membership",
//         "subtitle_1": "For Executives, Investors & Industry Leaders",
//         "subtitle_2": "Built for high-level professionals, traders, fund managers, and corporate executives who demand elite networking, investment advantages, and cutting-edge AI-powered tools.",
//         "price": "$1500",
//         "type": "Annual",
//         "features": [
//           {
//             "title": "Blockchain-Verified Credentials & Digital Identity",
//             "description": "Secure, verifiable finance credentials for LinkedIn & professional use."
//           },
//           {
//             "title": "AI-Powered Career Growth Tools",
//             "description": "Personalized learning paths, resume audits, and LinkedIn optimization."
//           },
//           {
//             "title": "Global Networking & Mentorship",
//             "description": "Access private events, summits, and 1-on-1 mentorship with finance leaders."
//           },
//           {
//             "title": "Exclusive Job Board",
//             "description": "Priority access to high-profile investment banking, fintech, and hedge fund job opportunities"
//           },
//           {
//             "title": "CEO & Founder Roundtables",
//             "description": "Participate in private discussions with industry leaders, hedge fund managers, and top executives."
//           },
//           {
//             "title": "Member-Only Investment & Trading Benefits",
//             "description": "Receive premium trading insights, market forecasts, and exclusive investment opportunities."
//           },
//           {
//             "title": "Personalized Investment & Trading Strategies",
//             "description": "Tailored investment strategies and portfolio management advice."
//           },
//           {
//             "title": "Discounted Access to Trading Platforms & Financial Tools",
//             "description": "Attend private events, conferences, and networking sessions with industry leaders."
//           },
//           {
//             "title": "Capital Academy Innovation Lab",
//             "description": "Dedicated support for all your membership needs."
//           }
//         ]
//       }
//     ]
//   }
// }