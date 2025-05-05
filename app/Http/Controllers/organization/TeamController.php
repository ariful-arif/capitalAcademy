<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SubscriptionPackageEnrollment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $status = 'all';
        $query = Team::query();
        // search filter
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $_GET['search'] . '%');
        }

        // // status filter
        // if (isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] != 'all') {
        //     if ($_GET['status'] == 'active') {
        //         $search_status = 'active';
        //         $query         = $query->where('status', $search_status);
        //     } elseif ($_GET['status'] == 'inactive') {
        //         $search_status = 'inactive';
        //         $query         = $query->where('status', $search_status);
        //     }
        //     $status = $_GET['status'];
        // }

        // $page_data['status']           = $status;
        $page_data['teams'] = $query->paginate(20)->appends(request()->query());
        // $page_data['active_certificate']  = Team::where('status', 'active')->count();
        // $page_data['inactive_certificate']  = Team::where('status', 'inactive')->count();
        return view("organization.team.index", $page_data);
    }


    public function create()
    {
        return view('organization.team.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        //for normal form submission

        $data['name'] = $request->name;
        $data['organization_id'] = auth()->user()->id;
        $data['team_members'] = $request->team_members;
        $data['member_ids'] = json_encode($request->member_ids);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        // if ($request->thumbnail) {
        //     $data['thumbnail'] = "uploads/certificate-program/certificate-thumbnail/" . nice_file_name($request->title, $request->thumbnail->extension());
        //     FileUploader::upload($request->thumbnail, $data['thumbnail'], 400, null, 200, 200);
        // }

        Team::insertGetId($data);

        //for normal form submission
        return redirect(route('organization.teams'))->with('success', get_phrase('Team create successfully'));
    }

    public function users_add1(Request $request)
    {
        $validated = $request->validate([
            'team' => 'required',
            'member_ids' => 'array',
        ]);

        // Find the team
        $team = Team::find($request->team);

        if (!$team) {
            return redirect()->back()->with('error', 'Team not found.');
        }

        // Get selected members count
        $selectedMembersCount = count($request->member_ids ?? []);

        // Check if the selected members exceed the allowed limit
        if ($selectedMembersCount > $team->team_members) {
            return redirect(route('organization.teams'))->with('error', "You can't add more than {$team->team_members} members to this team. Please extend the members.");
        }

        // Get users already assigned to other teams
        $existingAssignments = Team::where('id', '!=', $team->id)
            ->whereNotNull('member_ids')
            ->get()
            ->mapWithKeys(function ($existingTeam) {
                $memberIds = is_string($existingTeam->member_ids)
                    ? json_decode($existingTeam->member_ids, true)
                    : $existingTeam->member_ids; // Ensure it's an array

                return array_fill_keys($memberIds ?? [], $existingTeam->name);
            });
        $memberIds = $request->member_ids ?? []; // If null, set to an empty array

        foreach ($memberIds as $member_id) {
            if (isset($existingAssignments[$member_id])) {
                $user = User::find($member_id);
                $userName = $user ? $user->name : "Unknown User";

                return redirect()->back()->with('error', "This user **{$userName}** is already assigned to team '{$existingAssignments[$member_id]}'. A user can belong to only one team.");
            }
        }

        // foreach ($request->member_ids as $member_id) {
        //     if (isset($existingAssignments[$member_id])) {
        //         // Fetch user name from the database
        //         $user = User::find($member_id);
        //         $userName = $user ? $user->name : "Unknown User";

        //         return redirect()->back()->with('error', "This user **{$userName}** is already assigned to team '{$existingAssignments[$member_id]}'. A user can belong to only one team.");
        //     }
        // }

        // Update team details
        $team->member_ids = json_encode($request->member_ids); // Store member IDs
        $team->updated_at = now();
        $team->save();

        return redirect(route('organization.teams'))->with('success', 'User added successfully.');
    }

    public function users_add(Request $request)
    {
        $validated = $request->validate([
            'team' => 'required',
            'member_ids' => 'array',
        ]);

        // Find the team
        $team = Team::find($request->team);

        if (!$team) {
            return redirect()->back()->with('error', 'Team not found.');
        }
        $isenrollment = SubscriptionPackageEnrollment::where('user_id', $team->organization_id)
            ->orderBy('id', "desc")
            ->first();


        if (!$isenrollment) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'Subscription package not found.',
            ], 404);
        }

        // Get subscription license amount
        $licenseAmount = $isenrollment->license_amount;

        // Get all existing team member IDs across all teams
        $allTeamMembers = Team::whereNotNull('member_ids')
            ->get()
            ->flatMap(function ($team) {
                return is_array($team->member_ids) ? $team->member_ids : json_decode($team->member_ids, true) ?? [];
            })
            ->unique()
            ->count();


        // Count new members to be added
        $newMembersCount = count($request->member_ids ?? []);

        // dd($licenseAmount);

        // Check if total unique users exceed subscription license amount
        if (($allTeamMembers + $newMembersCount) > $licenseAmount) {
            // return response()->json([
            //     'status' => false,
            //     'status_code' => 403,
            //     'message' => 'License limit reached. No more users can be added. Please extend your license.',
            // ], 403);

            return redirect()->back()->with('error', "License limit reached. No more users can be added. Please extend your license. This time your License limit is **{$licenseAmount}** ");
        }

        // Get users already assigned to other teams
        $existingAssignments = Team::where('id', '!=', $team->id)
            ->whereNotNull('member_ids')
            ->get()
            ->mapWithKeys(function ($existingTeam) {
                $memberIds = is_array($existingTeam->member_ids) ? $existingTeam->member_ids : json_decode($existingTeam->member_ids, true);

                return array_fill_keys($memberIds ?? [], $existingTeam->name);
            });


        foreach ($request->member_ids as $member_id) {
            if (isset($existingAssignments[$member_id])) {
                $user = User::find($member_id);
                $userName = $user ? $user->name : "Unknown User";

                return redirect()->back()->with('error', "This user **{$userName}** is already assigned to team '{$existingAssignments[$member_id]}'. A user can belong to only one team.");
            }
        }

        // Update team members
        $updatedMembers = array_merge(
            is_array($team->member_ids) ? $team->member_ids : json_decode($team->member_ids, true) ?? [],
            $request->member_ids
        );
        // $team->member_ids = json_encode(array_unique($updatedMembers)); // Ensure unique members
        // $team->member_ids = json_encode(array_values(array_unique($updatedMembers)));
        $team->member_ids = array_values(array_unique($updatedMembers)); 

        $team->updated_at = now();
        $team->save();

        return redirect(route('organization.teams'))->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $page_data['teams'] = Team::where('id', $id)->first();
        return view('organization.team.edit', $page_data);
    }

    public function update(Request $request, $id)
    {
        // $data = $request->all();
        // dd($data);
        // die;
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $certificate = Team::find($id);

        // Prepare data for update
        $data['name'] = $request->name;
        $data['team_members'] = $request->team_members;
        // $data['member_ids'] = json_encode($request->member_ids);
        $data['updated_at'] = now();

        // // Handle Thumbnail Upload (if new file is uploaded)
        // if ($request->hasFile('thumbnail')) {
        //     // Delete old file if exists
        //     if ($certificate->thumbnail && file_exists(public_path($certificate->thumbnail))) {
        //         unlink(public_path($certificate->thumbnail));
        //     }

        //     // Save new file
        //     $data['thumbnail'] = "uploads/certificate-program/certificate-thumbnail/" . nice_file_name($request->title, $request->thumbnail->extension());
        //     FileUploader::upload($request->thumbnail, $data['thumbnail'], 400, null, 200, 200);
        // }

        // Update certificate program data
        $certificate->update($data);

        return redirect(route('organization.teams'))
            ->with('success', get_phrase('Team updated successfully'));
    }

    public function delete($id)
    {
        // Find the certificate program by ID
        $certificate = Team::findOrFail($id);

        // Delete associated files (thumbnail and certificate template)
        if ($certificate->thumbnail && file_exists(public_path($certificate->thumbnail))) {
            unlink(public_path($certificate->thumbnail));
        }

        if ($certificate->certificate_template && file_exists(public_path($certificate->certificate_template))) {
            unlink(public_path($certificate->certificate_template));
        }

        // Delete the record from the database
        $certificate->delete();

        // Redirect back with success message
        return redirect()->route('organization.teams')
            ->with('success', get_phrase('Team deleted successfully'));
    }

    public function users()
    {
        $page_data['teams'] = Team::get();
        return view('organization.team.users_create', $page_data);
    }
    public function getTeamMembers(Request $request)
    {
        $team = Team::find($request->team_id);

        if ($team) {
            return response()->json([
                'success' => true,
                'team_members' => $team->team_members,
                'selected_members' => is_string($team->member_ids) ? json_decode($team->member_ids, true) : ($team->member_ids ?? [])
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Team not found'
            ]);
        }
    }

    //     public function progress(Request $request)
//     {
//         // Get authenticated user ID
//         $user_id = auth()->user()->id;

    //         // Fetch the latest subscription details for the user
//         $subscription = SubscriptionPackageEnrollment::where('user_id', $user_id)
//             ->orderBy('id', "desc")
//             ->first();

    //         if (!$subscription) {
//             return redirect()->back()->with('error', 'No active subscription found.');
//         }

    //         // Fetch teams linked to this subscription's organization
//         $teams = Team::where('organization_id', $subscription->user_id)->get();

    //         // Extract all member IDs from the teams
//         $allMemberIds = [];

    //         foreach ($teams as $team) {
//             // Debugging: Check what is stored in `member_ids`


    //             // Ensure `member_ids` is a valid JSON string before decoding
//             $teamMemberIds = is_string($team->member_ids) ? json_decode($team->member_ids, true) : [];

    //             // Ensure it's an array before merging
//             if (!is_array($teamMemberIds)) {
//                 $teamMemberIds = [];
//             }

    //             $allMemberIds = array_merge($allMemberIds, $teamMemberIds);
//             // $allMemberIds = $team->name;
//         }
// //  dd($allMemberIds);
//         // Ensure unique values
//         $allMemberIds = array_unique($allMemberIds);

    //         // Debug to check extracted IDs
//         // dd($allMemberIds);



    //         // Fetch users who belong to these teams
//         $users = User::whereIn('id', $allMemberIds)->get();

    //         // Fetch active courses related to this subscription package
//         $activeCourses = Course::where('is_certificate_course', 1)
//             ->where('status', 'active')
//             ->get();

    //         // Prepare user progress data
//         $usersWithProgress = [];

    //         foreach ($users as $user) {
//             $totalProgress = 0;
//             $totalCourses = count($activeCourses);
//             $userCourses = [];

    //             foreach ($activeCourses as $course) {
//                 $completion = round(course_progress($course->id, $user->id));
//                 $totalLessons = count(get_lessons('course', $course->id));
//                 $completedLessons = get_completed_number_of_lesson($user->id, 'course', $course->id);

    //                 // Store individual course progress
//                 $userCourses[] = [
//                     'course_id' => $course->id,
//                     'certificate_course_type' => $course->certificate_course_type,
//                     'course_name' => $course->title,
//                     'completion_percentage' => $completion,
//                     'total_number_of_lessons' => $totalLessons,
//                     'total_number_of_completed_lessons' => $completedLessons,
//                 ];

    //                 // Sum progress for overall percentage
//                 $totalProgress += $completion;
//             }

    //             // Calculate overall progress percentage
//             $overallProgress = ($totalCourses > 0) ? round($totalProgress / $totalCourses) : 0;

    //             // Append user with progress details
//             $usersWithProgress[] = [
//                 'user_id' => $user->id,
//                 'role' => $user->role,
//                 'status' => $user->status,
//                 'name' => $user->name,
//                 'email' => $user->email,
//                 'phone' => $user->phone,
//                 'website' => $user->website,
//                 'skills' => $user->skills,
//                 'facebook' => $user->facebook,
//                 'twitter' => $user->twitter,
//                 'linkedin' => $user->linkedin,
//                 'address' => $user->address,
//                 'about' => $user->about,
//                 'biography' => $user->biography,
//                 'photo' => get_photo('user_image', $user->photo),
//                 'overall_progress' => $overallProgress,
//                 'courses' => $userCourses,
//             ];
//         }

    //         // Pass data to the view
//         $page_data = [
//             'subscription' => $subscription,
//             'teams' => $teams,
//             'usersWithProgress' => $usersWithProgress,
//         ];

    //         return view('organization.team_progress.team_progress', $page_data);
//     }



    public function progress(Request $request)
    {
        $user_id = auth()->user()->id;

        // Fetch the latest subscription
        $subscription = SubscriptionPackageEnrollment::where('user_id', $user_id)
            ->orderBy('id', "desc")
            ->first();

        // if (!$subscription) {
        //     return redirect()->back()->with('error', 'No active subscription found.');
        // }

        // Fetch teams linked to this organization
        $teams = Team::where('organization_id', $subscription->user_id)->get();

        $allMemberIds = [];

        foreach ($teams as $team) {
            // Debugging to check stored data
            // dump("Team Name:", $team->name, "Raw Member IDs:", $team->member_ids);

            // Ensure `member_ids` is a valid JSON string before decoding
            $teamMemberIds = is_string($team->member_ids) ? json_decode($team->member_ids, true) : $team->member_ids;

            if (!is_array($teamMemberIds)) {
                $teamMemberIds = [];
            }

            $allMemberIds = array_merge($allMemberIds, $teamMemberIds);
        }


        // Remove duplicates
        $allMemberIds = array_unique($allMemberIds);

        // Debugging - check extracted IDs
        // dump("All Extracted Member IDs:", $allMemberIds);

        // if (empty($allMemberIds)) {
        //     return redirect()->back()->with('error', 'No team members found.');
        // }

        // Fetch users based on extracted IDs
        $users = User::whereIn('id', $allMemberIds)->get();

        // if ($users->isEmpty()) {
        //     return redirect()->back()->with('error', 'No users found for the selected teams.');
        // }

        // Fetch active courses
        $activeCourses = Course::where('is_certificate_course', 1)
            ->where('status', 'active')
            ->get();

        // Prepare user progress data
        $usersWithProgress = [];

        foreach ($users as $user) {
            $totalProgress = 0;
            $totalCourses = $activeCourses->count();
            $userCourses = [];

            foreach ($activeCourses as $course) {
                $completion = round(course_progress($course->id, $user->id));
                $totalLessons = count(get_lessons('course', $course->id));
                $completedLessons = get_completed_number_of_lesson($user->id, 'course', $course->id);

                $userCourses[] = [
                    'course_id' => $course->id,
                    'course_name' => $course->title,
                    'completion_percentage' => $completion,
                    'total_number_of_lessons' => $totalLessons,
                    'total_number_of_completed_lessons' => $completedLessons,
                ];

                $totalProgress += $completion;
            }

            $overallProgress = ($totalCourses > 0) ? round($totalProgress / $totalCourses) : 0;

            $usersWithProgress[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'overall_progress' => $overallProgress,
                'courses' => $userCourses,
            ];
        }

        // if (empty($usersWithProgress)) {
        //     return redirect()->back()->with('error', 'Users found, but no progress data available.');
        // }

        return view('organization.team_progress.team_progress', [
            'subscription' => $subscription,
            'teams' => $teams,
            'usersWithProgress' => $usersWithProgress,
        ]);
    }





}
