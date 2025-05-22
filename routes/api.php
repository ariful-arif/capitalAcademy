<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\CourseController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiReactController;
use App\Http\Controllers\Api\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refreshToken']);
Route::post('loginViaApi', [AuthController::class, 'loginViaApi']);
Route::post('generateLoginLink', [AuthController::class, 'generateLoginLink']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Route::post('/login', [ApiController::class, 'login']);
// Route::post('/signup', [ApiController::class, 'signup']);
Route::post('/forgot_password', [ApiController::class, 'forgot_password']);
Route::post('/verify_otp', [ApiController::class, 'verify_otp']);
Route::post('/reset_password', [ApiController::class, 'reset_password']);

Route::get('/top_courses', [ApiController::class, 'top_courses']);
Route::get('/all_categories', [ApiController::class, 'all_categories']);
Route::get('/categories', [ApiController::class, 'categories']);
// Route::get('/categories', [ApiController::class, 'categories']);
// Route::get('/category_details', [ApiController::class, 'category_details']);
// Route::get('/sub_categories/{id}', [ApiController::class, 'sub_categories']);
// Route::get('/category_wise_course', [ApiController::class, 'category_wise_course']);
Route::get('/languages', [ApiController::class, 'languages']);
Route::get('/category_subcategory_wise_course', [ApiController::class, 'category_subcategory_wise_course']);
Route::get('/filter_course', [ApiController::class, 'filter_course']);
Route::get('/courses_by_search_string', [ApiController::class, 'courses_by_search_string']);
Route::get('/all_course', [ApiController::class, 'all_course']);
Route::get('/course_sections', [ApiController::class, 'course_sections']);
Route::get('/course_details_by_id', [ApiController::class, 'course_details_by_id']);

Route::get('/bootcamp_categories', [ApiController::class, 'bootcamp_categories']);
Route::get('/all_bootcamps', [ApiController::class, 'all_bootcamps']);
Route::get('/bootcamp_details_by_id', [ApiController::class, 'bootcamp_details_by_id']);

// Route::get('/tutor_categories', [ApiController::class, 'tutor_categories']);
// Route::get('/tutor_subjects', [ApiController::class, 'tutor_subjects']);
// Route::get('/tutor_schedules', [ApiController::class, 'tutor_schedules']);

Route::get('/blog_categories', [ApiController::class, 'blog_categories']);
Route::get('/all_blogs', [ApiController::class, 'all_blogs']);
Route::get('/blog_details_by_id', [ApiController::class, 'blog_details_by_id']);

// Route::get('/newsroom_categories', [ApiController::class, 'newsroom_categories']);
Route::get('/all_newsrooms', [ApiController::class, 'all_newsrooms']);
Route::get('/newsroom_details_by_id', [ApiController::class, 'newsroom_details_by_id']);

Route::get('/all_learnings', [ApiController::class, 'all_learnings']);
Route::get('/learning_details_by_id', [ApiController::class, 'learning_details_by_id']);

Route::get('/all_settings', [ApiController::class, 'all_settings']);
Route::get('/one_settings', [ApiController::class, 'one_settings']);

Route::get('/all_frontend_settings', [ApiController::class, 'all_frontend_settings']);
Route::get('/one_fronted_settings', [ApiController::class, 'one_fronted_settings']);



Route::get('/instructor', [ApiController::class, 'instructor']);
Route::get('/instructor_profile', [ApiController::class, 'instructor_profile']);

Route::get('/reviews', [ApiController::class, 'reviews']);



// Route::middleware('auth:api')->group(function(){
Route::get('/my_courses', [ApiController::class, 'my_courses']);
Route::get('/myCourse_sections', [ApiController::class, 'myCourse_sections']);
Route::get('myCourse_zoom_live_class', [ApiController::class, 'zoom_live_class_schedules']);
Route::get('/save_course_progress', [ApiController::class, 'save_course_progress']);
Route::get('free_course_enroll', [ApiController::class, 'free_course_enroll']);

Route::get('/my_wishlist', [ApiController::class, 'my_wishlist']);
Route::get('/toggle_wishlist_items', [ApiController::class, 'toggle_wishlist_items']);

Route::post('/update_password', [ApiController::class, 'update_password']);
Route::get('/my_profile', [ApiController::class, 'my_profile']);
Route::post('/update_userdata', [ApiController::class, 'update_userdata']);
Route::post('/account_disable', [ApiController::class, 'account_disable']); //not deliver

Route::get('/cart_list', [ApiController::class, 'cart_list']);
Route::get('/toggle_cart_items', [ApiController::class, 'toggle_cart_items']);
// Route::post('/logout', [ApiController::class, 'logout']);

Route::get('/my_bootcamp', [ApiController::class, 'my_bootcamp']);

Route::get('/my_live_tutor_bookings', [ApiController::class, 'my_live_tutor_bookings']);
Route::get('/my_archive_tutor_bookings', [ApiController::class, 'my_archive_tutor_bookings']);

Route::get('/course_purchase_history', [ApiController::class, 'course_purchase_history']);

Route::post('/newslatter_subscribe', [ApiController::class, 'newslatter_subscribe']);
Route::post('/contact_us', [ApiController::class, 'contact_us']);

Route::get('/chat_list', [ApiController::class, 'chat_list']);
Route::post('/chat_save', [ApiController::class, 'chat_save']);

Route::get('/courseLevels', [ApiController::class, 'courseLevels']);

Route::post('/checkCoupon', [ApiController::class, 'checkCoupon']);

Route::post('/stripe/create-checkout', [ApiController::class, 'createCheckoutSession']);
Route::get('/stripe/success', [ApiController::class, 'handleStripeSuccess'])->name('stripe.success');

Route::post('subscription/stripe/create-checkout', [ApiController::class, 'subscriptioncreateCheckoutSession']);
Route::get('subscription/stripe/success', [ApiController::class, 'subscriptionhandleStripeSuccess'])->name('subscription.stripe.success');


Route::get('payment', [ApiController::class, 'payment']);
// Route::get('payment/{token}', [ApiController::class, 'payment']);
Route::get('token', [ApiController::class, 'token']);
Route::get('cart_tools', [ApiController::class, 'cart_tools']);

Route::post('saveNotification', [ApiController::class, 'saveNotification']);
Route::get('fetchNotifications', [ApiController::class, 'fetchNotifications']);

Route::get('all_subscription', [ApiController::class, 'all_subscription']);
Route::post('subscription_enrollment', [ApiController::class, 'subscription_enrollment']);
Route::get('my_subscription', [ApiController::class, 'my_subscription']);
Route::post('subscription_user', [ApiController::class, 'subscription_user']);
Route::post('edit_subscription_user/{user_id}', [ApiController::class, 'edit_subscription_user']);
Route::get('delete_subscription_user/{user_id}', [ApiController::class, 'delete_subscription_user']);
Route::get('subscription_user_details', [ApiController::class, 'subscription_user_details']);

Route::get('team_users', [ApiController::class, 'team_users']);
Route::get('team_users_profile', [ApiController::class, 'team_users_profile']);

Route::get('certificate', [ApiController::class, 'certificate']);
Route::get('certificate_details', [ApiController::class, 'certificate_details']);
Route::post('certificate_achieve', [ApiController::class, 'certificate_achieve']);
Route::get('my_certificate', [ApiController::class, 'my_certificate']);
Route::get('final_exam_question', [ApiController::class, 'final_exam_question']);

Route::controller(ApiController::class)->group(function () {
    Route::post('certificate_review_store', 'certificate_review_store');
    Route::get('certificate_review_delete/{id}', 'certificate_review_delete');
    Route::post('certificate_review_update/{id}', 'certificate_review_update');
    Route::get('certificate_review_like/{id}', 'certificate_review_like');
    Route::get('certificate_review_dislike/{id}', 'certificate_review_dislike');
});

Route::get('certified_members', [ApiController::class, 'certified_members']);
Route::get('certified_members_profile', [ApiController::class, 'certified_members_profile']);

// ai chat api
Route::post('ai_chat', [ApiController::class, 'ai_chat']);
Route::post('ai_flashCards', [ApiController::class, 'ai_flashCards']);
Route::post('ai_summary', [ApiController::class, 'ai_summary']);
Route::post('generate_mcq_from_pdf', [ApiController::class, 'generate_mcq_from_pdf']);
Route::post('generate_free_response_from_pdf', [ApiController::class, 'generate_free_response_from_pdf']);
Route::post('audio_podcast', [ApiController::class, 'audio_podcast']);

Route::get('/all_dynamic_pages', [ApiController::class, 'all_dynamic_pages']);
Route::get('/one_dynamic_pages', [ApiController::class, 'one_dynamic_pages']);
Route::get('/student_list', [ApiController::class, 'student_list']);
Route::get('/homePage', [ApiController::class, 'homePage']);
Route::post('/update_watch_history_with_duration', [ApiController::class, 'update_watch_history_with_duration']);
Route::post('/update_watch_duration', [ApiController::class, 'update_watch_duration']);

// });

