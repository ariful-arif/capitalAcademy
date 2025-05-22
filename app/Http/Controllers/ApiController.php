<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\Bootcamp;
use App\Models\BootcampCategory;
use App\Models\BootcampLiveClass;
use App\Models\BootcampModule;
use App\Models\BootcampPurchase;
use App\Models\BootcampResource;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FileUploader;
use App\Models\FrontendSetting;
use App\Models\Language;
use App\Models\Live_class;
use App\Models\MediaFile;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MyCertificate;
use App\Models\NewsletterSubscriber;
use App\Models\Payment_history;
use App\Models\Setting;
use App\Models\SubscriptionPackageEnrollment;
use App\Models\SubscriptionPackageHistory;
use App\Models\SubscriptionPackage;
use App\Models\TutorBooking;
use App\Models\TutorCategory;
use App\Models\TutorReview;
use App\Models\TutorSchedule;
use App\Models\TutorSubject;
use App\Models\User;
use App\Models\UserReview;
use App\Models\Review;
use App\Models\LikeDislikeReview;
use App\Models\Wishlist;
use App\Models\CertificateProgram;
use App\Models\Team;
use App\Http\Controllers\student\PurchaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
// use DB;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\PasswordOtp;
use Smalot\PdfParser\Parser;
use Stripe\Stripe;
use App\Models\Lesson;
use App\Models\Newsroom;
use App\Models\Learning;
use App\Models\Coupon;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiController extends Controller
{

    // public function login(Request $request)
    // {
    //     $fields = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     // Check email
    //     $user = User::where('email', $fields['email'])->where('status', 1)->first();

    //     // Check password
    //     if (! $user || ! Hash::check($fields['password'], $user->password)) {
    //         if (isset($user) && $user->count() > 0) {
    //             return response([
    //                 'message' => 'Invalid credentials!',
    //             ], 401);
    //         } else {
    //             return response([
    //                 'message' => 'User not found!',
    //             ], 401);
    //         }
    //     } elseif ($user->role == 'student') {

    //         // $user->tokens()->delete();

    //         $token = $user->createToken('auth-token')->plainTextToken;

    //         $user->photo = get_photo('user_image', $user->photo);

    //         $response = [
    //             'message' => 'Login successful',
    //             'user' => $user,
    //             'token' => $token,
    //         ];

    //         return response($response, 200);
    //     } else {

    //         //user not authorized
    //         return response()->json([
    //             'message' => 'User not found!',
    //         ], 400);
    //     }
    // }

    // public function signup(Request $request)
    // {
    //     $response = [];

    //     $rules = [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ];

    //     // Validate the input
    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'status_code' => 422,
    //             'message' => 'Validation errors occurred.',
    //             'errors' => $validator->errors(),
    //         ], 200);
    //     }

    //     try {
    //         // Create the user
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'role' => 'student',
    //             'password' => Hash::make($request->password),
    //             'status' => 1,
    //         ]);

    //         // Trigger the Registered event
    //         // event(new Registered($user));

    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'User registered successfully.',
    //             'data' => $user,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Handle unexpected errors
    //         return response()->json([
    //             'status' => false,
    //             'status_code' => 500,
    //             'message' => 'An error occurred while registering the user.',
    //             'error' => $e->getMessage(),
    //         ], 200);
    //     }
    // }

    public function forgot_password1(Request $request)
    {
        // Validate email input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Attempt to send password reset link
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Reset password link sent successfully to your email.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'status_code' => 400,
            'message' => 'Failed to send reset password link. Please try again later.',
        ], 400);
    }


    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $otp = rand(100000, 999999);
        $email = $request->email;

        // Store OTP
        PasswordOtp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        // Send OTP via Mail (or SMS)
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your Password Reset OTP');
        });

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'Otp' => $otp,
            'message' => 'OTP sent to your email.'
        ], 200);
    }

    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $otpData = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpData) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        // Optional: Mark as verified or delete OTP
        $otpData->delete();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'OTP verified successfully.'
        ], 200);
    }


    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Password reset successfully.'
        ], 200);
    }


    public function top_courses($top_course_id = null)
    {
        try {
            // Base query to fetch the top 10 courses, ordered by ID in descending order
            $query = Course::withCount(['lessons'])
                ->orderBy('lessons_count', 'desc')
                ->where('status', 'active')->limit(10);

            // Filter by the given course ID if provided
            if (!is_null($top_course_id)) {
                $query->where('id', $top_course_id);
            }

            // Execute the query and retrieve the results
            $courses = $query->get();

            // Check if data exists
            if ($courses->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No courses found.',
                    'data' => [],
                ], 200); // HTTP status 200, but status_code indicates 404
            }

            // Format the course data (assuming `course_data` is a helper function for this purpose)
            $result = top_courses($courses);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Top courses retrieved successfully.',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the courses.',
                'error' => $e->getMessage(),
            ], 200); // HTTP status 200, but status_code indicates 500
        }
    }

    public function all_categories()
    {
        try {
            // Retrieve all parent categories
            $categories = Category::where('parent_id', 0)->get();

            // Check if categories exist
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No categories found.',
                    'data' => [],
                ], 200);
            }

            // Format categories data
            $all_categories = [];

            foreach ($categories as $key => $category) {
                $all_categories[$key] = $category;
                $all_categories[$key]['thumbnail'] = get_photo('category_thumbnail', $category['thumbnail']);
                $all_categories[$key]['category_logo'] = get_photo('category_logo', $category['category_logo']);
                $all_categories[$key]['number_of_courses'] = get_category_wise_courses($category['id'])->count();
                $all_categories[$key]['number_of_sub_categories'] = $category->childs->count();

                // Use only 'sub_categories' and rename 'childs' to 'sub_categories'
                $all_categories[$key]['childs'] = $category->childs->map(function ($sub_category) {
                    $sub_category['thumbnail'] = get_photo('category_thumbnail', $sub_category['thumbnail']);
                    $sub_category['category_logo'] = get_photo('category_logo', $sub_category['category_logo']);
                    $sub_category['number_of_courses'] = get_category_wise_courses($sub_category['id'])->count();

                    return $sub_category;
                });

                $courses = get_category_wise_courses($category->id);
                $all_categories[$key]['courses'] = course_data($courses);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Categories retrieved successfully.',
                'data' => $all_categories,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Category with id and title
    public function categories()
    {
        try {
            // Retrieve all parent categories
            $categories = Category::where('parent_id', 0)->get();

            // Check if categories exist
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No categories found.',
                    'data' => [],
                ], 200);
            }

            // Format categories data
            $all_categories = [];

            foreach ($categories as $key => $category) {
                // $all_categories[$key] = $category;
                $all_categories[$key]['id'] = $category['id'];
                $all_categories[$key]['title'] = $category['title'];
                $all_categories[$key]['slug'] = $category['slug'];
                $all_categories[$key]['icon'] = $category['icon'];
                // $all_categories[$key]['thumbnail'] = get_photo('category_thumbnail', $category['thumbnail']);
                // $all_categories[$key]['category_logo'] = get_photo('category_logo', $category['category_logo']);


                // $courses = get_category_wise_courses($category->id);
                // $all_categories[$key]['courses'] = course_data($courses);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Categories retrieved successfully.',
                'data' => $all_categories,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }


    public function category_subcategory_wise_course(Request $request)
    {
        try {
            // Validate the request
            // $request->validate([
            //     'category_id' => 'required|integer|exists:categories,id'
            // ]);

            // Fetch courses for the specified category ID
            $category_id = $request->category_id;
            $courses = get_category_wise_courses($category_id);

            // Check if courses are available
            if ($courses->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No courses found for the given category.',
                    'data' => [],
                ], 200);
            }

            // Format course data
            $result = course_data($courses);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Courses retrieved successfully.',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving courses.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function all_course1(Request $request)
    {
        try {
            // Get pagination parameters from request
            $limit = $request->input('limit'); // No default value
            $page = $request->input('page');   // No default value

            // Get course data with or without pagination
            $result = courses($limit, $page);

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Courses retrieved successfully.',
                'data' => $result['data'],
                'total_course' => $result['total'],
                'price' => [
                    'lowest_price' => $result['lowest_price'],
                    'highest_price' => $result['highest_price'],
                ],

            ];

            // Add pagination info only if pagination is applied
            if ($limit && $page) {
                $response['pagination'] = [
                    // 'lowest_price' => $result['lowest_price'],
                    // 'highest_price' => $result['highest_price'],
                    // 'total_course' => $result['total'],
                    'limit' => (int) $limit,
                    'page' => (int) $page,
                    'total_page' => ceil($result['total'] / $limit),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function all_course2(Request $request)
    {
        try {
            $filters = [
                'search_string' => $request->input('search_string'),
                'selected_category' => $request->input('selected_category', 'all'),
                'selected_level' => $request->input('selected_level', 'all'),
                'selected_rating' => $request->input('selected_rating', 'all'),
                'selected_instructor' => $request->input('selected_instructor', 'all'),
                'min_price' => $request->input('min_price', null),
                'max_price' => $request->input('max_price', null),
            ];

            $limit = $request->input('limit');
            $page = $request->input('page');

            // Get courses with filters
            $result = courses($limit, $page, $filters);

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Courses retrieved successfully.',
                'data' => $result['data'],
                'total_course' => $result['total'],
                'price' => [
                    'lowest_price' => $result['lowest_price'],
                    'highest_price' => $result['highest_price'],
                ],
            ];

            // Add pagination info if applied
            if ($limit && $page) {
                $response['pagination'] = [
                    'limit' => (int) $limit,
                    'page' => (int) $page,
                    'total_page' => ceil($result['total'] / $limit),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function all_course(Request $request)
    {
        try {
            $filters = [
                'search_string' => $request->input('search_string'),
                'selected_category' => $request->input('selected_category', 'all'),
                'selected_price' => $request->input('selected_price', 'all'),
                'selected_level' => $request->input('selected_level', 'all'),
                'selected_rating' => $request->input('selected_rating', 'all'),
                'selected_instructor' => $request->input('selected_instructor', 'all'),
                'min_price' => $request->input('min_price', null),
                'max_price' => $request->input('max_price', null),
            ];

            $limit = $request->input('limit');
            $page = $request->input('page');

            // Convert category & rating to an array if passed as a string
            if (!empty($filters['selected_category']) && $filters['selected_category'] !== 'all' && !is_array($filters['selected_category'])) {
                $filters['selected_category'] = explode(',', $filters['selected_category']);
            }
            if (!empty($filters['selected_rating']) && $filters['selected_rating'] !== 'all' && !is_array($filters['selected_rating'])) {
                $filters['selected_rating'] = explode(',', $filters['selected_rating']);
            }

            // Get courses with filters
            $result = courses($limit, $page, $filters);

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Courses retrieved successfully.',
                'data' => $result['data'],
                'total_course' => $result['total'],
                'price' => [
                    'lowest_price' => $result['lowest_price'],
                    'highest_price' => $result['highest_price'],
                ],
            ];

            // Add pagination info if applied
            if ($limit && $page) {
                $response['pagination'] = [
                    'limit' => (int) $limit,
                    'page' => (int) $page,

                    'total_page' => ceil($result['total'] / $limit),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function filter_course(Request $request)
    {
        try {
            // Validate input parameters
            // $request->validate([
            //     'selected_category' => 'nullable|string',
            //     'selected_price' => 'nullable|string|in:all,paid,free',
            //     'selected_level' => 'nullable|string',
            //     'selected_language' => 'nullable|string',
            //     'selected_rating' => 'nullable|integer|min:1|max:5',
            //     'selected_search_string' => 'nullable|string'
            // ]);

            // Retrieve filter parameters
            $selected_category = $request->selected_category ?? 'all';
            $selected_price = $request->selected_price ?? 'all';
            $selected_level = $request->selected_level ?? 'all';
            $selected_language = $request->selected_language ?? 'all';
            $selected_rating = $request->selected_rating ?? 'all';
            // $selected_search_string = trim($request->selected_search_string ?? "");

            // Query initialization
            $query = Course::query();

            // Apply filters
            // if (!empty($selected_search_string)) {
            //     $query->where('title', 'LIKE', "%{$selected_search_string}%");
            // }
            if ($selected_category !== 'all') {
                $query->where('category_id', $selected_category);
            }
            if ($selected_price !== 'all') {
                if ($selected_price === 'paid') {
                    $query->where('is_paid', 1);
                } elseif ($selected_price === 'free') {
                    $query->where(function ($q) {
                        $q->where('is_paid', 0)->orWhereNull('is_paid');
                    });
                }
            }
            if ($selected_level !== 'all') {
                $query->where('level', $selected_level);
            }
            if ($selected_language !== 'all') {
                $query->where('language', $selected_language);
            }
            if ($selected_rating !== 'all') {
                $query->where('average_rating', $selected_rating);
            }

            // Filter only active courses
            $query->where('status', 'active');
            $courses = $query->get();

            // Format the response
            $result = course_data($courses);

            if (empty($result)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No courses found for the given filters.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Courses retrieved successfully.',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while filtering courses.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function languages()
    {
        try {
            // Fetch distinct languages
            $languages = Language::select('name')->distinct()->get();

            // Check if languages are available
            if ($languages->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No languages found.',
                    'data' => [],
                ], 200);
            }

            // Format the response
            $response = [];
            foreach ($languages as $key => $language) {
                $response[$key]['id'] = $key + 1;
                $response[$key]['value'] = $language->name;
                $response[$key]['displayedValue'] = ucfirst($language->name);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Languages retrieved successfully.',
                'data' => $response,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving languages.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function courses_by_search_string(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'search_string' => 'required|string|min:1',
            ]);

            // Retrieve the search string
            $search_string = $request->search_string;

            // Fetch courses that match the search string and are active
            $courses = Course::where('title', 'LIKE', "%{$search_string}%")
                ->where('status', 'active')
                ->get();

            // Check if any courses match the search
            if ($courses->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No courses found matching the search string.',
                    'data' => [],
                ], 200);
            }

            // Format the course data
            $response = course_data($courses);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Courses retrieved successfully.',
                'data' => $response,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving courses.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function course_details_by_id(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'course_id' => 'required|exists:courses,id',
            ]);

            // Get the authenticated user (if any)
            $user = auth('api')->user();
            $user_id = $user ? $user->id : 0;

            // Retrieve course details
            $course_id = $request->course_id;
            $response = course_details_by_id($user_id, $course_id);
            $response->related_courses = course_related_category_course_for_course_details($course_id);

            // Check if the course details are available
            if (empty($response)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'Course details not found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Course details retrieved successfully.',
                'data' => $response,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving course details.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // course sections
    public function course_sections(Request $request)
    {
        try {
            // Validate course_id
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id',
            ]);

            // Check if the user is authenticated
            $user = auth('api')->user();
            $user_id = $user ? $user->id : 0; // If user is not logged in, set user_id to 0

            // Retrieve course sections
            $response = sections($request->course_id, $user_id);

            // Check if sections exist
            if (empty($response)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No sections found for this course.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Sections retrieved successfully.',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving sections.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // BootcampCategory
    public function bootcamp_categories()
    {
        try {
            // Retrieve all parent categories
            $categories = BootcampCategory::get();

            // Check if categories exist
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No categories found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Categories retrieved successfully.',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // all bootcamps
    public function all_bootcamps(Request $request)
    {
        try {
            $bootcamp_category_id = $request->bootcamp_category_id;
            if ($bootcamp_category_id == '') {
                $bootcamps = Bootcamp::get();
            } else {
                $bootcamps = Bootcamp::where('category_id', $bootcamp_category_id)->get();
            }
            foreach ($bootcamps as $bootcamp) {

                $bootcamp->thumbnail = get_photo('bootcamp_thumbnail', $bootcamp->thumbnail);
            }

            // Check if bootcamps exist
            if ($bootcamps->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No bootcamps found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'bootcamps retrieved successfully.',
                'data' => $bootcamps,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the bootcamps.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // bootcamp details
    public function bootcamp_details_by_id(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'bootcamp_id' => 'required|exists:bootcamps,id',
            ]);

            // Retrieve the bootcamp details
            $bootcamp_id = $request->bootcamp_id;
            $bootcamp = Bootcamp::find($bootcamp_id);

            if (!$bootcamp) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Bootcamp details not found.',
                    'data' => [],
                ], 200);
            }

            // Fetch bootcamp modules
            $modules = BootcampModule::where('bootcamp_id', $bootcamp->id)->get();

            // Fetch live classes for each module
            foreach ($modules as $module) {
                $module->live_classes = BootcampLiveClass::where('module_id', $module->id)->get();
                $module->resource = BootcampResource::where('module_id', $module->id)->get();
                foreach ($module->resource as $resource) {
                    $resource->file = url('public/' . $resource->file);
                }
            }

            // Attach modules with live classes to the bootcamp
            $bootcamp->modules = $modules;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Bootcamp details retrieved successfully.',
                'data' => $bootcamp,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving bootcamp details.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Tutor Category
    public function tutor_categories()
    {
        try {
            // Retrieve all parent categories
            $categories = TutorCategory::get();

            // Check if categories exist
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No categories found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Categories retrieved successfully.',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Tutor Subjects
    public function tutor_subjects()
    {
        try {
            // Retrieve all parent categories
            $subjects = TutorSubject::get();

            // Check if subjects exist
            if ($subjects->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No subjects found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'subjects retrieved successfully.',
                'data' => $subjects,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Tutor Scedules
    public function tutor_schedules()
    {
        try {
            // Retrieve all schedules
            $schedules = TutorSchedule::get();

            // Check if schedules exist
            if ($schedules->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }

            // Pluck unique tutor IDs
            $tutorIds = $schedules->pluck('tutor_id')->unique();

            // Initialize the result array
            $result = [];

            foreach ($tutorIds as $tutorId) {
                // Get user details using the API function
                $userData = get_user_info_api($tutorId);

                if ($userData) {
                    // Filter schedules for the current tutor
                    $tutorSchedules = $schedules->where('tutor_id', $tutorId);
                    $tutorReview = TutorReview::where('tutor_id', $tutorId)->get();

                    // Add tutor data and schedules to the result
                    $result[] = [
                        'tutor_id' => $tutorId,
                        'name' => $userData['name'] ?? 'N/A',
                        'image' => get_photo('user_image', $userData['photo']),
                        'educations' => $userData['educations'],
                        'intro_youtube_video' => $userData['video_url'],
                        'schedules' => $tutorSchedules->values(),
                        'tutor_reviews' => $tutorReview,
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // blogCategory
    public function blog_categories()
    {
        try {
            // Retrieve all parent categories
            $categories = BlogCategory::get();

            // Check if categories exist
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No categories found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'Categories retrieved successfully.',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // all blogs
    public function all_blogs1(Request $request)
    {
        try {
            $blog_category_id = $request->blog_category_id;
            if ($blog_category_id == '') {
                $blogs = Blog::get();
            } else {
                $blogs = Blog::where('category_id', $blog_category_id)->get();
            }

            // Check if blogs exist
            if ($blogs->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No blogs found.',
                    'data' => [],
                ], 200);
            }

            foreach ($blogs as $blog) {
                $author_details = get_user_info($blog->user_id);
                $category_details = get_category_details_by_id($blog->category_id);
                $blog->author_name = $author_details->name;
                $blog->author_image = get_photo('user_image', $author_details->photo);
                $blog->category_name = $category_details->title;
                $blog->thumbnail = get_photo('blog_thumbnail', $blog->thumbnail);
                $blog->banner = get_photo('blog_banner', $blog->banner);
                // $user->photo = get_photo('user_image', $user->photo);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'blogs retrieved successfully.',
                'data' => $blogs,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the blogs.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function all_blogs(Request $request)
    {
        try {
            $blog_category_id = $request->blog_category_id;
            $perPage = $request->limit; // If not provided, fetch all data
            $page = $request->page ?? 1;

            if (empty($blog_category_id)) {
                $query = Blog::query();
            } else {
                $query = Blog::where('category_id', $blog_category_id);
            }

            // If perPage is not set, fetch all data without pagination
            if (empty($perPage)) {
                $blogs = $query->get();
            } else {
                $blogs = $query->paginate($perPage, ['*'], 'page', $page);
            }

            // Check if blogs exist
            if ($blogs->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }

            // Transform blog data
            $formattedBlogs = $blogs->map(function ($blog) {
                $author_details = get_user_info($blog->user_id);
                $category_details = get_category_details_by_id($blog->category_id);
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author_name' => $author_details->name,
                    'author_image' => get_photo('user_image', $author_details->photo),
                    'category_name' => $category_details->title,
                    'thumbnail' => get_photo('blog_thumbnail', $blog->thumbnail),
                    'banner' => get_photo('blog_banner', $blog->banner),
                    'short_description' => $blog->short_description,
                    'description' => $blog->description,
                    'keywords' => json_decode($blog->keywords, true),
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at,
                ];
            });

            // If pagination is applied, return pagination info
            $response = [
                'status' => true,
                'status_code' => 200,
                'data' => $formattedBlogs,
            ];

            if (!empty($perPage)) {
                $response['pagination'] = [
                    'total' => $blogs->total(),
                    'limit' => $blogs->perPage(),
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'next_page_url' => $blogs->nextPageUrl(),
                    'prev_page_url' => $blogs->previousPageUrl(),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // blog details
    // public function blog_details_by_id(Request $request)
    // {
    //     try {
    //         // Validate the input
    //         $request->validate([
    //             'blog_id' => 'required|exists:blogs,id',
    //         ]);

    //         // Retrieve the blog details
    //         $blog_id = $request->blog_id;
    //         $blog = Blog::find($blog_id);

    //         if (!$blog) {
    //             return response()->json([
    //                 'status' => false,
    //                 'status_code' => 404,
    //                 // 'message' => 'blog details not found.',
    //                 'data' => [],
    //             ], 200);
    //         }
    //         // foreach ($blogs as $blog) {
    //         $blog->thumbnail = get_photo('blog_thumbnail', $blog->thumbnail);
    //         $blog->banner = get_photo('blog_banner', $blog->banner);
    //         $blog->keywords = json_decode($blog->keywords, true);
    //         // }

    //         // Fetch blog modules
    //         $blog->likes = BlogLike::where('blog_id', $blog->id)->get();
    //         $blog->comments = BlogComment::where('blog_id', $blog->id)->get();

    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             // 'message' => 'blog details retrieved successfully.',
    //             'data' => $blog,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Handle unexpected errors
    //         return response()->json([
    //             'status' => false,
    //             'status_code' => 500,
    //             // 'message' => 'An error occurred while retrieving blog details.',
    //             'error' => $e->getMessage(),
    //         ], 200);
    //     }
    // }

    public function blog_details_by_id(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'blog_id' => 'required|exists:blogs,id',
            ]);

            // Retrieve the blog details
            $blog_id = $request->blog_id;
            $blog = Blog::find($blog_id);

            if (!$blog) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }
            $author_details = get_user_info($blog->user_id);
            $category_details = get_blog_category_details_by_id($blog->category_id);
            $blog->author_name = $author_details->name;
            $blog->author_image = get_photo('user_image', $author_details->photo);
            $blog->author_designation = $author_details->designation;
            $blog->category_name = $category_details->title;
            $blog->thumbnail = get_photo('blog_thumbnail', $blog->thumbnail);
            $blog->banner = get_photo('blog_banner', $blog->banner);
            $blog->keywords = json_decode($blog->keywords, true);
            // $blog->likes = BlogLike::where('blog_id', $blog->id)->get();
            // $blog->comments = BlogComment::where('blog_id', $blog->id)->get();

            // Fetch other blogs in the same category, excluding the current blog
            $relatedBlogs = Blog::where('category_id', $blog->category_id)
                ->where('id', '!=', $blog->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($blog) {
                    $author_details = get_user_info($blog->user_id);
                    $category_details = get_blog_category_details_by_id($blog->category_id);
                    return [
                        'id' => $blog->id,
                        'title' => $blog->title,
                        'content' => $blog->content,
                        'author_name' => $author_details->name,
                        'author_image' => get_photo('user_image', $author_details->photo),
                        'author_designation' => $author_details->designation,
                        'category_name' => $category_details->title,
                        'thumbnail' => get_photo('blog_thumbnail', $blog->thumbnail),
                        'banner' => get_photo('blog_banner', $blog->banner),
                        'short_description' => $blog->short_description,
                        'description' => $blog->description,
                        'keywords' => json_decode($blog->keywords, true),
                        'created_at' => $blog->created_at,
                        'updated_at' => $blog->updated_at,
                    ];
                });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'data' => [
                    'blog' => $blog,
                    'related_blogs' => $relatedBlogs,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // all newsrooms
    public function all_newsrooms(Request $request)
    {
        try {
            $newsroom_category_id = $request->newsroom_category_id;
            $perPage = $request->limit; // If not provided, fetch all data
            $page = $request->page ?? 1;

            if (empty($newsroom_category_id)) {
                $query = Newsroom::query();
            } else {
                $query = Newsroom::where('category_id', $newsroom_category_id);
            }

            // If perPage is not set, fetch all data without pagination
            if (empty($perPage)) {
                $newsrooms = $query->get();
            } else {
                $newsrooms = $query->paginate($perPage, ['*'], 'page', $page);
            }

            // Check if newsrooms exist
            if ($newsrooms->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }

            // Transform newsroom data
            $formattednewsrooms = $newsrooms->map(function ($newsroom) {
                $author_details = get_user_info($newsroom->user_id);
                $category_details = get_newsroom_category_details_by_id($newsroom->category_id);
                return [
                    'id' => $newsroom->id,
                    'title' => $newsroom->title,
                    'content' => $newsroom->content,
                    'author_name' => $author_details->name,
                    'author_image' => get_photo('user_image', $author_details->photo),
                    'category_name' => $category_details->title,
                    'thumbnail' => get_photo('newsroom_thumbnail', $newsroom->thumbnail),
                    'banner' => get_photo('newsroom_banner', $newsroom->banner),
                    'keywords' => json_decode($newsroom->keywords, true),
                    'created_at' => $newsroom->created_at,
                    'updated_at' => $newsroom->updated_at,
                ];
            });

            // If pagination is applied, return pagination info
            $response = [
                'status' => true,
                'status_code' => 200,
                'data' => $formattednewsrooms,
            ];

            if (!empty($perPage)) {
                $response['pagination'] = [
                    'total' => $newsrooms->total(),
                    'limit' => $newsrooms->perPage(),
                    'current_page' => $newsrooms->currentPage(),
                    'last_page' => $newsrooms->lastPage(),
                    'next_page_url' => $newsrooms->nextPageUrl(),
                    'prev_page_url' => $newsrooms->previousPageUrl(),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // newsroom details

    public function newsroom_details_by_id(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'newsroom_id' => 'required|exists:newsrooms,id',
            ]);

            // Retrieve the newsroom details
            $newsroom_id = $request->newsroom_id;
            $newsroom = newsroom::find($newsroom_id);

            if (!$newsroom) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }
            $author_details = get_user_info($newsroom->user_id);
            $category_details = get_newsroom_category_details_by_id($newsroom->category_id);
            $newsroom->author_name = $author_details->name;
            $newsroom->author_image = get_photo('user_image', $author_details->photo);
            $newsroom->author_designation = $author_details->designation;
            $newsroom->category_name = $category_details->title;
            $newsroom->thumbnail = get_photo('newsroom_thumbnail', $newsroom->thumbnail);
            $newsroom->banner = get_photo('newsroom_banner', $newsroom->banner);
            $newsroom->keywords = json_decode($newsroom->keywords, true);
            // $newsroom->likes = newsroomLike::where('newsroom_id', $newsroom->id)->get();
            // $newsroom->comments = newsroomComment::where('newsroom_id', $newsroom->id)->get();

            // Fetch other newsrooms in the same category, excluding the current newsroom
            $relatednewsrooms = newsroom::where('category_id', $newsroom->category_id)
                ->where('id', '!=', $newsroom->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($newsroom) {
                    $author_details = get_user_info($newsroom->user_id);
                    $category_details = get_newsroom_category_details_by_id($newsroom->category_id);
                    return [
                        'id' => $newsroom->id,
                        'title' => $newsroom->title,
                        'content' => $newsroom->content,
                        'author_name' => $author_details->name,
                        'author_image' => get_photo('user_image', $author_details->photo),
                        'author_designation' => $author_details->designation,
                        'category_name' => $category_details->title,
                        'thumbnail' => get_photo('newsroom_thumbnail', $newsroom->thumbnail),
                        'banner' => get_photo('newsroom_banner', $newsroom->banner),
                        'keywords' => json_decode($newsroom->keywords, true),
                        'created_at' => $newsroom->created_at,
                        'updated_at' => $newsroom->updated_at,
                    ];
                });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'data' => [
                    'newsroom' => $newsroom,
                    'related_newsrooms' => $relatednewsrooms,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }
    // all learnings
    public function all_learnings(Request $request)
    {
        try {
            $learning_category_id = $request->learning_category_id;
            $perPage = $request->limit; // If not provided, fetch all data
            $page = $request->page ?? 1;

            if (empty($learning_category_id)) {
                $query = Learning::query();
            } else {
                $query = learning::where('category_id', $learning_category_id);
            }

            // If perPage is not set, fetch all data without pagination
            if (empty($perPage)) {
                $learnings = $query->get();
            } else {
                $learnings = $query->paginate($perPage, ['*'], 'page', $page);
            }

            // Check if learnings exist
            if ($learnings->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }

            // Transform learning data
            $formattedlearnings = $learnings->map(function ($learning) {
                $author_details = get_user_info($learning->user_id);
                $category_details = get_learning_category_details_by_id($learning->category_id);
                return [
                    'id' => $learning->id,
                    'title' => $learning->title,
                    'content' => $learning->content,
                    'author_name' => $author_details->name,
                    'author_image' => get_photo('user_image', $author_details->photo),
                    'category_name' => $category_details->title,
                    'thumbnail' => get_photo('learning_thumbnail', $learning->thumbnail),
                    'banner' => get_photo('learning_banner', $learning->banner),
                    'keywords' => json_decode($learning->keywords, true),
                    'created_at' => $learning->created_at,
                    'updated_at' => $learning->updated_at,
                ];
            });

            // If pagination is applied, return pagination info
            $response = [
                'status' => true,
                'status_code' => 200,
                'data' => $formattedlearnings,
            ];

            if (!empty($perPage)) {
                $response['pagination'] = [
                    'total' => $learnings->total(),
                    'limit' => $learnings->perPage(),
                    'current_page' => $learnings->currentPage(),
                    'last_page' => $learnings->lastPage(),
                    'next_page_url' => $learnings->nextPageUrl(),
                    'prev_page_url' => $learnings->previousPageUrl(),
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // learning details

    public function learning_details_by_id(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'learning_id' => 'required|exists:learnings,id',
            ]);

            // Retrieve the learning details
            $learning_id = $request->learning_id;
            $learning = learning::find($learning_id);

            if (!$learning) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }
            $author_details = get_user_info($learning->user_id);
            $category_details = get_learning_category_details_by_id($learning->category_id);
            $learning->author_name = $author_details->name;
            $learning->author_image = get_photo('user_image', $author_details->photo);
            $learning->author_designation = $author_details->designation;
            $learning->category_name = $category_details->title;
            $learning->thumbnail = get_photo('learning_thumbnail', $learning->thumbnail);
            $learning->banner = get_photo('learning_banner', $learning->banner);
            $learning->keywords = json_decode($learning->keywords, true);
            // $learning->likes = learningLike::where('learning_id', $learning->id)->get();
            // $learning->comments = learningComment::where('learning_id', $learning->id)->get();

            // Fetch other learnings in the same category, excluding the current learning
            $relatedlearnings = learning::where('category_id', $learning->category_id)
                ->where('id', '!=', $learning->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($learning) {
                    $author_details = get_user_info($learning->user_id);
                    $category_details = get_learning_category_details_by_id($learning->category_id);
                    return [
                        'id' => $learning->id,
                        'title' => $learning->title,
                        'content' => $learning->content,
                        'author_name' => $author_details->name,
                        'author_image' => get_photo('user_image', $author_details->photo),
                        'author_designation' => $author_details->designation,
                        'category_name' => $category_details->title,
                        'thumbnail' => get_photo('learning_thumbnail', $learning->thumbnail),
                        'banner' => get_photo('learning_banner', $learning->banner),
                        'keywords' => json_decode($learning->keywords, true),
                        'created_at' => $learning->created_at,
                        'updated_at' => $learning->updated_at,
                    ];
                });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'data' => [
                    'learning' => $learning,
                    'related_learnings' => $relatedlearnings,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // All settings
    public function all_settings()
    {
        try {
            // Retrieve all settings
            $settings = Setting::get();

            // Check if settings exist
            if ($settings->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'data' => [],
                ], 200);
            }

            // Decode the description field if it exists and handle invalid JSON
            foreach ($settings as $setting) {
                if (!empty($setting->description)) {
                    $decodedDescription = json_decode($setting->description, true);

                    // If JSON decoding fails, keep the original value
                    $setting->description = json_last_error() === JSON_ERROR_NONE
                        ? $decodedDescription
                        : $setting->description;
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'data' => $settings,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // One  settings
    public function one_settings(Request $request)
    {
        try {
            // Validate that the 'type' parameter is provided
            $type = $request->type;
            if (!$type) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    //    'message' => 'The "type" parameter is required.',
                    'data' => [],
                ], 200); // HTTP 200 to indicate success but invalid input
            }

            // Retrieve the settings based on type
            $settings = Setting::where('type', $type)->first();

            // Check if settings exist
            if (!$settings) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    //    'message' => 'No settings found for the given type.',
                    'data' => [],
                ], 200);
            }

            // Decode the description field if it exists
            // foreach ($settings as $setting) {
            if (!empty($settings->description)) {
                $decodedDescription = json_decode($settings->description, true);

                // If JSON decoding fails, keep the original value
                $settings->description = json_last_error() === JSON_ERROR_NONE
                    ? $decodedDescription
                    : $settings->description;
            }

            // }
            return response()->json([
                'status' => true,
                'status_code' => 200,
                //    'message' => 'Settings retrieved successfully.',
                'data' => $settings,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                //    'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // All Frontend settings
    public function all_frontend_settings()
    {
        try {
            // Retrieve all parent categories
            $settings = FrontendSetting::get();

            // Check if settings exist
            if ($settings->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No settings found.',
                    'data' => [],
                ], 200);
            }

            // Decode the description field if it exists and handle invalid JSON
            //  foreach ($settings as $setting) {
            //     if (!empty($setting->value)) {
            //         $decodedvalue = json_decode($setting->value, true);
            //          if ($setting->key == 'motivational_speech') {
            //             foreach ($decodedvalue  as $value) {
            //                 $value['image'] = get_photo('motivational_speech',$value['image']);
            //             }
            //     }
            //         // If JSON decoding fails, keep the original value
            //         $setting->value = json_last_error() === JSON_ERROR_NONE
            //             ? $decodedvalue
            //             : $setting->value;
            //     }
            //     if ($setting->key == 'banner_image') {
            //         # code...
            //         $setting->value = get_photo('banner_image',$setting->value);
            //     }elseif($setting->key == 'light_logo'){
            //         $setting->value = get_photo('light_logo',$setting->value);
            //     }elseif($setting->key == 'dark_logo'){
            //         $setting->value = get_photo('dark_logo',$setting->value);
            //     }elseif($setting->key == 'small_logo'){
            //         $setting->value = get_photo('small_logo',$setting->value);
            //     }elseif($setting->key == 'favicon'){
            //         $setting->value = get_photo('favicon',$setting->value);
            //     }
            // }

            foreach ($settings as $setting) {
                if (!empty($setting->value)) {
                    $decodedValue = json_decode($setting->value, true);

                    // Handle 'motivational_speech' setting
                    if ($setting->key == 'motivational_speech') {
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                            foreach ($decodedValue as &$value) {
                                if (isset($value['image'])) {
                                    $value['image'] = get_photo('motivational_speech', $value['image']);
                                }
                            }
                            $setting->value = $decodedValue; // Assign the modified array back
                        } else {
                            // Invalid JSON or not an array
                            $setting->value = [];
                        }
                    } else {
                        // If JSON decoding fails, keep the original value
                        $setting->value = json_last_error() === JSON_ERROR_NONE ? $decodedValue : $setting->value;
                    }
                }

                // Process other keys with images
                if ($setting->key == 'banner_image') {
                    $setting->value = get_photo('banner_image', $setting->value);
                } elseif ($setting->key == 'light_logo') {
                    $setting->value = get_photo('light_logo', $setting->value);
                } elseif ($setting->key == 'dark_logo') {
                    $setting->value = get_photo('dark_logo', $setting->value);
                } elseif ($setting->key == 'small_logo') {
                    $setting->value = get_photo('small_logo', $setting->value);
                } elseif ($setting->key == 'favicon') {
                    $setting->value = get_photo('favicon', $setting->value);
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'settings retrieved successfully.',
                'data' => $settings,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // One frontend settings
    public function one_fronted_settings(Request $request)
    {
        try {
            // Validate that the 'type' parameter is provided
            $key = $request->key;
            if (!$key) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'The "key" parameter is required.',
                    'data' => [],
                ], 200); // HTTP 200 to indicate success but invalid input
            }

            // Retrieve the settings based on key
            $setting = FrontendSetting::where('key', $key)->first();
            $setting->title = format_text_settings($setting->key);
            // Check if settings exist
            if (!$setting) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No settings found for the given type.',
                    'data' => [],
                ], 200);
            }

            //    Decode the description field if it exists
            // foreach ($settings as $setting) {
            if (!empty($setting->value)) {
                $decodedValue = json_decode($setting->value, true);

                // Handle 'motivational_speech' setting
                if ($setting->key == 'motivational_speech') {
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                        foreach ($decodedValue as &$value) {
                            if (isset($value['image'])) {
                                $value['image'] = get_photo('motivational_speech', $value['image']);
                            }
                        }
                        $setting->value = $decodedValue; // Assign the modified array back
                    } else {
                        // Invalid JSON or not an array
                        $setting->value = [];
                    }
                } else {
                    // If JSON decoding fails, keep the original value
                    $setting->value = json_last_error() === JSON_ERROR_NONE ? $decodedValue : $setting->value;
                }
            }

            if ($setting->key == 'banner_image') {
                $setting->value = get_photo('banner_image', $setting->value);
            } elseif ($setting->key == 'light_logo') {
                $setting->value = get_photo('light_logo', $setting->value);
            } elseif ($setting->key == 'dark_logo') {
                $setting->value = get_photo('dark_logo', $setting->value);
            } elseif ($setting->key == 'small_logo') {
                $setting->value = get_photo('small_logo', $setting->value);
            } elseif ($setting->key == 'favicon') {
                $setting->value = get_photo('favicon', $setting->value);
            } elseif ($setting->key == 'footer_video') {
                $setting->value = url('public/' . $setting->value);
            } elseif ($setting->key == 'banner_video') {
                $setting->value = url('public/' . $setting->value);
            } elseif ($setting->key == 'home_page_body_video') {
                $setting->value = url('public/' . $setting->value);
            }
            // }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => "{$setting->title} retrieved successfully.",
                'data' => $setting,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Instructor
    public function instructor1(Request $request)
    {
        try {
            // Retrieve the settings based on key
            $users = User::where('role', 'instructor')->get();
            foreach ($users as $user) {
                $user->photo = get_photo('user_image', $user->photo);
                $user->paymentkeys = json_decode($user->paymentkeys, true);
                $user->educations = json_decode($user->educations, true);
                $user->skills = json_decode($user->skills, true);
            }

            // Check if user exist
            if (!$users) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No user found for the given type.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'user retrieved successfully.',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function instructor(Request $request)
    {
        try {
            // Pagination parameters
            $page = $request->input('page');
            $limit = $request->input('limit');
            $categoryId = $request->input('category_id');

            // Base query
            $query = User::where('role', 'instructor');

            // Filter by category if provided
            if ($categoryId) {
                $query->whereHas('courses', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            // Fetch data
            if ($page && $limit) {
                $users = $query->paginate($limit, ['*'], 'page', $page);
                $data = $users->items();
            } else {
                $users = $query->get();
                $data = $users;
            }

            // Format data
            foreach ($data as $user) {
                $user->photo = get_photo('user_image', $user->photo);
                $user->paymentkeys = json_decode($user->paymentkeys, true);
                $user->educations = json_decode($user->educations, true);
                $user->skills = json_decode($user->skills, true);
            }

            if (count($data) === 0) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No user found for the given criteria.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Users retrieved successfully.',
                'data' => $data,
                'pagination' => $page && $limit ? [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                ] : null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the instructors.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function instructor_profile(Request $request)
    {
        try {
            // Validate the request to ensure 'instructor_id' is provided
            $request->validate([
                'instructor_id' => 'required|integer|exists:users,id',
            ]);

            $instructor_id = $request->instructor_id;

            // Fetch the instructor with necessary checks
            $user = User::where('role', 'instructor')
                ->where('id', $instructor_id)
                ->first();

            // Check if the user exists and process data
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Instructor not found.',
                    'data' => [],
                ], 200);
            }

            // Transform user data
            $user->photo = get_photo('user_image', $user->photo);
            $user->video_thumbnail = get_photo('video_thumbnail', $user->video_thumbnail);
            $user->paymentkeys = $user->paymentkeys ? json_decode($user->paymentkeys, true) : null;
            $user->educations = $user->educations ? json_decode($user->educations, true) : [];
            $user->skills = $user->skills ? json_decode($user->skills, true) : [];
            $user->students = count_student_by_instructor_api($user->id);
            $user->courses = count_course_by_instructor_api($user->id);

            // Fetch instructor-specific courses
            $courses = courses_by_instructor($user->id);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Instructor profile retrieved successfully.',
                'data' => [
                    'instructor' => $user,
                    'courses' => $courses['data'],
                    'total_courses' => $courses['total']
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the instructor profile.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // Review
    public function reviews(Request $request)
    {
        try {

            // Retrieve the settings based on key
            $reviews = UserReview::get();
            foreach ($reviews as $review) {
                $review->user_photo = get_image_by_id_api($review->user_id);
            }
            // Check if user exist
            if (!$reviews) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    //    'message' => 'No user found for the given type.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                //    'message' => 'user retrieved successfully.',
                'data' => $reviews,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                //    'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    //Protected APIs. This APIs will require Authorization.
    // My Courses API
    public function my_courses1(Request $request)
    {
        try {
            // Get the authenticated user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = $user->id;

            // Initialize an empty array to store the user's courses
            $my_courses = [];
            $my_courses_ids = Enrollment::where('user_id', $user_id)->orderBy('id', 'desc')->get();

            // Fetch course details for each enrolled course
            foreach ($my_courses_ids as $my_courses_id) {
                $course_details = Course::find($my_courses_id['course_id']);
                if ($course_details) {
                    array_push($my_courses, $course_details);
                }
            }

            // Convert the courses into a formatted array
            $my_courses = course_data($my_courses);

            // Add progress and lesson details to each course
            foreach ($my_courses as $key => $my_course) {
                if (isset($my_course['id']) && $my_course['id'] > 0) {
                    $my_courses[$key]['completion'] = round(course_progress($my_course['id'], $user_id));
                    $my_courses[$key]['total_number_of_lessons'] = count(get_lessons('course', $my_course['id']));
                    $my_courses[$key]['total_number_of_completed_lessons'] = get_completed_number_of_lesson($user_id, 'course', $my_course['id']);
                } else {
                    unset($my_courses[$key]);
                }
            }

            // Return success response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => count($my_courses) > 0
                    ? 'My Courses retrieved successfully.' : 'Your myCourse list is Empty',
                'data' => $my_courses
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your courses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function my_courses(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            $user_id = $user->id;

            // Get limit and page from request without default values
            $limit = $request->input('limit');
            $page = $request->input('page');

            $query = Enrollment::where('user_id', $user_id)->orderBy('id', 'desc');

            // Apply pagination if both limit and page are provided
            if ($limit && $page) {
                $limit = (int) $limit;
                $page = (int) $page;

                $offset = ($page - 1) * $limit;
                $total_courses = $query->count();
                $enrollments = $query->offset($offset)->limit($limit)->get();
            } else {
                // No pagination: get all
                $enrollments = $query->get();
                $total_courses = count($enrollments);
            }

            // Fetch course details
            $courses = [];
            foreach ($enrollments as $enrollment) {
                $course = Course::find($enrollment->course_id);
                if ($course) {
                    $courses[] = $course;
                }
            }

            $courses = course_data($courses);

            // Add progress and lesson stats
            foreach ($courses as $key => $course) {
                if (isset($course['id']) && $course['id'] > 0) {
                    $courses[$key]['completion'] = round(course_progress($course['id'], $user_id));
                    $courses[$key]['total_number_of_lessons'] = count(get_lessons('course', $course['id']));
                    $courses[$key]['total_number_of_completed_lessons'] = get_completed_number_of_lesson($user_id, 'course', $course['id']);
                } else {
                    unset($courses[$key]);
                }
            }

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => count($courses) > 0 ? 'My Courses retrieved successfully.' : 'Your myCourse list is empty.',
                'data' => array_values($courses),
            ];

            // Add pagination only if both are provided
            if ($limit && $page) {
                $response['pagination'] = [
                    'limit' => $limit,
                    'page' => $page,
                    'total_course' => $total_courses,
                    'total_page' => $limit > 0 ? ceil($total_courses / $limit) : 1
                ];
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your courses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // Get all the sections
    public function myCourse_sections(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id', // Ensure course_id is required, an integer, and exists
            ]);

            // Authenticate the user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Please log in.',
                    'data' => []
                ], 401);
            }

            // Get user ID and course ID
            $user_id = $user->id;
            $course_id = $request->course_id;

            // Retrieve course sections
            $response = sections($course_id, $user_id);

            // Check if sections data is available
            if (empty($response)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No sections found for this course.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Sections retrieved successfully.',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving sections.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // live class scedule of my course
    public function zoom_live_class_schedules(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Validate request
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id'
            ]);

            $classes = [];

            // Get live classes for the given course
            $live_classes = Live_class::where('course_id', $request->course_id)
                ->orderBy('class_date_and_time', 'desc')
                ->get();

            // Process each live class
            foreach ($live_classes as $key => $live_class) {
                $additional_info = json_decode($live_class->additional_info, true) ?? [];

                $classes[$key] = [
                    'class_topic' => $live_class->class_topic,
                    'provider' => $live_class->provider,
                    'note' => $live_class->note,
                    'class_date_and_time' => $live_class->class_date_and_time,
                    'meeting_id' => $additional_info['id'] ?? null,
                    'meeting_password' => $additional_info['password'] ?? null,
                    'start_url' => $additional_info['start_url'] ?? null,
                    'join_url' => $additional_info['join_url'] ?? null,
                ];
            }

            // Prepare response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => count($classes) > 0 ? 'Live classes retrieved successfully' : 'No live classes found',
                'live_classes' => $classes,
                'zoom_sdk' => get_settings('zoom_web_sdk'),
                'zoom_sdk_client_id' => get_settings('zoom_sdk_client_id'),
                'zoom_sdk_client_secret' => get_settings('zoom_sdk_client_secret'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while fetching live classes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // My course progress
    public function save_course_progress(Request $request)
    {
        $response = [];

        try {
            // Check if the user is authenticated via Passport API
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get authenticated user ID
            $user = auth('api')->user();
            $user_id = $user->id;

            // Check if lesson ID is provided
            if (empty($request->lesson_id)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400, // Bad Request
                    'message' => 'Lesson ID is required.',
                ], 400);
            }

            // Get lessons based on the provided lesson ID
            $lessons = get_lessons('lesson', $request->lesson_id);

            if (count($lessons) === 0) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404, // Not Found
                    'message' => 'Lesson not found.',
                ], 404);
            }

            // Update watch history manually for the user
            update_watch_history_manually($request->lesson_id, $lessons[0]->course_id, $user_id);

            // Get course completion data for the user
            $completion_data = course_completion_data($lessons[0]->course_id, $user_id);

            // Return the completion data along with success status
            return response()->json([
                'status' => true,
                'status_code' => 200, // OK
                'message' => 'Course progress saved successfully.',
                'completion_data' => $completion_data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An error occurred while saving course progress.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Free course enrollment
    public function free_course_enroll(Request $request)
    {
        $response = [];

        try {
            // Check if the user is authenticated via Passport API
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get authenticated user ID
            $user = auth('api')->user();
            $user_id = $user->id;

            $request->validate([
                'course_id' => 'required|integer|exists:courses,id'
            ]);

            // Check if the course exists and if it's free
            $course = Course::find($request->course_id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404, // Not Found
                    'message' => 'Course not found.',
                ], 404);
            }

            // Check if the course is free
            if ($course->is_paid == 1) {
                return response()->json([
                    'status' => false,
                    'status_code' => 403, // Forbidden
                    'message' => 'This is a paid course. You cannot enroll for free.',
                ], 403);
            }

            // Check if the user is already enrolled in the course
            $check = Enrollment::where('course_id', $request->course_id)->where('user_id', $user_id)->count();

            if ($check > 0) {
                return response()->json([
                    'status' => false,
                    'status_code' => 409, // Conflict - already enrolled
                    'message' => 'You are already enrolled in this course.',
                ], 409);
            }

            // Enroll the user in the course
            $enrollment = new Enrollment;
            $enrollment->user_id = $user_id;
            $enrollment->course_id = $request->course_id;
            $enrollment->enrollment_type = 'free';
            $enrollment->entry_date = time();
            $enrollment->expiry_date = null; // No expiry for free course
            $done = $enrollment->save();

            // Check if the enrollment was successful
            if ($done) {
                return response()->json([
                    'status' => true,
                    'status_code' => 200, // OK
                    'message' => 'Course successfully enrolled.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'status_code' => 500, // Internal Server Error
                    'message' => 'Some error occurred. Please try again.',
                ], 500);
            }
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // My wishlist API
    public function my_wishlist1(Request $request)
    {
        try {
            // Authenticate user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = $user->id;

            // Retrieve the user's wishlist
            $wishlist = Wishlist::where('user_id', $user_id)->pluck('course_id');
            $wishlists = json_decode($wishlist);

            // Prepare the response data
            if (count($wishlists) > 0) {
                $courses = Course::whereIn('id', $wishlists)->get();
                $response_data = course_data($courses);
            } else {
                $response_data = [];
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => count($response_data) > 0
                    ? 'Wishlist retrieved successfully.'
                    : 'Your wishlist is empty.',
                'data' => $response_data
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function my_wishlist(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            $user_id = $user->id;

            // Get wishlist course IDs
            $wishlist = Wishlist::where('user_id', $user_id)->pluck('course_id')->toArray();

            // If wishlist is empty, return empty data
            if (empty($wishlist)) {
                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Your wishlist is empty.',
                    'data' => []
                ], 200);
            }

            // Get pagination inputs (optional)
            $limit = $request->input('limit');
            $page = $request->input('page');

            $query = Course::whereIn('id', $wishlist);

            if ($limit && $page) {
                $limit = (int) $limit;
                $page = (int) $page;
                $offset = ($page - 1) * $limit;

                $total = $query->count();
                $courses = $query->offset($offset)->limit($limit)->get();
            } else {
                $courses = $query->get();
                $total = count($courses);
            }

            $response_data = course_data($courses);

            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => count($response_data) > 0
                    ? 'Wishlist retrieved successfully.'
                    : 'Your wishlist is empty.',
                'data' => is_array($response_data) ? array_values($response_data) : $response_data // safe handling
            ];


            if ($limit && $page) {
                $response['pagination'] = [
                    'limit' => $limit,
                    'page' => $page,
                    'total_course' => $total,
                    'total_page' => $limit > 0 ? ceil($total / $limit) : 1,
                ];
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Remove from wishlist
    public function toggle_wishlist_items(Request $request)
    {
        try {
            // Validate the course_id parameter
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id', // Ensure course_id is provided, is an integer, and exists in the database
            ]);

            // Authenticate the user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = $user->id;
            $course_id = $request->course_id;

            // Check if the course exists in the wishlist
            $check_status = Wishlist::where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->first();

            // Toggle the wishlist status
            if (empty($check_status)) {
                // Add to wishlist if not already present
                $wishlist = new Wishlist;
                $wishlist->course_id = $course_id;
                $wishlist->user_id = $user_id;
                $wishlist->save();
                $status = 'added';
                $message = 'Course added to wishlist.';
            } else {
                // Remove from wishlist if already present
                Wishlist::where('user_id', $user_id)->where('course_id', $course_id)->delete();
                $status = 'removed';
                $message = 'Course removed from wishlist.';
            }

            // Prepare the response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => $message,
                'data' => ['status' => $status]
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that might occur
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while toggling the wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // password reset
    public function update_password(Request $request)
    {
        try {
            // Authenticate user via Passport
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please log in first.'
                ], 401);
            }

            // Validate request
            $request->validate([
                'current_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            // Check if current password matches
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Current password is incorrect.'
                ], 400);
            }

            // Ensure the new password is not the same as the current password
            if ($request->current_password === $request->new_password) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'New password cannot be the same as the current password.'
                ], 400);
            }

            // Update password
            $user = User::find(auth('api')->user()->id);
            $user->password = Hash::make($request->new_password);
            $user->save();


            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Password changed successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while updating the password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // update user data


    public function update_userdata(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please log in first.'
                ], 401);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'biography' => 'nullable|string',
                'about' => 'nullable|string',
                'address' => 'nullable|string',
                'facebook' => 'nullable|url',
                'twitter' => 'nullable|url',
                'linkedin' => 'nullable|url',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $existingUser = User::find($user->id);

            $data = [
                'name' => $request->filled('name') ? $request->name : $existingUser->name,
                'biography' => $request->filled('biography') ? $request->biography : $existingUser->biography,
                'about' => $request->filled('about') ? $request->about : $existingUser->about,
                'phone' => $request->filled('phone') ? $request->phone : $existingUser->phone,
                'address' => $request->filled('address') ? $request->address : $existingUser->address,
                'website' => $request->filled('website') ? htmlspecialchars($request->website, ENT_QUOTES, 'UTF-8') : $existingUser->website,
                'instagram' => $request->filled('instagram') ? htmlspecialchars($request->instagram, ENT_QUOTES, 'UTF-8') : $existingUser->instagram,
                'facebook' => $request->filled('facebook') ? htmlspecialchars($request->facebook, ENT_QUOTES, 'UTF-8') : $existingUser->facebook,
                'twitter' => $request->filled('twitter') ? htmlspecialchars($request->twitter, ENT_QUOTES, 'UTF-8') : $existingUser->twitter,
                'linkedin' => $request->filled('linkedin') ? htmlspecialchars($request->linkedin, ENT_QUOTES, 'UTF-8') : $existingUser->linkedin,
            ];

            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if (!empty($existingUser->photo)) {
                    $oldPath = public_path($existingUser->photo);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // Upload new photo
                $file = $request->file('photo');
                $file_name = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = 'uploads/users/' . $user->role . '/' . $file_name;

                FileUploader::upload($file, $path, null, null, 300);
                $data['photo'] = $path;
            }

            User::where('id', $user->id)->update($data);

            $updated_user = User::find($user->id);
            $updated_user->photo = get_photo('user_image', $updated_user->photo);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User data updated successfully.',
                'user' => $updated_user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while updating user data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // My profile
    public function my_profile(Request $request)
    {
        try {
            $response = [];
            // Authenticate user using Passport
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please log in first.'
                ], 401);
            }
            $user->photo = get_photo('user_image', $user->photo);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User data retrive successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // $user = auth('api')->user();
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrive user data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Account disable
    public function account_disable(Request $request)
    {

        $token = $request->bearerToken();
        $response = [];

        if (isset($token) && $token != '') {
            $auth = auth('api')->user();

            $account_password = $request->get('account_password');

            // The passwords matches
            if (Hash::check($account_password, $auth->password)) {
                User::where('id', $auth->id)->update([
                    'status' => 0,
                ]);
                $response['validity'] = 1;
                $response['message'] = 'Account has been removed';
            } else {
                $response['validity'] = 0;
                $response['message'] = 'Mismatch password';
            }
        }

        return $response;
    }

    // cart list
    public function cart_list(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Include status code in the response
                    'message' => 'Unauthorized. Please log in first.',
                ], 401); // 401 Unauthorized
            }

            // Get the authenticated user
            $user = auth('api')->user();
            $cart_items = [];

            // Fetch all cart items for the user
            $cartEntries = CartItem::where('user_id', $user->id)->get();

            // Retrieve course details
            foreach ($cartEntries as $cartEntry) {
                $course_details = Course::find($cartEntry->course_id);
                if ($course_details) {
                    $cart_items[] = $course_details;
                }
            }

            // Format course data
            $cart_items = course_data($cart_items);

            return response()->json([
                'status' => true,
                'status_code' => 200, // Include status code in the response
                'message' => count($cart_items) > 0 ?
                    'Cart items retrieved successfully.' : "Your cart is empty.",
                'cart_items' => $cart_items,
            ], 200); // 200 OK

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500, // Include status code in the response
                'message' => 'An error occurred while fetching the cart.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    // Toggle from cart list
    public function toggle_cart_items(Request $request)
    {
        $response = [];

        try {
            // Check if user is authenticated via Passport API
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get the authenticated user
            $user = auth('api')->user();
            $user_id = $user->id;

            // Validate course_id
            $course_id = $request->course_id;
            if (empty($course_id)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400, // Bad Request
                    'message' => 'Course ID is required.',
                ], 400);
            }

            // Check if the course is already in the cart
            $check_status = CartItem::where('course_id', $course_id)->where('user_id', $user_id)->first();

            if (empty($check_status)) {
                // Add the course to the cart
                $cart_item = new CartItem;
                $cart_item->course_id = $course_id;
                $cart_item->user_id = $user_id;
                $cart_item->save();

                return response()->json([
                    'status' => true,
                    'status_code' => 200, // OK
                    'message' => 'Course added to the cart.',
                ], 200);
            } else {
                // Remove the course from the cart
                CartItem::where('user_id', $user_id)->where('course_id', $course_id)->delete();

                return response()->json([
                    'status' => true,
                    'status_code' => 200, // OK
                    'message' => 'Course removed from the cart.',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An error occurred while updating the cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // My bootcamp
    public function my_bootcamp(Request $request)
    {
        try {
            // Get the authenticated user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = $user->id;

            // Initialize an empty array to store the user's courses
            $my_bootcamps = [];
            $my_bootcamps_ids = BootcampPurchase::where('user_id', $user_id)->orderBy('id', 'desc')->get();

            // Fetch bootcamp details for each enrolled bootcamp
            foreach ($my_bootcamps_ids as $my_bootcamps_id) {
                $bootcamp_details = Bootcamp::find($my_bootcamps_id['bootcamp_id']);
                if ($bootcamp_details) {
                    $bootcamp_details->thumbnail = get_photo('bootcamp_thumbnail', $bootcamp_details->thumbnail);
                    array_push($my_bootcamps, $bootcamp_details);
                }
            }
            // Return success response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => count($my_bootcamps) > 0
                    ? 'My bootcamps retrieved successfully.' : 'Your mybootcamp list is Empty',
                'data' => $my_bootcamps
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your bootcamps.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // TuTor  booking Live scedule
    public function my_live_tutor_bookings()
    {
        try {
            // Ensure the user is authenticated
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = auth('api')->user()->id;

            // Get the current timestamp for today at midnight
            $todayStart = strtotime('today');
            $todayEnd = strtotime('tomorrow') - 1;

            // Retrieve active tutor bookings (for today or later)
            $my_bookings = TutorBooking::where('student_id', $user_id)
                ->where('start_time', '>=', $todayStart)
                ->orderBy('id', 'desc')
                ->get();

            foreach ($my_bookings as $book) {
                # code...
                $book->joining_data = json_decode($book->joining_data);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200, // Success
                'message' => 'Live and Upcoming Tutor bookings retrieved successfully.',
                'data' => $my_bookings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // TuTor booking archive scedule
    public function my_archive_tutor_bookings()
    {
        try {
            // Ensure the user is authenticated
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = auth('api')->user()->id;

            // Get the current timestamp for today at midnight
            $todayStart = strtotime('today');
            $todayEnd = strtotime('tomorrow') - 1;

            // Retrieve archived tutor bookings (before today)
            $my_archive_bookings = TutorBooking::where('student_id', $user_id)
                ->where('start_time', '<', $todayStart)
                ->orderBy('id', 'desc')
                ->get();
            foreach ($my_archive_bookings as $book) {
                # code...
                $book->joining_data = json_decode($book->joining_data);
            }
            return response()->json([
                'status' => true,
                'status_code' => 200, // Success
                'message' => 'Archive Tutor bookings retrieved successfully.',
                'data' => $my_archive_bookings,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // My purchase history
    public function course_purchase_history(Request $request)
    {
        try {
            // Get the authenticated user using Passport
            $user = auth('api')->user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized access. Invalid or missing token.',
                    'data' => []
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = $user->id;

            // Initialize an empty array to store the user's courses

            $purchase_history = Payment_history::where('user_id', $user_id)->orderBy('id', 'desc')->get();
            foreach ($purchase_history as $value) {
                # code...
                $course = single_course_by_id($value->course_id);
                $value->course_title = $course->title;
            }

            // Return success response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => count($purchase_history) > 0
                    ? 'My Purchase history retrieved successfully.' : 'My Purchase history list is Empty',
                'data' => $purchase_history
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving your purchase history.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function chat_list1(Request $request)
    {
        try {
            // Ensure the user is authenticated
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401, // Unauthorized
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            // Get the authenticated user's ID
            $user_id = auth('api')->user()->id;

            // Fetch chat contacts
            $contacts = Message::where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id)
                ->latest('id')
                ->pluck('sender_id', 'receiver_id')
                ->toArray();

            // Get enrolled courses and find instructors
            $enrollments = Enrollment::where('user_id', $user_id)->get();
            $my_instructors = [];

            foreach ($enrollments as $enrollment) {
                $course_details = Course::find($enrollment->course_id);
                if ($course_details) {
                    foreach (json_decode($course_details->instructor_ids, true) ?? [] as $instructor_id) {
                        if (!in_array($instructor_id, $my_instructors)) {
                            $my_instructors[] = $instructor_id;
                        }
                    }
                    if (!in_array($course_details->user_id, $my_instructors)) {
                        $my_instructors[] = $course_details->user_id;
                    }
                }
            }

            // Fetch message threads
            $message_threads = MessageThread::where('contact_one', $user_id)
                ->orWhere('contact_two', $user_id)
                ->get();


            // Fetch conversations for each thread
            $conversations = [];
            foreach ($message_threads as $thread) {
                $contactOne = User::find($thread->contact_one);
                $contactTwo = User::find($thread->contact_two);

                // Add user details to the thread
                $thread->contact_one_user = [
                    'id' => $contactOne->id ?? null,
                    'name' => $contactOne->name ?? 'Deleted User',
                    'photo' => isset($contactOne->photo) ? get_photo('user_image', $contactOne->photo) : null,
                    'role' => $contactOne->role ?? 'Deleted User'
                ];

                $thread->contact_two_user = [
                    'id' => $contactTwo->id ?? null,
                    'name' => $contactTwo->name ?? 'Deleted User',
                    'photo' => isset($contactTwo->photo) ? get_photo('user_image', $contactTwo->photo) : null,
                    'role' => $contactTwo->role ?? 'Deleted User'
                ];
                $conversations[$thread->code] = Message::where('thread_id', $thread->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($conversations[$thread->code] as $conversation) {
                    $conversation->type = "text";
                    $conversation->files = null; // Initialize as null

                    if ($conversation->message == null) {
                        $files = MediaFile::where('chat_id', $conversation->id)->get();
                        $conversation->type = "file";

                        // Create a temporary array to hold file data
                        $fileData = [];
                        foreach ($files as $file) {
                            $fileData[] = [
                                'type' => $file->file_type,
                                'media' => url('public/uploads/message/' . $thread->code . '/' . $file->file_name),
                            ];
                        }
                        $conversation->files = $fileData;
                    }
                }
            }
            // Check if an instructor is specified and create a new thread if necessary
            if ($request->has('instructor') && !empty($request->query('instructor'))) {
                $new_thread = Str::random(25);
                $instructor_id = $request->query('instructor');

                $check_thread = MessageThread::where(function ($query) use ($instructor_id, $user_id) {
                    $query->where('contact_one', $user_id)->where('contact_two', $instructor_id);
                })->orWhere(function ($query) use ($instructor_id, $user_id) {
                    $query->where('contact_two', $user_id)->where('contact_one', $instructor_id);
                })->count();

                if ($check_thread == 0) {
                    // Create a new thread
                    MessageThread::create([
                        'code' => $new_thread,
                        'contact_one' => $user_id,
                        'contact_two' => $instructor_id,
                    ]);

                    return response()->json([
                        'status' => true,
                        'status_code' => 201, // Created
                        'message' => 'New message thread created successfully.',
                        'thread_code' => $new_thread,
                        'instructor_id' => $instructor_id,
                    ], 201);
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200, // Success
                'message' => 'Chat list retrieved successfully.',
                'data' => [
                    // 'contacts' => array_keys($contacts),
                    // 'my_instructor_ids' => $my_instructors,
                    'chat_list' => $message_threads,
                    'conversations' => $conversations,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500, // Internal Server Error
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function chat_list(Request $request)
    {
        try {
            if (!auth('api')->check()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please log in first.',
                ], 401);
            }

            $user_id = auth('api')->user()->id;
            $search = $request->query('search');

            $enrollments = Enrollment::where('user_id', $user_id)->get();
            $my_instructors = [];

            foreach ($enrollments as $enrollment) {
                $course_details = Course::find($enrollment->course_id);
                if ($course_details) {
                    foreach (json_decode($course_details->instructor_ids, true) ?? [] as $instructor_id) {
                        if (!in_array($instructor_id, $my_instructors)) {
                            $my_instructors[] = $instructor_id;
                        }
                    }
                    if (!in_array($course_details->user_id, $my_instructors)) {
                        $my_instructors[] = $course_details->user_id;
                    }
                }
            }

            $message_threads = MessageThread::where(function ($query) use ($user_id) {
                $query->where('contact_one', $user_id)
                    ->orWhere('contact_two', $user_id);
            })->get();

            // Apply search filter
            if ($search) {
                $message_threads = $message_threads->filter(function ($thread) use ($user_id, $search) {
                    $other_user_id = $thread->contact_one == $user_id ? $thread->contact_two : $thread->contact_one;
                    $other_user = User::find($other_user_id);
                    return $other_user && stripos($other_user->name, $search) !== false;
                })->values();
            }

            $conversations = [];

            foreach ($message_threads as $thread) {
                $contactOne = User::find($thread->contact_one);
                $contactTwo = User::find($thread->contact_two);

                $thread->contact_one_user = [
                    'id' => $contactOne->id ?? null,
                    'name' => $contactOne->name ?? 'Deleted User',
                    'photo' => isset($contactOne->photo) ? get_photo('user_image', $contactOne->photo) : null,
                    'role' => $contactOne->role ?? 'Deleted User'
                ];
                $thread->contact_two_user = [
                    'id' => $contactTwo->id ?? null,
                    'name' => $contactTwo->name ?? 'Deleted User',
                    'photo' => isset($contactTwo->photo) ? get_photo('user_image', $contactTwo->photo) : null,
                    'role' => $contactTwo->role ?? 'Deleted User'
                ];

                $messages = Message::where('thread_id', $thread->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($messages as $message) {
                    $message->type = $message->message ? 'text' : 'file';
                    $message->sender = $message->sender_id == $user_id ? 'me' : 'you';

                    $fileData = [];

                    if ($message->type === 'file') {
                        $files = MediaFile::where('chat_id', $message->id)->get();
                        foreach ($files as $file) {
                            $fileData[] = [
                                'type' => $file->file_type,
                                'media' => url('public/uploads/message/' . $thread->code . '/' . $file->file_name),
                            ];
                        }
                    }

                    $message->files = $fileData; // ✅ Safe assignment
                }


                $conversations[$thread->code] = $messages;

                $last_message = $messages->last();
                $thread->last_message = $last_message ? [
                    'id' => $last_message->id,
                    'sender' => $last_message->sender,
                    'message' => $last_message->message ?? 'Media file',
                    'type' => $last_message->type,
                    'created_at' => $last_message->created_at,
                    'is_read' => $last_message->is_read,
                ] : null;
            }

            // Create thread with instructor if requested
            if ($request->has('instructor') && !empty($request->query('instructor'))) {
                $new_thread = Str::random(25);
                $instructor_id = $request->query('instructor');

                $check_thread = MessageThread::where(function ($query) use ($instructor_id, $user_id) {
                    $query->where('contact_one', $user_id)->where('contact_two', $instructor_id);
                })->orWhere(function ($query) use ($instructor_id, $user_id) {
                    $query->where('contact_two', $user_id)->where('contact_one', $instructor_id);
                })->count();

                if ($check_thread == 0) {
                    MessageThread::create([
                        'code' => $new_thread,
                        'contact_one' => $user_id,
                        'contact_two' => $instructor_id,
                    ]);

                    return response()->json([
                        'status' => true,
                        'status_code' => 201,
                        'message' => 'New message thread created successfully.',
                        'thread_code' => $new_thread,
                        'instructor_id' => $instructor_id,
                    ], 201);
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Chat list retrieved successfully.',
                'data' => [
                    'chat_list' => $message_threads,
                    'conversations' => $conversations,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function chat_save(Request $request)
    {

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            // 'message' => 'nullable|string',
            // 'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (empty($request->message) && !$request->hasFile('media_files')) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Message or media is required.'
            ], 422);
        }

        $sender_id = auth('api')->id();
        $receiver_id = $request->receiver_id;

        // Check for an existing thread
        $thread = MessageThread::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('contact_one', $sender_id)->where('contact_two', $receiver_id);
        })
            ->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('contact_one', $receiver_id)->where('contact_two', $sender_id);
            })
            ->first();

        // If not found, create one
        if (!$thread) {
            $thread = MessageThread::create([
                'code' => Str::random(25),
                'contact_one' => $sender_id,
                'contact_two' => $receiver_id,
            ]);
        }

        // Save the message
        $message = Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'thread_id' => $thread->id,
            'message' => $request->message,
            'read' => 0,
        ]);

        // Update thread timestamp
        $thread->touch();

        $media_files = [];

        // Handle media uploads
        if ($request->hasFile('media_files')) {
            $files = $request->file('media_files');

            foreach ($files as $file) {
                $mimeType = explode('/', $file->getClientMimeType());
                $file_type = $mimeType[0];

                if (in_array($file_type, ['image', 'video'])) {
                    $file_name = Str::random(20) . '.' . $file->extension();
                    FileUploader::upload($file, 'uploads/message/' . $thread->code . '/' . $file_name, null, null, 300);

                    $media = MediaFile::create([
                        'chat_id' => $message->id,
                        'file_name' => $file_name,
                        'file_type' => $file_type,
                    ]);

                    $media_files[] = $media;
                }
            }
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Message sent successfully.',
            'data' => [
                'thread_id' => $thread->id,
                'thread_code' => $thread->code,
                'message' => $message,
                'media' => $media_files
            ]
        ], 200);
    }

    public function newslatter_subscribe(Request $request)
    {
        try {
            $input = $request->all();

            // Recaptcha Verification
            if (get_frontend_settings('recaptcha_status') == true) {
                if (!isset($input['g-recaptcha-response']) || check_recaptcha($input['g-recaptcha-response']) == false) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 400,
                        'message' => 'Recaptcha verification failed.',
                    ], 400);
                }
            }

            // Validate email
            $validated = $request->validate([
                'email' => 'required|email|unique:newsletter_subscribers,email',
            ]);

            // Check if email is already subscribed
            if (NewsletterSubscriber::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 409, // Conflict
                    'message' => 'You have already subscribed.',
                ], 409);
            }

            // Store subscription
            NewsletterSubscriber::create(['email' => $request->email]);

            return response()->json([
                'status' => true,
                'status_code' => 200, // Created
                'message' => 'You have successfully subscribed.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function contact_us(Request $request)
    {
        try {
            $input = $request->all();

            // Recaptcha Verification (if enabled)
            if (get_frontend_settings('recaptcha_status') == true) {
                if (!isset($input['g-recaptcha-response']) || check_recaptcha($input['g-recaptcha-response']) == false) {
                    return $this->handleResponse(false, 400, 'Recaptcha verification failed.');
                }
            }

            // Check for duplicate email
            if (Contact::where('email', $request->email)->exists()) {
                return $this->handleResponse(false, 409, 'This email has been taken.');
            }

            // Validate user data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                // 'address' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
            ]);

            // Insert data using Eloquent
            Contact::create($validated);

            return $this->handleResponse(true, 200, 'Your record has been saved.');
        } catch (\Exception $e) {
            return $this->handleResponse(false, 500, 'An error occurred while processing your request.', $e->getMessage());
        }
    }

    //  Helper function for consistent response format.
    private function handleResponse($status, $status_code, $message, $error = null)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'error' => $error,
        ], $status_code);
    }

    public function courseLevels()
    {
        $levels = [
            ['id' => 1, 'name' => 'Beginner'],
            ['id' => 2, 'name' => 'Intermediate'],
            ['id' => 3, 'name' => 'Advanced'],
        ];

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Course levels retrieved successfully.',
            'data' => $levels,
        ], 200);
    }

    // Certificate list
    public function certificate(Request $request)
    {
        try {
            $limit = $request->input('limit');
            $page = $request->input('page');

            // Fields to retrieve from the database
            // $selectedFields = ['id', 'title', 'logo', 'thumbnail', 'certificate_template'];

            // If limit or page is missing, fetch all
            if (empty($limit) || empty($page)) {
                $certificates = CertificateProgram::where("status", "active")
                    // ->select($selectedFields)
                    ->get();

                // Transform only selected fields + media URLs
                $certificates->transform(function ($certificate) {
                    return [
                        'id' => $certificate->id,
                        'title' => $certificate->title,
                        'slug' => $certificate->slug,
                        'average_rating' => $certificate->average_rating,
                        'certificated_course_count' => $certificate->certificated_course_count,
                        'logo' => get_photo("certificate_logo", $certificate->logo),
                        'thumbnail' => get_photo("certificate_thumbnail", $certificate->thumbnail),
                        'certificate_template' => get_photo("certificate_template", $certificate->certificate_template),
                    ];
                });

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'All certificates retrieved successfully',
                    'data' => $certificates,
                ]);
            }

            // Else, paginate
            $certificates = CertificateProgram::where("status", "active")
                // ->select($selectedFields)
                ->paginate($limit, ['*'], 'page', $page);

            $certificates->getCollection()->transform(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'title' => $certificate->title,
                    'logo' => get_photo("certificate_logo", $certificate->logo),
                    'thumbnail' => get_photo("certificate_thumbnail", $certificate->thumbnail),
                    'certificate_template' => get_photo("certificate_template", $certificate->certificate_template),
                ];
            });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Certificates retrieved successfully',
                'data' => $certificates->items(),
                'pagination' => [
                    'limit' => (int) $limit,
                    'page' => (int) $page,
                    'total' => $certificates->total(),
                    'total_page' => $certificates->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to retrieve certificates',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Certificate details
    public function certificate_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }
        try {
            $certificate_id = $request->certificate_id;

            // Fetch the certificate with the given ID
            $certificate = CertificateProgram::where("status", "active")->where("id", $certificate_id)->first();

            // Check if certificate exists
            if (!$certificate) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Certificate not found',
                ], 404);
            }

            // Transform certificate details
            $certificateDetails = [
                'id' => $certificate->id,
                'title' => $certificate->title,
                'short_description' => $certificate->short_description,
                'description' => $certificate->description,
                'average_rating' => $certificate->average_rating,
                // 'final_question' => $certificate->final_question,
                'review' => review($certificate_id, "7", "certificate")->count(),
                'reviews' => review($certificate_id, "7", "certificate"),
                'thumbnail' => get_photo("certificate_thumbnail", $certificate->thumbnail),
                'certificate_template' => get_photo("certificate_template", $certificate->certificate_template),
            ];

            // Fetch course details
            $courseIds = json_decode($certificate->course_ids, true);
            $totalLessons = 0;
            $totalCourses = is_array($courseIds) ? count($courseIds) : 0;

            // Arrays for core and elective courses
            $coreCourses = [];
            $electiveCourses = [];
            $coreCourseCount = 0;
            $electiveCourseCount = 0;

            if ($totalCourses > 0) {
                $courseRecords = Course::whereIn('id', $courseIds)->get();

                foreach ($courseRecords as $course) {
                    $instructor_details = get_user_info($course->user_id);
                    $lessonCount = get_lessons('course', $course->id)->count();
                    $totalLessons += $lessonCount;

                    $courseData = [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'thumbnail' => get_photo('course_thumbnail', $course->thumbnail),
                        'banner' => get_photo('course_banner', $course->banner),
                        'preview' => $course->preview ? (
                            strpos($course->preview, 'youtube.com') !== false ||
                            strpos($course->preview, 'youtu.be') !== false ||
                            strpos($course->preview, 'vimeo.com') !== false ||
                            strpos($course->preview, 'drive.google.com') !== false ||
                            (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false)
                        ) ? $course->preview : url('public/' . $course->preview) : null,
                        'isPaid' => $course->is_paid,
                        'price' => currency($course->price),
                        'isDiscount' => $course->discount_flag,
                        'average_review' => $course->average_rating,
                        'review' => review($course->id, "7", "course")->count(),
                        'discount_price' => currency($course->discounted_price),
                        'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                        'lessons' => $lessonCount,
                        'instructor_name' => $instructor_details->name ?? '',
                        'instructor_image' => isset($instructor_details->photo) ? url('public/' . $instructor_details->photo) : '',
                        'course_type' => $course->certificate_course_type,
                    ];

                    // Categorize courses based on their type
                    if ($course->certificate_course_type === 'core') {
                        $coreCourses[] = $courseData;
                        $coreCourseCount++;
                    } elseif ($course->certificate_course_type === 'elective') {
                        $electiveCourses[] = $courseData;
                        $electiveCourseCount++;
                    }
                }
            }

            // Store total counts in the certificate object
            $certificateDetails['total_courses'] = $totalCourses;
            $certificateDetails['total_lessons'] = $totalLessons;

            $certificateDetails['faq'] = [
                [
                    "title" => "What prerequisites do I need for this course?",
                    "description" => "Basic knowledge of HTML, CSS, and WordPress would be beneficial. Familiarity with Bootstrap framework is also helpful but not mandatory."
                ],
                [
                    "title" => "Do I need to know PHP to take this course?",
                    "description" => "While some basic understanding of PHP would be advantageous, this course focuses primarily on integrating Bootstrap with WordPress themes. Basic PHP concepts will be explained as needed."
                ],
                [
                    "title" => "What version of Bootstrap does this course cover?",
                    "description" => "This course covers Bootstrap 5, the latest stable version at the time of creation. However, the principles taught are generally applicable to newer versions as well."
                ],
                [
                    "title" => "Will this course teach me how to create custom WordPress themes from scratch?",
                    "description" => "Yes, by the end of this course, you will have the knowledge and skills to develop custom WordPress themes using Bootstrap, tailored to your specific needs."
                ],
                [
                    "title" => "Can I use the skills learned in this course to customize existing WordPress themes?",
                    "description" => "Absolutely! The techniques taught in this course are applicable to both creating themes from scratch and customizing existing themes to better suit your requirements."
                ],
                [
                    "title" => "Do I need to have a WordPress website already set up to take this course?",
                    "description" => "It's recommended to have a basic understanding of WordPress, including how to set up a WordPress site and install themes. However, detailed instructions will be provided on how to set up a local WordPress environment for development purposes."
                ]
            ];

            $certificateDetails['skilled'] = [
                'accounting' => [
                    'name' => 'Accounting',
                    'percentage' => 50,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
                'business' => [
                    'name' => 'Business',
                    'percentage' => 10,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
                'finance' => [
                    'name' => 'finance',
                    'percentage' => 5,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
                'management' => [
                    'name' => 'management',
                    'percentage' => 20,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
                'marketing' => [
                    'name' => 'marketing',
                    'percentage' => 5,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
                'project_management' => [
                    'name' => 'project_management',
                    'percentage' => 10,
                    'description' => "Identify the key accounting standards commonly encountered by financial analysts and explain the impact on financial statements and financial models.Identify key items on the financial statements and describe the interrelationship between all the components."
                ],
            ];
            $user_1 = get_user_info(2);
            $user_1->photo = get_photo('user_image', $user_1->photo);
            $user_2 = get_user_info(3);
            $user_2->photo = get_photo('user_image', $user_2->photo);
            $user_3 = get_user_info(5);
            $user_3->photo = get_photo('user_image', $user_3->photo);
            // $certificateDetails['rating'] = 13456;
            // $certificateDetails['average_rating'] = 4.5;
            $certificateDetails['instructors'] = [
                $user_1,
                $user_2,
                $user_3
            ];
            $certificateDetails['interactive_exercise'] = 200;
            $certificateDetails['five_star_ratting'] = 20034;
            $certificateDetails['program_overview'] = "Project Management is the application of knowledge, skills, tools and techniques to project activities in order to meet project requirements. And all must be managed expertly to deliver the results on time, within budget, learning and integration that the organization needs.";
            $certificateDetails['core_courses'] = [
                'count' => $coreCourseCount,
                'courses' => $coreCourses,
            ];
            $certificateDetails['elective_courses'] = [
                'count' => $electiveCourseCount,
                'courses' => $electiveCourses,
            ];

            // Prepare response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Certificate details retrieved successfully',
                'data' => $certificateDetails,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to retrieve certificate details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function final_exam_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }
        try {
            $certificate_id = $request->certificate_id;

            // Fetch the certificate with the given ID
            $certificate = CertificateProgram::where("status", "active")->where("id", $certificate_id)->first();

            // Check if certificate exists
            if (!$certificate) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Certificate not found',
                ], 404);
            }

            // Transform certificate details
            $certificateDetails = [
                'id' => $certificate->id,
                'title' => $certificate->title,
                'average_rating' => $certificate->average_rating,
                'final_question' => $certificate->final_question,
            ];

            // Prepare response
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Certificate details retrieved successfully',
                'data' => $certificateDetails,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to retrieve certificate details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function certificate_review_store(Request $request)
    {
        // Check if user is authenticated
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'certificate_id' => 'required|exists:certificate_program,id',
            'review' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth('api')->user();

        // Store the review
        Review::insert([
            'certificate_id' => $request->certificate_id,
            'user_id' => $user->id,
            'review' => $request->review,
            'review_type' => 'certificate',
            'rating' => $request->rating,
        ]);

        // Update average rating (only for certificate-type reviews)
        $query = Review::where('certificate_id', $request->certificate_id)
            ->where('review_type', 'certificate');

        if ($query->count() > 0) {
            $total_rating = $query->sum('rating');
            $avg_rating = $total_rating / $query->count();
            CertificateProgram::where('id', $request->certificate_id)->update([
                'average_rating' => round($avg_rating),
            ]);
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Your review has been submitted successfully.',
        ]);
    }


    public function certificate_review_delete($id)
    {
        if (!auth('api')->check()) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Unauthorized.'], 401);
        }

        $userId = auth('api')->id();
        $review = Review::where('id', $id)->where('user_id', $userId)->first();

        if (!$review) {
            return response()->json(['status' => false, 'status_code' => 404, 'message' => 'Review not found.'], 404);
        }

        $review->delete();

        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Your review has been deleted.']);
    }

    public function certificate_review_update(Request $request, $id)
    {
        if (!auth('api')->check()) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Unauthorized.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'certificate_id' => 'required|exists:certificate_program,id',
            'review' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'status_code' => 422, 'errors' => $validator->errors()], 422);
        }

        $userId = auth('api')->id();
        $review = Review::where('id', $id)->where('user_id', $userId)->first();

        if (!$review) {
            return response()->json(['status' => false, 'status_code' => 404, 'message' => 'Review not found.'], 404);
        }

        $review->update([
            'certificate_id' => $request->certificate_id,
            'review' => $request->review,
            'rating' => $request->rating,
            'review_type' => 'certificate',
        ]);

        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Your review has been updated.']);
    }

    public function certificate_review_like($id)
    {
        if (!auth('api')->check()) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Unauthorized.'], 401);
        }

        $userId = auth('api')->id();

        $status = LikeDislikeReview::where('user_id', $userId)->where('review_id', $id)->first();

        if ($status) {
            if ($status->liked) {
                $status->delete();
            } else {
                $status->update(['liked' => 1, 'disliked' => 0]);
            }
        } else {
            LikeDislikeReview::create([
                'user_id' => $userId,
                'review_id' => $id,
                'liked' => 1,
            ]);
        }

        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Like status updated.']);
    }

    public function certificate_review_dislike($id)
    {
        if (!auth('api')->check()) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Unauthorized.'], 401);
        }

        $userId = auth('api')->id();

        $status = LikeDislikeReview::where('user_id', $userId)->where('review_id', $id)->first();

        if ($status) {
            if ($status->disliked) {
                $status->delete();
            } else {
                $status->update(['disliked' => 1, 'liked' => 0]);
            }
        } else {
            LikeDislikeReview::create([
                'user_id' => $userId,
                'review_id' => $id,
                'disliked' => 1,
            ]);
        }

        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Dislike status updated.']);
    }

    // Achieve certificate
    public function certificate_achieve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|exists:users,id',
            'course_ids' => 'required|array',
            'certificate_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        // Ensure the user is authenticated
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            $user_id = auth('api')->id();

            // Fetch organization_id from User table
            $user = User::find($user_id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'User not found.',
                ], 404);
            }
            $organization_id = $user->organization_id;

            // Check if certificate already exists
            $existingCertificate = MyCertificate::where('user_id', $user_id)
                ->where('certificate_id', $request->input('certificate_id'))
                ->exists();

            if ($existingCertificate) {
                return response()->json([
                    'status' => false,
                    'status_code' => 409,
                    'message' => 'Certificate already issued to this user.',
                ], 409);
            }

            // Fetch team_id from Team table (assuming a user belongs to only one team)
            // $team = Team::where('organization_id', $organization_id)->whereJsonContains('member_ids', (string) $user_id)->first();
            // $team = Team::where('organization_id', $organization_id)
            //     ->whereRaw("FIND_IN_SET(?, member_ids)", [$user_id])
            //     ->first();
            $teams = Team::where('organization_id', $organization_id)->get();
            $foundTeam = null;

            foreach ($teams as $team) {
                // Ensure member_ids is an array
                $member_ids = is_array($team->member_ids) ? $team->member_ids : json_decode($team->member_ids, true);

                if (is_array($member_ids) && in_array((string) $user_id, $member_ids)) {
                    $foundTeam = $team; // Assign team when user is found
                    break; // Stop loop since we found the team
                }
            }

            // Check if user was found in any team
            if (!$foundTeam) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => "User is not assigned to any team. User ID: {$user_id}",
                ], 404);
            }

            $team_id = $foundTeam->id;

            $course_ids = $request->input('course_ids');

            // Check course progress for each course
            $incompleteCourses = [];
            foreach ($course_ids as $course_id) {
                $progress = course_progress($course_id, $user_id); // Assume this function returns progress percentage

                if ($progress < 100) {
                    $incompleteCourses[] = [
                        'course_id' => $course_id,
                        'progress' => $progress
                    ];
                }
            }

            // If there are incomplete courses, return error with progress details
            if (!empty($incompleteCourses)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 403,
                    'message' => 'Some courses are not fully completed.',
                    'incomplete_courses' => $incompleteCourses, // Return details of incomplete courses
                ], 403);
            }


            // If all courses are 100% complete, save certificate
            MyCertificate::create([
                'user_id' => $user_id,
                'organization_id' => $organization_id,
                'team_id' => $team_id,
                'certificate_id' => $request->input('certificate_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Certificate achieved successfully!',
            ], 200);

        } catch (\Exception $e) {
            // \Log::error('Certificate Achieve Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(), // Debugging purpose (remove in production)
            ], 500);
        }

    }

    // My certificate
    public function my_certificate(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $user_id = auth('api')->id();

        // Fetch user details
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'User not found.',
            ], 404);
        }

        // Retrieve user's certificates
        $certificates = MyCertificate::where('user_id', $user_id)
            // ->with(['my_certificate', 'organization', 'team']) // Load relationships if needed
            ->get();

        if ($certificates->isEmpty()) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'No certificates found for this user.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Certificates retrieved successfully!',
            'certificates' => $certificates,
        ], 200);
    }


    public function all_subscription(Request $request)
    {
        try {
            // Get pagination parameters from request
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);

            $subscriptionPackage = SubscriptionPackage::where("status", "active")->paginate($limit, ['*'], 'page', $page);

            $subscriptionPackage->getCollection()->transform(function ($subPackage) {
                $subPackage->banner = get_photo("subscription_banner", $subPackage->banner);
                $subPackage->info = json_decode($subPackage->info);
                return $subPackage;
            });

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Subscription Package retrieved successfully',
                'data' => $subscriptionPackage->items(),
                'pagination' => [
                    'limit' => (int) $limit,
                    'page' => (int) $page,
                    'total' => $subscriptionPackage->total(),
                    'total_page' => $subscriptionPackage->lastPage(),
                ],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to retrieve subscriptionPackage',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function subscription_enrollment(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            // Explicitly assign all fields
            $data = [
                'user_id' => auth('api')->id(),
                'subscription_package_id' => $request->input('subscription_package_id'),
                'subscription_type' => $request->input('subscription_type'),
                'payment_method' => $request->input('payment_method'),
                'amount' => $request->input('amount'),
                'license_amount' => $request->input('license_amount'),
                'invoice' => $request->input('invoice'),
                'entry_date' => $request->input('entry_date'),
                'expiry_date' => $request->input('expiry_date'),
                'admin_revenue' => $request->input('admin_revenue'),
                'instructor_revenue' => $request->input('instructor_revenue'),
                'tax' => $request->input('tax'),
                'instructor_payment_status' => $request->input('instructor_payment_status'),
                'transaction_id' => $request->input('transaction_id'),
                'session_id' => $request->input('session_id'),
                'coupon' => $request->input('coupon'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert into database
            $isenrollment = SubscriptionPackageEnrollment::where('subscription_package_id', $request->subscription_package_id)->where('user_id', auth('api')->id())->first();
            if ($isenrollment) {
                $enrollData = [
                    'license_amount' => $isenrollment->license_amount + $request->license_amount,
                    'updated_at' => now(),
                ];
                $enrollment_status = $isenrollment->update($enrollData);
                if ($enrollment_status) {
                    $enrollment = $enrollData;
                } else {
                    $enrollment = $enrollment_status;
                }

                $payment_history = SubscriptionPackageHistory::create($data);
            } else {

                $enrollment = SubscriptionPackageEnrollment::create($data);
                $payment_history = SubscriptionPackageHistory::create($data);
            }

            $data = [
                'enrollment' => $enrollment,
                'payment_history' => $payment_history,
            ];

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Subscription enrolled successfully',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function my_subscription(Request $request)
    {
        // Ensure the user is authenticated
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            // Fetch the user's subscriptions from subscription_enrolments
            $subscriptions = SubscriptionPackageEnrollment::where('user_id', $user->id)
                // ->with('subscription:id,title,price,duration') // Load related subscription details
                ->get();
            foreach ($subscriptions as $subscription) {
                $subscription->subscription = SubscriptionPackage::find($subscription->subscription_package_id);
                $subscription->subscription->banner = get_photo("subscription_banner", $subscription->subscription->banner);
                $subscription->subscription->info = json_decode($subscription->subscription->info);
            }

            // Prepare response
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Subscriptions retrieved successfully',
                'data' => $subscriptions
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Failed to retrieve subscriptions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function subscription_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_package_id' => 'required',
            'user_id' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            // Check if subscription enrollment exists
            $isenrollment = SubscriptionPackageEnrollment::where('subscription_package_id', $request->subscription_package_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$isenrollment) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Subscription package not found.',
                ], 404);
            }

            // Decode license users and check limit
            $existingLicenseUsers = json_decode($isenrollment->license_user, true) ?? [];
            $licenseAmount = $isenrollment->license_amount;

            if (count($existingLicenseUsers) >= $licenseAmount) {
                return response()->json([
                    'status' => false,
                    'status_code' => 403,
                    'message' => 'License limit reached. No more users can be added, Please extend your license',
                ], 403);
            }

            // Prepare user data
            $data = [
                'name' => $request->name,
                'about' => $request->about,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'website' => $request->website,
                'linkedin' => $request->linkedin,
                'paymentkeys' => json_encode($request->paymentkeys),
                'status' => '1',
                'password' => Hash::make($request->password),
                'role' => 'student',
            ];

            if (get_settings('student_email_verification') != 1) {
                $data['email_verified_at'] = now();
            }

            // Upload photo if available
            if ($request->hasFile('photo')) {
                $path = "uploads/users/student/" . nice_file_name($request->name, $request->photo->extension());
                FileUploader::upload($request->photo, $path, 400, null, 200, 200);
                $data['photo'] = $path;
            }

            // Create user
            $user = User::create($data);

            // Send email verification if required
            if (get_settings('student_email_verification') == 1) {
                $user->sendEmailVerificationNotification();
            }

            // Append new user ID to the license user array
            $existingLicenseUsers[] = $user->id;

            // Update subscription enrollment
            $isenrollment->update([
                'license_user' => json_encode($existingLicenseUsers),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Subscription user added successfully',
                'user_id' => $user->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit_subscription_user(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'subscription_package_id' => 'required',
            'name' => 'required|max:255',
            'email' => "required|email|unique:users,email,$user_id",
            'password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            $user = User::find($user_id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'User not found.',
                ], 404);
            }

            // Fetch Subscription Enrollment
            $isenrollment = SubscriptionPackageEnrollment::where('subscription_package_id', $request->subscription_package_id)
                ->where('user_id', auth('api')->user()->id)
                ->first();

            if (!$isenrollment) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Subscription package not found.',
                ], 404);
            }

            // Decode JSON to an array
            $licenseUsers = json_decode($isenrollment->license_user, true) ?? [];

            // Check if user is part of this subscription package
            if (!in_array($user_id, $licenseUsers)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 403,
                    'message' => 'User does not belong to the given subscription package.',
                ], 403);
            }

            // Update User Details
            $user->name = $request->name;
            $user->about = $request->about;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->facebook = $request->facebook;
            $user->twitter = $request->twitter;
            $user->website = $request->website;
            $user->linkedin = $request->linkedin;
            $user->paymentkeys = json_encode($request->paymentkeys);

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('photo')) {
                $path = "uploads/users/student/" . nice_file_name($request->name, $request->photo->extension());
                FileUploader::upload($request->photo, $path, 400, null, 200, 200);
                $user->photo = $path;
            }

            $user->save();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User updated successfully',
                'user_id' => $user->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete_subscription_user($user_id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subscription_package_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        try {
            $user = User::find($user_id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'User not found.',
                ], 404);
            }

            // Fetch Subscription Enrollment
            $isenrollment = SubscriptionPackageEnrollment::where('subscription_package_id', $request->subscription_package_id)
                ->where('user_id', auth('api')->user()->id)->first();

            if (!$isenrollment) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Subscription package not found.',
                ], 404);
            }

            // Decode JSON to an array
            $licenseUsers = json_decode($isenrollment->license_user, true) ?? [];

            // Check if user is part of this subscription package
            if (!in_array($user_id, $licenseUsers)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 403,
                    'message' => 'User does not belong to the given subscription package.',
                ], 403);
            }

            // Remove user ID from the license user list
            $licenseUsers = array_filter($licenseUsers, function ($id) use ($user_id) {
                return $id != $user_id;
            });

            // Update Subscription Enrollment
            $isenrollment->update([
                'license_user' => json_encode(array_values($licenseUsers)),
                'updated_at' => now(),
            ]);

            // Delete user photo if exists
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            // Delete the user
            $user->delete();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User deleted successfully from the subscription package and database, including profile photo.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function subscription_user_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_package_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $user_id = auth('api')->user()->id;

        $isenrollment = SubscriptionPackageEnrollment::where('subscription_package_id', $request->subscription_package_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$isenrollment) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'Subscription package not found.',
            ], 404);
        }

        // Decode license_user JSON and get user details
        $licenseUserIds = json_decode($isenrollment->license_user, true) ?? [];

        if (empty($licenseUserIds)) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'No users found in this subscription package.',
            ], 404);
        }

        // Fetch users
        $users = User::whereIn('id', $licenseUserIds)->get();

        // Fetch active courses related to this subscription package
        $activeCourses = Course::where('is_certificate_course', 1)
            ->where('status', 'active')
            ->get();

        // Prepare user progress data
        $usersWithProgress = [];

        foreach ($users as $user) {
            $totalProgress = 0;
            $totalCourses = count($activeCourses);
            $userCourses = [];

            foreach ($activeCourses as $course) {
                $completion = round(course_progress($course->id, $user->id));
                $totalLessons = count(get_lessons('course', $course->id));
                $completedLessons = get_completed_number_of_lesson($user->id, 'course', $course->id);

                // Store individual course progress
                $userCourses[] = [
                    'course_id' => $course->id,
                    'certificate_course_type' => $course->certificate_course_type,
                    'course_name' => $course->title,
                    'completion_percentage' => $completion,
                    'total_number_of_lessons' => $totalLessons,
                    'total_number_of_completed_lessons' => $completedLessons,
                ];

                // Sum progress for overall percentage
                $totalProgress += $completion;
            }

            // Calculate overall progress percentage
            $overallProgress = ($totalCourses > 0) ? round($totalProgress / $totalCourses) : 0;

            // Append user with progress details
            $usersWithProgress[] = [
                'user_id' => $user->id,
                'role' => $user->role,
                'status' => $user->status,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'phone' => $user->phone,
                'website' => $user->website,
                'skills' => $user->skills,
                'facebook' => $user->facebook,
                'twitter' => $user->twitter,
                'linkedin' => $user->linkedin,
                'address' => $user->address,
                'about' => $user->about,
                'biography' => $user->biography,
                'photo' => get_photo('user_image', $user->photo),
                'email_verified_at' => $user->email_verified_at,
                'paymentkeys' => $user->paymentkeys,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'educations' => $user->educations,
                'video_url' => $user->video_url,
                // 'users' => $user,
                'overall_progress' => $overallProgress,
                'courses' => $userCourses,
            ];
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Subscription user details with progress fetched successfully.',
            'data' => $usersWithProgress,
        ], 200);
    }

    public function team_users(Request $request)
    {
        $limit = $request->input('limit');
        $page = $request->input('page');

        // Get all team member IDs
        $teams = Team::get();
        $member_ids = [];
        foreach ($teams as $team) {
            $member_ids = array_merge($member_ids, $team->member_ids);
        }

        $query = User::whereIn('id', $member_ids);

        // Use pagination if limit is specified
        if ($limit) {
            $users = $query->paginate($limit, ['*'], 'page', $page ?? 1);
            $users->getCollection()->transform(function ($user) {
                $user->photo = get_photo('user_image', $user->photo);
                return $user;
            });
        } else {
            $users = $query->get();
            $users->transform(function ($user) {
                $user->photo = get_photo('user_image', $user->photo);
                return $user;
            });
        }

        // Handle empty results
        if ($users->isEmpty()) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'No teammates available.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Team users fetched successfully.',
            'data' => $users,
        ], 200);
    }


    public function team_users_profile(Request $request)
    {
        try {
            // Validate the request to ensure 'instructor_id' is provided
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user_id = $request->user_id;

            // Fetch the instructor with necessary checks
            $user = User::where('id', $user_id)
                ->first();

            // Check if the user exists and process data
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Instructor not found.',
                    'data' => [],
                ], 200);
            }

            // Transform user data
            $user->photo = get_photo('user_image', $user->photo);
            $user->video_thumbnail = get_photo('video_thumbnail', $user->video_thumbnail);
            $user->paymentkeys = $user->paymentkeys ? json_decode($user->paymentkeys, true) : null;
            $user->educations = $user->educations ? json_decode($user->educations, true) : [];
            $user->skills = $user->skills ? json_decode($user->skills, true) : [];
            // $user->students = count_student_by_instructor_api($user->id);
            // $user->courses = count_course_by_instructor_api($user->id);

            // Fetch instructor-specific courses
            $courses = team_courses($user->id);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Instructor profile retrieved successfully.',
                'data' => [
                    'instructor' => $user,
                    'courses' => $courses['data'],
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the instructor profile.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // certified members

    public function certified_members(Request $request)
    {
        // Inputs
        $searchName = $request->input('searchName');
        $searchAddress = $request->input('searchAddress');
        $limit = $request->input('limit'); // nullable
        $page = $request->input('page');   // nullable

        // Get certified user IDs
        $memberUserIds = MyCertificate::pluck('user_id')->unique();

        // Build query
        $query = User::whereIn('id', $memberUserIds);

        // Search logic
        if ($searchName && $searchAddress) {
            // Both fields provided – use exact match AND
            $query->where('name', 'LIKE', '%' . $searchName . '%')
                ->where('address', 'LIKE', '%' . $searchAddress . '%');
        } elseif ($searchName) {
            // Only name provided – use exact match
            $query->where('name', 'LIKE', '%' . $searchName . '%');
        } elseif ($searchAddress) {
            // Only address provided – use exact match
            $query->where('address', 'LIKE', '%' . $searchAddress . '%');
        }

        // Apply pagination if limit is provided
        if ($limit) {
            $users = $query->paginate($limit, ['*'], 'page', $page ?? 1);
            $users->getCollection()->transform(function ($user) {
                $user->photo = get_photo('user_image', $user->photo);
                return $user;
            });
        } else {
            $users = $query->get();
            $users->transform(function ($user) {
                $user->photo = get_photo('user_image', $user->photo);
                return $user;
            });
        }

        // Response
        if ($users->isEmpty()) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'No certified members available.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Certified members fetched successfully.',
            'data' => $users,
        ], 200);
    }


    public function certified_members_profile(Request $request)
    {
        try {
            // Validate the request to ensure 'instructor_id' is provided
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user_id = $request->user_id;

            // Fetch the instructor with necessary checks
            $user = User::where('id', $user_id)
                ->first();

            // Check if the user exists and process data
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Instructor not found.',
                    'data' => [],
                ], 200);
            }

            // Transform user data
            $user->photo = get_photo('user_image', $user->photo);
            $user->paymentkeys = $user->paymentkeys ? json_decode($user->paymentkeys, true) : null;
            $user->educations = $user->educations ? json_decode($user->educations, true) : [];
            $user->skills = $user->skills ? json_decode($user->skills, true) : [];
            // $user->students = count_student_by_instructor_api($user->id);
            // $user->courses = count_course_by_instructor_api($user->id);

            // Fetch instructor-specific courses
            $certificate_id = MyCertificate::where('user_id', $user->id)->get();
            $certificate = [];
            foreach ($certificate_id as $cert) {
                $certificate[] = CertificateProgram::where('id', $cert->certificate_id)->first();
            }
            foreach ($certificate as $cert) {
                $cert->thumbnail = get_photo('certificate_thumbnail', $cert->thumbnail);
                $cert->certificate_template = get_photo('certificate_template', $cert->certificate_template);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Instructor profile retrieved successfully.',
                'data' => [
                    'instructor' => $user,
                    'certificate' => $certificate,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the instructor profile.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }


    public function checkCoupon(Request $request)
    {
        // $request->validate([
        //     'coupon_code' => 'required|string',
        //     'cart_total' => 'required|numeric|min:0',
        // ]);
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
            'cart_total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        try {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Coupon not found.',
                ], 404);
            }

            if (Carbon::now()->gt(Carbon::createFromTimestamp($coupon->expiry))) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Coupon has expired.',
                ], 400);
            }

            // Optional: Check usage limit, user eligibility, etc.

            $cartTotal = $request->cart_total;
            $discount = ($coupon->discount / 100) * $cartTotal;
            $newTotal = $cartTotal - $discount;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Coupon applied successfully.',
                'data' => [
                    'original_total' => round($cartTotal, 2),
                    'discount_percent' => $coupon->discount,
                    'discount_amount' => round($discount, 2),
                    'final_total' => round($newTotal, 2),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while checking the coupon.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createCheckoutSession(Request $request)
    {
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $user = auth('api')->user();
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'stripe')->first();
        $keys = json_decode($payment_gateway->keys, true);

        $stripeSecretKey = $payment_gateway->test_mode == 1
            ? $keys['secret_key']
            : $keys['secret_live_key'];

        $cartItems = CartItem::where('user_id', $user->id)->pluck('course_id');
        $items_id = $cartItems;
        $courses = $items_id;

        $gifted_user_id = '';
        if ($request->gifted_user_email) {
            $gifted_user_id = User::where('role', '!=', 'admin')
                ->where('email', $request->gifted_user_email)
                ->value('id');

            if (!$gifted_user_id) {
                return response()->json(['error' => "User email doesn't exist."], 422);
            }

            $courses = [];
            foreach ($items_id as $item) {
                if (Enrollment::where('course_id', $item)->where('user_id', $gifted_user_id)->doesntExist()) {
                    $courses[] = $item;
                }
            }

            if (count($courses) === 0) {
                return response()->json(['error' => 'User already enrolled.'], 422);
            }
        }

        $selected_courses = Course::whereIn('id', $courses)->get();
        $items = [];

        foreach ($selected_courses as $course) {
            $items[] = [
                'id' => $course->id,
                'title' => $course->title,
                'subtitle' => '',
                'price' => $course->price,
                'discount_price' => $course->discount_flag ? $course->discounted_price : 0,
            ];
        }

        $products_name = '';
        foreach ($items as $key => $value) {
            $products_name .= $key == 0 ? $value['title'] : ', ' . $value['title'];
        }
        return response()->json(['data' => $courses]);

        Stripe::setApiKey($stripeSecretKey);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => get_phrase('Purchasing') . ' ' . $products_name,
                        ],
                        'unit_amount' => round($request->payable * 100, 2),
                        'currency' => $payment_gateway->currency,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'success_url' => url('api/stripe/success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->cancel_url,
            'metadata' => [
                'user_id' => $user->id,
                // 'cart_id' => implode(',', (array) $items_id),
                'cart_id' => implode(',', $items_id->toArray()),
                'gifted_user_id' => $gifted_user_id,
                'coupon' => $request->coupon_code ?? '',
                'coupon_discount' => $request->coupon_discount ?? 0,
                'tax' => $request->tax ?? 0,
                'payable_amount' => $request->payable,
                'course_ids' => implode(',', $courses->toArray()),
                // 'course_ids' => implode(',', (array) $courses),
            ]
        ]);
        
        return response()->json(['url' => $session->url]);
        // return redirect()->away($session->url);
    }

    public function handleStripeSuccess(Request $request)
    {
        // Fetch Stripe keys and session
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'stripe')->first();
        $keys = json_decode($payment_gateway->keys, true);

        $stripeSecretKey = $payment_gateway->test_mode == 1
            ? $keys['secret_key']
            : $keys['secret_live_key'];

        Stripe::setApiKey($stripeSecretKey);

        // Retrieve Stripe session
        // $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));


        $session_id = $request->get('session_id');
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        if ($session->payment_status !== 'paid') {
            return response()->json(['error' => 'Payment not completed'], 400);
        }
        // Use data from $session->metadata instead of Laravel session
        $metadata = $session->metadata;
        dd($metadata);
        die;

        $course_ids = explode(',', $metadata->course_ids);
        $cart_ids = explode(',', $metadata->cart_id);

        foreach ($course_ids as $course_id) {
            $course = Course::find($course_id);
            $price = $course->price;
            $discount = $course->discount_flag ? $course->discounted_price : 0;

            $creator = get_course_creator_id($course_id);
            $payment = [
                'invoice' => Str::random(20),
                'course_id' => $course_id,
                'user_id' => $metadata->user_id,
                'tax' => $metadata->tax,
                'amount' => $discount ?: $price,
                'payment_type' => 'stripe',
                'coupon' => $metadata->coupon,
                'session_id' => $session_id,
            ];

            if ($creator->role === 'admin') {
                $payment['admin_revenue'] = $metadata->payable_amount;
            } else {
                $instructor_revenue = $metadata->payable_amount * (get_settings('instructor_revenue') / 100);
                $payment['instructor_revenue'] = $instructor_revenue;
                $payment['admin_revenue'] = $metadata->payable_amount - $instructor_revenue;
            }

            DB::table('payment_histories')->insert($payment);

            // Enroll
            DB::table('enrollments')->insert([
                'course_id' => $course_id,
                'user_id' => $metadata->gifted_user_id ?: $metadata->user_id,
                'enrollment_type' => 'paid',
                'entry_date' => time(),
                'expiry_date' => $course->expiry_period > 0
                    ? strtotime('+' . ($course->expiry_period * 30) . ' days')
                    : null,
            ]);
        }

        // Remove from cart
        CartItem::where('user_id', $metadata->user_id)->whereIn('course_id', $cart_ids)->delete();

        // Redirect directly to the frontend success page
        return redirect()->away($request->success_url);
    }

    public function subscriptioncreateCheckoutSession(Request $request)
    {
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $user = auth('api')->user();
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'stripe')->first();
        $keys = json_decode($payment_gateway->keys, true);

        $stripeSecretKey = $payment_gateway->test_mode == 1
            ? $keys['secret_key']
            : $keys['secret_live_key'];

        $cartItems = CartItem::where('user_id', $user->id)->pluck('course_id');
        $items_id = $cartItems;
        $courses = $items_id;

        $gifted_user_id = '';
        if ($request->gifted_user_email) {
            $gifted_user_id = User::where('role', '!=', 'admin')
                ->where('email', $request->gifted_user_email)
                ->value('id');

            if (!$gifted_user_id) {
                return response()->json(['error' => "User email doesn't exist."], 422);
            }

            $courses = [];
            foreach ($items_id as $item) {
                if (Enrollment::where('course_id', $item)->where('user_id', $gifted_user_id)->doesntExist()) {
                    $courses[] = $item;
                }
            }

            if (count($courses) === 0) {
                return response()->json(['error' => 'User already enrolled.'], 422);
            }
        }

        $selected_courses = Course::whereIn('id', $courses)->get();
        $items = [];

        foreach ($selected_courses as $course) {
            $items[] = [
                'id' => $course->id,
                'title' => $course->title,
                'subtitle' => '',
                'price' => $course->price,
                'discount_price' => $course->discount_flag ? $course->discounted_price : 0,
            ];
        }

        $products_name = '';
        foreach ($items as $key => $value) {
            $products_name .= $key == 0 ? $value['title'] : ', ' . $value['title'];
        }

        Stripe::setApiKey($stripeSecretKey);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => get_phrase('Purchasing') . ' ' . $products_name,
                        ],
                        'unit_amount' => round($request->payable * 100, 2),
                        'currency' => $payment_gateway->currency,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('subscription.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'success_url' => url('api/stripe/success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->cancel_url,
            'metadata' => [
                'user_id' => $user->id,
                // 'cart_id' => implode(',', (array) $items_id),
                'cart_id' => implode(',', $items_id->toArray()),
                'gifted_user_id' => $gifted_user_id,
                'coupon' => $request->coupon_code ?? '',
                'coupon_discount' => $request->coupon_discount ?? 0,
                'tax' => $request->tax ?? 0,
                'payable_amount' => $request->payable,
                'course_ids' => implode(',', $courses->toArray()),
                // 'course_ids' => implode(',', (array) $courses),
            ]
        ]);

        return response()->json(['url' => $session->url]);
        // return redirect()->away($session->url);
    }

    public function subscriptionhandleStripeSuccess(Request $request)
    {
        // Fetch Stripe keys and session
        $payment_gateway = DB::table('payment_gateways')->where('identifier', 'stripe')->first();
        $keys = json_decode($payment_gateway->keys, true);

        $stripeSecretKey = $payment_gateway->test_mode == 1
            ? $keys['secret_key']
            : $keys['secret_live_key'];

        Stripe::setApiKey($stripeSecretKey);

        // Retrieve Stripe session
        // $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));


        $session_id = $request->get('session_id');
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        if ($session->payment_status !== 'paid') {
            return response()->json(['error' => 'Payment not completed'], 400);
        }
        // Use data from $session->metadata instead of Laravel session
        $metadata = $session->metadata;

        $course_ids = explode(',', $metadata->course_ids);
        $cart_ids = explode(',', $metadata->cart_id);

        foreach ($course_ids as $course_id) {
            $course = Course::find($course_id);
            $price = $course->price;
            $discount = $course->discount_flag ? $course->discounted_price : 0;

            $creator = get_course_creator_id($course_id);
            $payment = [
                'invoice' => Str::random(20),
                'course_id' => $course_id,
                'user_id' => $metadata->user_id,
                'tax' => $metadata->tax,
                'amount' => $discount ?: $price,
                'payment_type' => 'stripe',
                'coupon' => $metadata->coupon,
                'session_id' => $session_id,
            ];

            if ($creator->role === 'admin') {
                $payment['admin_revenue'] = $metadata->payable_amount;
            } else {
                $instructor_revenue = $metadata->payable_amount * (get_settings('instructor_revenue') / 100);
                $payment['instructor_revenue'] = $instructor_revenue;
                $payment['admin_revenue'] = $metadata->payable_amount - $instructor_revenue;
            }

            DB::table('payment_histories')->insert($payment);

            // Enroll
            DB::table('enrollments')->insert([
                'course_id' => $course_id,
                'user_id' => $metadata->gifted_user_id ?: $metadata->user_id,
                'enrollment_type' => 'paid',
                'entry_date' => time(),
                'expiry_date' => $course->expiry_period > 0
                    ? strtotime('+' . ($course->expiry_period * 30) . ' days')
                    : null,
            ]);
        }

        // Remove from cart
        CartItem::where('user_id', $metadata->user_id)->whereIn('course_id', $cart_ids)->delete();

        // Redirect directly to the frontend success page
        return redirect()->away($request->success_url);
    }

    // ai chat api
    public function ai_chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        $apiKey = $chatgpt_api_key;

        $userMessage = $request->input('message');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                ]);

        if ($response->successful()) {
            return response()->json([
                'reply' => $response['choices'][0]['message']['content']
            ]);
        }

        return response()->json([
            'error' => 'OpenAI API failed',
            'details' => $response->json(),
        ], $response->status());
    }
    // ai flashcards api

    public function ai_flashCards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'pdf' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }
        // Parse the PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        $text = $pdf->getText();
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        // $text = substr($text, 0, 12000); // limit characters (tokens) if needed

        // Create OpenAI prompt
        $prompt = "Generate flashcards from the following text. Generate minimum 50 and maximum 500 if possible. Format each flashcard like this:\n\nFlashcard 1:\nQuestion 1: [Your Question]\nAnswer 1: [Your Answer]\n\n---\n\nText:\n" . $text;
        // Your OpenAI API Key
        $apiKey = $chatgpt_api_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a flashcard generator. Generate minimum 50 and maximum 500 if possible. Format your response using "Flashcard 1:", "Question 1:", "Answer 1:" etc.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        // Extract flashcards
        $rawText = $response['choices'][0]['message']['content'];
        $flashcards = [];

        preg_match_all('/Flashcard (\d+):\s+Question \d+: (.*?)\s+Answer \d+: (.*?)(?=(Flashcard \d+:|$))/s', $rawText, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $flashcards[] = [
                'question' => trim($match[2]),
                'answer' => trim($match[3]),
            ];
        }

        // Update Lesson record
        $lesson = Lesson::find($request->lesson_id);
        $lesson->flashcards = $flashcards; // if column is json type
        $lesson->save();

        return response()->json([
            'message' => 'Flashcards generated and saved successfully.',
            'lesson_id' => $lesson->id,
            'flashcards' => $flashcards,
        ]);
    }

    public function ai_summary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'pdf' => 'required|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        // Parse the PDF
        // $parser = new Parser();
        // $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        // $text = $pdf->getText();

        // // Optional: limit text size to avoid token overflow
        // $text = substr($text, 0, 60000);
        // Token estimation helper
        function estimateTokens($text)
        {
            return (int) (strlen($text) / 4); // rough estimate: 1 token ≈ 4 characters
        }

        // Parse the PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        $text = $pdf->getText();

        // Trim text if estimated tokens exceed 3000 (~12,000 characters)
        if (estimateTokens($text) > 3000) {
            $text = substr($text, 0, 12000);
        }


        // Create prompt
        $prompt = "Generate a clear, concise summary of the following educational content around 1000 word:\n\n" . $text;

        // Your OpenAI API Key
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        $apiKey = $chatgpt_api_key;
        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert educational content summarizer. Keep the summary concise and focused around 1000 word.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $summary = trim($response['choices'][0]['message']['content']);

        // Save to lesson
        $lesson = Lesson::find($request->lesson_id);
        $lesson->summary = $summary;
        $lesson->save();

        return response()->json([
            'message' => 'Summary generated and saved successfully.',
            'lesson_id' => $lesson->id,
            'summary' => $summary,
        ]);
    }

    // Generate MCQs from PDF
    public function generate_mcq_from_pdf(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'pdf' => 'required|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        // Function to estimate tokens (as an approximation)
        function estimateTokensM($text)
        {
            return (int) (strlen($text) / 4);
        }

        // Parse PDF and extract text
        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        $text = $pdf->getText();

        if (estimateTokensM($text) > 3000) {
            $text = substr($text, 0, 2000);
        }

        // Generate prompt for OpenAI API
        $prompt = "From the following educational content, create 50 multiple-choice questions. Each question should have 4 options (A, B, C, D) and clearly indicate the correct answer. Format them cleanly in a json formate." . $text;

        // OpenAI API Key
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');
        $apiKey = $chatgpt_api_key;
        // Make API call to OpenAI to generate MCQs
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert MCQ generator. Create high-quality multiple-choice questions with correct answers.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        $lesson = Lesson::find($request->lesson_id);
        $lesson->mcq_question = $jsonString;
        $lesson->save();

        return response()->json([
            'message' => 'Multiple Choice Questions generated and saved successfully.',
            'lesson_id' => $request->lesson_id,
            'mcqs' => json_decode($jsonString),
        ]);
    }

    // Generate free-response questions from PDF
    public function generate_free_response_from_pdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'pdf' => 'required|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 200);
        }

        function estimateTokensF($text)
        {
            return (int) (strlen($text) / 4);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        $text = $pdf->getText();
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        if (estimateTokensF($text) > 300) {
            $text = substr($text, 0, 2000);
        }

        // $prompt = "From the following educational content, create 50 free-response questions. Each question should have a short model/sample answer (2-5 sentences). Format them cleanly.\n\n" . $text;

        $apiKey = $chatgpt_api_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert free-response question writer. Create good educational questions and model answers.'],
                        ['role' => 'user', 'content' => 'From the following educational content generate 50 questions with model answers in JSON array format like [{"question": "...", "answer": "..."}]' . $text],
                    ],
                ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'OpenAI API failed',
                'details' => $response->json(),
            ], $response->status());
        }

        $rawText = $response['choices'][0]['message']['content'];

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $rawText;
        }

        $lesson = Lesson::find($request->lesson_id);
        $lesson->free_response_question = $jsonString;
        $lesson->save();

        return response()->json([
            'message' => 'Free Response Questions generated successfully.',
            'lesson_id' => $request->lesson_id,
            'free_responses' => json_decode($jsonString),
        ]);
    }


    public function audio_podcast(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audio' => 'required',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422));
        }

        // Voice to Text Conversion by ELevenlabs

        $file = $request->file('audio');
        $filePath = $file->getRealPath();
        $elevenlabs_api_key = get_settings('elevenlabs_api_key');
        $chatgpt_api_key = get_settings('chatgpt_api_key');
        $chatgpt_model = get_settings('chatgpt_model');

        $cfile = new \CURLFile($filePath, mime_content_type($filePath), basename($filePath));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.elevenlabs.io/v1/speech-to-text',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('model_id' => 'scribe_v1', 'language_code' => 'eng', 'file' => $cfile),
            CURLOPT_HTTPHEADER => array(
                'Xi-Api-Key: ' . $elevenlabs_api_key
            ),
        ));

        $response = json_decode(curl_exec($curl));
        if (curl_errno($curl)) {
            $info['status'] = 'failed';
            $info['details'] = curl_errno($curl);
            return response()->json($info);

        }
        curl_close($curl);

        if (!isset($response->text)) {
            $info['status'] = 'failed';
            $info['details'] = 'Elevenlabs Api failed!';
            return response()->json($info);
        }

        $question = isset($response->text) ? $response->text : null;

        if (empty($question)) {
            $info['status'] = 'failed';
            $info['details'] = 'Elevenlabs Api failed.';
            return response()->json($info);
        }


        // Qustion Ask To OpenAI

        $apiKey = $chatgpt_api_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->withOptions(["verify" => false])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $chatgpt_model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'system', 'content' => $question],
                    ],
                ]);

        $answer = null;
        if ($response->successful()) {
            $answer = $response['choices'][0]['message']['content'];
        } else {

            $info['status'] = 'failed';
            $info['details'] = 'OpenAI Api failed';
            return response()->json($info);

        }

        // Text To Voice Conversion by ELevenlabs
        if ($answer) {

            $url = "https://api.elevenlabs.io/v1/text-to-speech/JBFqnCBsd6RMkjVDRZzb?output_format=mp3_44100_128";

            $headers = [
                "Xi-Api-Key: $elevenlabs_api_key",
                "Content-Type: application/json"
            ];

            $data = [
                "text" => $answer,
                "model_id" => "eleven_multilingual_v2"
            ];

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);

            if (curl_errno($ch)) {

                $info['status'] = 'failed';
                $info['details'] = curl_error($ch);
                return response()->json($info);

            } else {

                // Save response to file
                $filename = 'answer_' . time() . '.mp3';
                file_put_contents(public_path('uploads/audio/' . $filename), $response);

                $info['status'] = 'success';
                $info['details'] = url('public/uploads/audio/' . $filename);

                return response()->json($info);

            }

            curl_close($ch);

        }
    }

    // All Frontend settings
    public function all_dynamic_pages()
    {
        try {
            // Retrieve all parent categories
            $settings = DB::table("dynamic_pages")
                //    ->select('key', 'value')
                ->get();

            // Check if settings exist
            if ($settings->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    // 'message' => 'No settings found.',
                    'data' => [],
                ], 200);
            }

            //    foreach ($settings as $setting) {
            //        if (!empty($setting->value)) {
            //            $decodedValue = json_decode($setting->value, true);

            //            // Handle 'motivational_speech' setting
            //            if ($setting->key == 'motivational_speech') {
            //                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
            //                    foreach ($decodedValue as &$value) {
            //                        if (isset($value['image'])) {
            //                            $value['image'] = get_photo('motivational_speech', $value['image']);
            //                        }
            //                    }
            //                    $setting->value = $decodedValue; // Assign the modified array back
            //                } else {
            //                    // Invalid JSON or not an array
            //                    $setting->value = [];
            //                }
            //            } else {
            //                // If JSON decoding fails, keep the original value
            //                $setting->value = json_last_error() === JSON_ERROR_NONE ? $decodedValue : $setting->value;
            //            }
            //        }

            // Process other keys with images
            //    if ($setting->key == 'banner_image') {
            //        $setting->value = get_photo('banner_image', $setting->value);
            //    } elseif ($setting->key == 'light_logo') {
            //        $setting->value = get_photo('light_logo', $setting->value);
            //    } elseif ($setting->key == 'dark_logo') {
            //        $setting->value = get_photo('dark_logo', $setting->value);
            //    } elseif ($setting->key == 'small_logo') {
            //        $setting->value = get_photo('small_logo', $setting->value);
            //    } elseif ($setting->key == 'favicon') {
            //        $setting->value = get_photo('favicon', $setting->value);
            //    }
            //    }


            return response()->json([
                'status' => true,
                'status_code' => 200,
                // 'message' => 'settings retrieved successfully.',
                'data' => $settings,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'status_code' => 500,
                // 'message' => 'An error occurred while retrieving the categories.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    // One frontend settings
    public function one_dynamic_pages1(Request $request)
    {
        try {
            $key = $request->key;
            if (!$key) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'The "key" parameter is required.',
                    'data' => [],
                ], 200);
            }

            // Retrieve the setting based on the key
            $setting = DB::table("dynamic_pages")->where('key', $key)->first();

            // Check if setting exists BEFORE trying to access it
            if (!$setting) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No settings found for the given key.',
                    'data' => [],
                ], 200);
            }

            $setting->title = format_text_settings($setting->key);
            $setting->value = $setting->value ? json_decode($setting->value) : $setting->value;
            // $setting->value = $setting->value ;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => "{$setting->title} retrieved successfully.",
                'data' => $setting,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function one_dynamic_pages(Request $request)
    {
        try {
            $key = $request->key;
            if (!$key) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'The "key" parameter is required.',
                    'data' => [],
                ], 200);
            }

            $setting = DB::table("dynamic_pages")->where('key', $key)->first();

            if (!$setting) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No settings found for the given key.',
                    'data' => [],
                ], 200);
            }

            $setting->title = format_text_settings($setting->key);

            // Decode and format URLs if value is present
            $setting->value = $setting->value ? json_decode($setting->value, true) : $setting->value;

            if (is_array($setting->value)) {
                $setting->value = $this->addPublicUrlToAssets($setting->value);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => "{$setting->title} retrieved successfully.",
                'data' => $setting,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the settings.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    private function addPublicUrlToAssets($data)
    {
        $baseUrl = asset(''); // get your full public URL

        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                // Check if it's an array of images
                if (in_array($key, ['thumbnail', 'thumbnail_video', 'logo', 'logo_1', 'thumbnail_1'])) {
                    foreach ($value as &$v) {
                        if (is_string($v)) {
                            $v = $baseUrl . ltrim($v, '/');
                        }
                    }
                } else {
                    // Recursively call if it's nested array
                    $value = $this->addPublicUrlToAssets($value);
                }
            } elseif (in_array($key, ['thumbnail', 'thumbnail_video', 'logo', 'logo_1', 'thumbnail_1']) && is_string($value)) {
                $value = $baseUrl . ltrim($value, '/');
            }
        }

        return $data;
    }

    public function student_list(Request $request)
    {
        try {

            // Base query
            $data = User::where('role', 'student')->get();




            // Format data
            foreach ($data as $user) {
                $user->photo = get_photo('user_image', $user->photo);
                $user->paymentkeys = json_decode($user->paymentkeys, true);
                $user->educations = json_decode($user->educations, true);
                $user->skills = json_decode($user->skills, true);
            }

            if (count($data) === 0) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No user found for the given criteria.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Users retrieved successfully.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An error occurred while retrieving the instructors.',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function homePage(Request $request)
    {
        $setting1 = FrontendSetting::where('key', "instructor_graduated_form")->first();
        $setting2 = FrontendSetting::where('key', "work_experience")->first();
        $setting3 = FrontendSetting::where('key', "certified_professionals")->first();
        $setting4 = FrontendSetting::where('key', "features")->first();
        $setting5 = FrontendSetting::where('key', "banner_title")->first();
        $setting6 = FrontendSetting::where('key', "banner_sub_title")->first();
        $setting7 = FrontendSetting::where('key', "home_page_body_video")->first();
        $setting8 = FrontendSetting::where('key', "website_faqs")->first();
        $setting9 = FrontendSetting::where('key', "banner_video")->first();

        $certificates = [];
        $certificate = CertificateProgram::where("status", "active")->get();
        foreach ($certificate as $key => $value) {
            $certificates[$key]['id'] = $value->id;
            $certificates[$key]['title'] = $value->title;
            // $certificates[$key]['image'] = $value->image ? asset($value->image) : null;
        }

        $categories = Category::where('parent_id', 0)->get();
        // Format categories data
        $all_categories = [];
        foreach ($categories as $key => $category) {
            $all_categories[$key]['id'] = $category->id;
            $all_categories[$key]['title'] = $category->title;
            $all_categories[$key]['category_logo'] = get_photo('category_logo', $category['category_logo']);
            $all_categories[$key]['description'] = $category->description;
        }

        $data = User::where('role', 'instructor')->get();
        $instructors = [];

        // Format data
        foreach ($data as $key => $user) {
            $instructors[$key] = $user;
            $instructors[$key]['photo'] = get_photo('user_image', $user->photo);
            $instructors[$key]['educations'] = json_decode($user->educations, true);
            $instructors[$key]['skills'] = json_decode($user->skills, true);
        }

        // Helper function to update image URLs
        $mapImageUrls = function ($item) {
            if (isset($item['thumbnail']) && is_array($item['thumbnail'])) {
                $item['thumbnail'] = array_map(function ($img) {
                    return asset($img);
                }, $item['thumbnail']);
            }
            return $item;
        };

        $mapFeatureImages = function ($features) {
            return array_map(function ($feature) {
                $feature['logo'] = asset($feature['logo']);
                return $feature;
            }, $features);
        };

        $homeData = [
            'title' => $setting5->value,
            'subtitle' => $setting6->value,
            'instructor_graduated_form' => $mapImageUrls(json_decode($setting1->value, true)),
            'work_experience' => $mapImageUrls(json_decode($setting2->value, true)),
            'certified_professionals' => $mapImageUrls(json_decode($setting3->value, true)),
            'certificates' => $certificates,
            'certified_users' => 5000,
            'home_page_body_video' => asset($setting7->value),
            'banner_video' => asset($setting9->value),
            'categories' => $all_categories,
            'instructors' => $instructors,
            'features' => $mapFeatureImages(json_decode($setting4->value, true)),
            'website_faqs' => json_decode($setting8->value, true),
        ];

        return $homeData;
    }

    public function update_watch_history_with_duration(Request $request)
    {
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $userId = auth('api')->user()->id;  // Get the logged-in user's ID
        $courseProgress = 0;
        $isCompleted = 0;

        // Retrieve and sanitize input data
        $courseId = htmlspecialchars($request->input('course_id'));
        $lessonId = htmlspecialchars($request->input('lesson_id'));
        $currentDuration = htmlspecialchars($request->input('current_duration'));

        // Fetch current watch history record
        $currentHistory = DB::table('watch_durations')
            ->where([
                'watched_course_id' => $courseId,
                'watched_lesson_id' => $lessonId,
                'watched_student_id' => $userId,
            ])
            ->first();

        // Fetch course details
        $courseDetails = DB::table('courses')->where('id', $courseId)->first();
        $dripContentSettings = json_decode($courseDetails->drip_content_settings, true);

        if ($currentHistory) {
            $watchedDurationArr = json_decode($currentHistory->watched_counter, true);
            if (!is_array($watchedDurationArr)) $watchedDurationArr = [];

            if (!in_array($currentDuration, $watchedDurationArr)) {
                array_push($watchedDurationArr, $currentDuration);
            }

            $watchedDurationJson = json_encode($watchedDurationArr);

            DB::table('watch_durations')
                ->where([
                    'watched_course_id' => $courseId,
                    'watched_lesson_id' => $lessonId,
                    'watched_student_id' => $userId,
                ])
                ->update([
                    'watched_counter' => $watchedDurationJson,
                    'current_duration' => $currentDuration,
                ]);
        } else {
            $watchedDurationArr = [$currentDuration];
            DB::table('watch_durations')->insert([
                'watched_course_id' => $courseId,
                'watched_lesson_id' => $lessonId,
                'watched_student_id' => $userId,
                'current_duration' => $currentDuration,
                'watched_counter' => json_encode($watchedDurationArr),
            ]);
        }

        if ($courseDetails->enable_drip_content != 1) {
            return response()->json([
                'lesson_id' => $lessonId,
                'course_progress' => null,
                'is_completed' => null
            ]);
        }

        // Fetch lesson details for duration calculations
        $lessonTotalDuration = DB::table('lessons')->where('id', $lessonId)->value('duration');
        $lessonTotalDurationArr = explode(':', $lessonTotalDuration);
        $lessonTotalSeconds = ($lessonTotalDurationArr[0] * 3600) + ($lessonTotalDurationArr[1] * 60) + $lessonTotalDurationArr[2];
        $currentTotalSeconds = count($watchedDurationArr) * 5;  // Assuming each increment represents 5 seconds

        // Drip content completion logic
        if ($dripContentSettings['lesson_completion_role'] == 'duration') {
            if ($currentTotalSeconds >= $dripContentSettings['minimum_duration']) {
                $isCompleted = 1;
            } elseif (($currentTotalSeconds + 4) >= $lessonTotalSeconds) {
                $isCompleted = 1;
            }
        } else {
            $requiredDuration = ($lessonTotalSeconds / 100) * $dripContentSettings['minimum_percentage'];
            if ($currentTotalSeconds >= $requiredDuration) {
                $isCompleted = 1;
            } elseif (($currentTotalSeconds + 4) >= $lessonTotalSeconds) {
                $isCompleted = 1;
            }
        }

        // Update course progress if the lesson is completed
        if ($isCompleted == 1) {
            $watchHistory = DB::table('watch_histories')
                ->where([
                    'course_id' => $courseId,
                    'student_id' => $userId,
                ])
                ->first();

            if ($watchHistory) {
                $lessonIds = json_decode($watchHistory->completed_lesson, true);
                $courseProgress = $watchHistory->course_progress;

                if (!is_array($lessonIds)) $lessonIds = [];

                if (!in_array($lessonId, $lessonIds)) {
                    array_push($lessonIds, $lessonId);
                    $totalLesson = DB::table('lessons')->where('course_id', $courseId)->count();
                    $courseProgress = (100 / $totalLesson) * count($lessonIds);

                    $completedDate = ($courseProgress >= 100 && !$watchHistory->completed_date)
                        ? time()
                        : $watchHistory->completed_date;

                    DB::table('watch_histories')
                        ->where('id', $watchHistory->id)
                        ->update([
                            'course_progress' => $courseProgress,
                            'completed_lesson' => json_encode($lessonIds),
                            'completed_date' => $completedDate,
                        ]);
                }
            }
        }

        // Return the response
        return response()->json([
            'lesson_id' => $lessonId,
            'course_progress' => round($courseProgress),
            'is_completed' => $isCompleted,
        ]);
    }
    public function update_watch_duration(Request $request)
    {
        $response = array();
        $token = $request->bearerToken();

        if (isset($token) && $token != '') {
            $userId = auth('sanctum')->user()->id;
            $courseProgress = 0;
            $isCompleted = 0;

            // Retrieve and sanitize input data
            $courseId = htmlspecialchars($request->input('course_id'));
            $lessonId = htmlspecialchars($request->input('lesson_id'));
            $currentDuration = htmlspecialchars($request->input('current_duration'));

            // Fetch current watch history record
            $currentHistory = DB::table('watch_durations')
                ->where([
                    'watched_course_id' => $courseId,
                    'watched_lesson_id' => $lessonId,
                    'watched_student_id' => $userId,
                ])
                ->first();

            // Fetch course details
            $courseDetails = DB::table('courses')->where('id', $courseId)->first();
            $dripContentSettings = json_decode($courseDetails->drip_content_settings, true);

            if ($currentHistory) {
                $watchedDurationArr = json_decode($currentHistory->watched_counter, true);
                if (!is_array($watchedDurationArr)) $watchedDurationArr = [];

                if (!in_array($currentDuration, $watchedDurationArr)) {
                    array_push($watchedDurationArr, $currentDuration);
                }

                $watchedDurationJson = json_encode($watchedDurationArr);

                DB::table('watch_durations')
                    ->where([
                        'watched_course_id' => $courseId,
                        'watched_lesson_id' => $lessonId,
                        'watched_student_id' => $userId,
                    ])
                    ->update([
                        'watched_counter' => $watchedDurationJson,
                        'current_duration' => $currentDuration,
                    ]);
            } else {
                $watchedDurationArr = [$currentDuration];
                DB::table('watch_durations')->insert([
                    'watched_course_id' => $courseId,
                    'watched_lesson_id' => $lessonId,
                    'watched_student_id' => $userId,
                    'current_duration' => $currentDuration,
                    'watched_counter' => json_encode($watchedDurationArr),
                ]);
            }

            if ($courseDetails->enable_drip_content != 1) {
                return response()->json([
                    'lesson_id' => $lessonId,
                    'course_progress' => null,
                    'is_completed' => null
                ]);
            }

            // Fetch lesson details for duration calculations
            $lessonTotalDuration = DB::table('lessons')->where('id', $lessonId)->value('duration');
            $lessonTotalDurationArr = explode(':', $lessonTotalDuration);
            $lessonTotalSeconds = ($lessonTotalDurationArr[0] * 3600) + ($lessonTotalDurationArr[1] * 60) + $lessonTotalDurationArr[2];
            $currentTotalSeconds = count($watchedDurationArr) * 5;  // Assuming each increment represents 5 seconds

            // Drip content completion logic
            if ($dripContentSettings['lesson_completion_role'] == 'duration') {
                if ($currentTotalSeconds >= $dripContentSettings['minimum_duration']) {
                    $isCompleted = 1;
                } elseif (($currentTotalSeconds + 4) >= $lessonTotalSeconds) {
                    $isCompleted = 1;
                }
            } else {
                $requiredDuration = ($lessonTotalSeconds / 100) * $dripContentSettings['minimum_percentage'];
                if ($currentTotalSeconds >= $requiredDuration) {
                    $isCompleted = 1;
                } elseif (($currentTotalSeconds + 4) >= $lessonTotalSeconds) {
                    $isCompleted = 1;
                }
            }

            // Update course progress if the lesson is completed
            if ($isCompleted == 1) {
                $watchHistory = DB::table('watch_histories')
                    ->where([
                        'course_id' => $courseId,
                        'student_id' => $userId,
                    ])
                    ->first();

                if ($watchHistory) {
                    $lessonIds = json_decode($watchHistory->completed_lesson, true);
                    $courseProgress = $watchHistory->course_progress;

                    if (!is_array($lessonIds)) $lessonIds = [];

                    if (!in_array($lessonId, $lessonIds)) {
                        array_push($lessonIds, $lessonId);
                        $totalLesson = DB::table('lessons')->where('course_id', $courseId)->count();
                        $courseProgress = (100 / $totalLesson) * count($lessonIds);

                        $completedDate = ($courseProgress >= 100 && !$watchHistory->completed_date)
                            ? time()
                            : $watchHistory->completed_date;

                        DB::table('watch_histories')
                            ->where('id', $watchHistory->id)
                            ->update([
                                'course_progress' => $courseProgress,
                                'completed_lesson' => json_encode($lessonIds),
                                'completed_date' => $completedDate,
                            ]);
                    }
                }
            }

            // Return the response
            return response()->json([
                'lesson_id' => $lessonId,
                'course_progress' => round($courseProgress),
                'is_completed' => $isCompleted,
            ]);
        }  else {
            $response['status'] = false;
            $response['message'] = "Undefined authentication";
        }

        return $response;
    }
    public function payment2(Request $request)
    {
        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }

        $user_id = auth('api')->user()->id;

        $identifier = $request->identifier;

        $url = url("payment/create/" . $identifier);

        return $url;

    }

    public function payment(Request $request)
    {

        if (!auth('api')->check()) {
            return response()->json([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized. Please log in first.',
            ], 401);
        }
        $user = auth('api')->user();
        // Get the cart items
        $cartItems = CartItem::where('user_id', auth('api')->user()->id)->pluck('course_id');
        // Add both cart items and user details to the request
        $request->merge([
            'CartItem' => json_encode($cartItems),
            'auth_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo ?? null,
            ],
        ]);
        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }


        // Call the payout() method from another controller
        $purchaseController = new PurchaseController();
        $response = $purchaseController->payout($request);

        // If payout redirects back due to error, you can handle that:
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong in payout.',
            ], 400);
        }

        // If everything is fine, return the payment URL
        $identifier = $request->identifier;
        $url = url("payment/create/" . $identifier);

        return response()->json([
            'status' => true,
            'payment_url' => $url,
        ]);
    }

    public function saveNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Insert data directly into the database
        DB::table('notification_test')->insert([
            'title' => $request->title,
            'description' => $request->description,

        ]);

        return response()->json([
            'message' => 'Notification saved successfully'
        ], 201);
    }

    public function fetchNotifications()
    {
        // Fetch all notifications from the database
        $notifications = DB::table('notification_test')
            ->orderBy('id', 'desc') // Get the latest notifications first
            ->get();

        return response()->json($notifications, 200);
    }

    public function payment1(Request $request)
    {
        $response = [];
        $token = $request->bearerToken();

        if (isset($token) && $token != '') {
            $user = auth('api')->user();
            Auth::login($user);
        }

        if ($request->app_url) {
            session(['app_url' => $request->app_url . '://']);
        }

        return redirect(route('payment'));
        // return $response;
    }

    public function cart_tools(Request $request)
    {
        $response = [];
        $token = $request->bearerToken();

        if (isset($token) && $token != '') {
            $response['course_selling_tax'] = get_settings('course_selling_tax');
            $response['currency_position'] = get_settings('currency_position');
            $response['currency_symbol'] = DB::table('currencies')->where('code', get_settings('system_currency'))->value('symbol');
        } else {
            $response['status'] = 'Not Authorized Credential';
        }

        return $response;
    }

}
