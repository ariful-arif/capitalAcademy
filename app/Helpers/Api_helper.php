<?php
// import facade

use App\Models\Addon;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\{CartItem, Course, Category, Live_class, Review, User};

//Api related
if (!function_exists('enroll_history')) {
    function enroll_history($course_id = "", $distinct_data = false)
    {
        if ($distinct_data) {
            $enroll_hoistory = DB::table('enrollments')->select('user_id')
                ->distinct()
                ->where('course_id', $course_id)
                ->get();
            return $enroll_hoistory;
        } else {
            if ($course_id > 0) {
                return DB::table('enrollments')->where('course_id', $course_id)->get();
            } else {
                return DB::table('enrollments')->get();
            }
        }
    }
}


if (!function_exists('courses_by_instructor')) {
    function courses_by_instructor($instructor_id)
    {
        // Query to get active courses for a specific instructor
        $query = Course::where('status', 'active')->where('user_id', $instructor_id);

        // Get total count before pagination
        $total = $query->count();

        // Get the courses
        $courses = $query->get();

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'total' => $total,
            ];
        }

        // Format course data
        $filteredCourses = $courses->map(function ($course) {
            $instructor_details = get_user_info($course->user_id);
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => get_photo('course_thumbnail', $course->thumbnail),
                'banner' => get_photo('course_banner', $course->banner),
                'preview' => $course->preview ? (strpos($course->preview, 'youtube.com') !== false || strpos($course->preview, 'youtu.be') !== false || strpos($course->preview, 'vimeo.com') !== false || strpos($course->preview, 'drive.google.com') !== false || (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false)) ? $course->preview : url('public/' . $course->preview) : null,
                'isPaid' => $course->is_paid,
                'price' => currency($course->price),
                'isDiscount' => $course->discount_flag,
                'discount_price' => currency($course->discounted_price),
                'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                'lessons' => get_lessons('course', $course->id)->count(),
                'instructor_name' => $instructor_details->name,
                'instructor_image' => url('public/' . $instructor_details->photo),
            ];
        });

        return [
            'data' => $filteredCourses->toArray(),
            'total' => $total,
        ];
    }
}


if (!function_exists('team_courses')) {
    function team_courses($limit = null, $page = null, $filters = [])
    {
        // Initialize query for active courses
        $courses = Course::where('status', 'active')->get();

        // Format course data
        $filteredCourses = $courses->map(function ($course) {
            $instructor_details = get_user_info($course->user_id);
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => get_photo('course_thumbnail', $course->thumbnail),
                'banner' => get_photo('course_banner', $course->banner),
                'preview' => $course->preview ? (strpos($course->preview, 'youtube.com') !== false || strpos($course->preview, 'youtu.be') !== false || strpos($course->preview, 'vimeo.com') !== false || strpos($course->preview, 'drive.google.com') !== false || (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false)) ? $course->preview : url('public/' . $course->preview) : null,
                'isPaid' => $course->is_paid,
                'price' => currency($course->price),
                'isDiscount' => $course->discount_flag,
                'discount_price' => currency($course->discounted_price),
                'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                'lessons' => get_lessons('course', $course->id)->count(),
                'instructor_name' => $instructor_details->name,
                'instructor_image' => url('public/' . $instructor_details->photo),
            ];
        });

        return [
            'data' => $filteredCourses->toArray(),
        ];
    }
}


if (!function_exists('courses')) {
    function courses($limit = null, $page = null, $filters = [])
    {
        // Initialize query for active courses
        $query = Course::where('status', 'active');


        // Apply filters if provided
        if (!empty($filters['search_string'])) {
            $query->where('title', 'LIKE', "%{$filters['search_string']}%");
        }
        if (!empty($filters['selected_category']) && $filters['selected_category'] !== 'all') {
            $categories = is_array($filters['selected_category']) ? $filters['selected_category'] : explode(',', $filters['selected_category']);
            $query->whereIn('category_id', $categories);
        }
        if (!empty($filters['selected_price']) && $filters['selected_price'] !== 'all') {
            if ($filters['selected_price'] === 'paid') {
                $query->where('is_paid', 1);
            } elseif ($filters['selected_price'] === 'free') {
                $query->where(function ($q) {
                    $q->where('is_paid', 0)->orWhereNull('is_paid');
                });
            }
        }
        if (!empty($filters['selected_level']) && $filters['selected_level'] !== 'all') {
            $query->where('level', $filters['selected_level']);
        }
        if (!empty($filters['selected_rating']) && $filters['selected_rating'] !== 'all') {
            $ratings = is_array($filters['selected_rating']) ? $filters['selected_rating'] : explode(',', $filters['selected_rating']);
            $query->whereIn('average_rating', $ratings);
        }
        if (!empty($filters['selected_instructor']) && $filters['selected_instructor'] !== 'all') {
            $query->where('user_id', $filters['selected_instructor']);
        }
        if (!empty($filters['min_price'])) {
            $query->whereRaw("(CASE WHEN discount_flag = 1 THEN discounted_price ELSE price END) >= ?", [$filters['min_price']]);
        }
        if (!empty($filters['max_price'])) {
            $query->whereRaw("(CASE WHEN discount_flag = 1 THEN discounted_price ELSE price END) <= ?", [$filters['max_price']]);
        }

        // Get total count before pagination
        $total = $query->count();

        // Get all course prices before pagination
        $allPrices = $query->get()->map(function ($course) {
            return $course->discount_flag == 1 ? $course->discounted_price : $course->price;
        });

        // Calculate highest & lowest price
        $highest_price = $allPrices->isEmpty() ? 0 : $allPrices->max();
        $lowest_price = $allPrices->isEmpty() ? 0 : $allPrices->min();

        // Apply pagination
        if ($limit && $page) {
            $query->skip(($page - 1) * $limit)->take($limit);
        }

        // Get paginated courses
        $courses = $query->get();

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'total' => $total,
                'highest_price' => $highest_price,
                'lowest_price' => $lowest_price,
            ];
        }

        // Format course data
        $filteredCourses = $courses->map(function ($course) {
            $instructor_details = get_user_info($course->user_id);
            $user = auth('api')->user();
            $user_id = $user ? $user->id : 0;
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => get_photo('course_thumbnail', $course->thumbnail),
                'banner' => get_photo('course_banner', $course->banner),
                'preview' => $course->preview ? (strpos($course->preview, 'youtube.com') !== false || strpos($course->preview, 'youtu.be') !== false || strpos($course->preview, 'vimeo.com') !== false || strpos($course->preview, 'drive.google.com') !== false || (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false)) ? $course->preview : url('public/' . $course->preview) : null,
                'isPaid' => $course->is_paid,
                'is_wishlisted' => is_added_to_wishlist($user_id, $course->id),
                'is_purchased' => is_purchased($user_id, $course->id),
                'is_cart' => is_cart_item($user_id, $course->id),
                'price' => currency($course->price),
                'isDiscount' => $course->discount_flag,
                'discount_price' => currency($course->discounted_price),
                'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                'lessons' => get_lessons('course', $course->id)->count(),
                'instructor_name' => $instructor_details->name,
                'instructor_image' => url('public/' . $instructor_details->photo),
            ];
        });

        return [
            'data' => $filteredCourses->toArray(),
            'total' => $total,
            'highest_price' => $highest_price,
            'lowest_price' => $lowest_price,
        ];
    }
}


if (!function_exists('top_courses')) {
    function top_courses($courses)
    {
        // $courses = $courses;

        if ($courses->isEmpty()) {
            return [
                'data' => [],
            ];
        }

        $filteredCourses = $courses->map(function ($course) {
            $instructor_details = get_user_info($course->user_id);
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => get_photo('course_thumbnail', $course->thumbnail),
                'banner' => get_photo('course_banner', $course->banner),
                'preview' => $course->preview ? (strpos($course->preview, 'youtube.com') !== false || strpos($course->preview, 'youtu.be') !== false || strpos($course->preview, 'vimeo.com') !== false || strpos($course->preview, 'drive.google.com') !== false || (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false)) ? $course->preview : url('public/' . $course->preview) : null,
                'isPaid' => $course->is_paid,
                'price' => currency($course->price),
                'isDiscount' => $course->discount_flag,
                'discount_price' => currency($course->discounted_price),
                'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                'instructor_name' => $instructor_details->name,
                'instructor_image' => url('public/' . $instructor_details->photo),
            ];
        });


        return $filteredCourses->toArray();

    }
}


// Return require course data
if (!function_exists('course_data')) {
    function course_data($courses = array())
    {
        foreach ($courses as $key => $course) {
            $courses[$key]->requirements = json_decode($course->requirements) == null ? [] : json_decode($course->requirements);
            $courses[$key]->outcomes = json_decode($course->outcomes) == null ? [] : json_decode($course->outcomes);
            $courses[$key]->faqs = json_decode($course->faqs) == null ? [] : json_decode($course->faqs);
            $courses[$key]->instructors = json_decode($course->instructor_ids) == null ? [] : json_decode($course->instructor_ids);
            $courses[$key]->thumbnail = get_photo('course_thumbnail', $course->thumbnail);
            $courses[$key]->banner = get_photo('course_banner', $course->banner);
            // $courses[$key]->preview = get_photo('course_preview', $courses[$key]->preview);
            if (strpos($courses[$key]->preview, 'youtube.com') !== false || strpos($courses[$key]->preview, 'youtu.be') !== false) {
            } elseif (strpos($courses[$key]->preview, 'vimeo.com') !== false) {
            } elseif (strpos($courses[$key]->preview, 'drive.google.com') !== false) {
            } elseif (strpos($courses[$key]->preview, '.mp4') !== false && strpos($courses[$key]->preview, 'http') !== false) {
            } else {
                $courses[$key]->preview = url('public/' . $course->preview);
            }
            // $courses[$key]->enable_drip_content = $course->enable_drip_content;
            if ($course->is_paid == 0) {
                $courses[$key]->price = 'Free';
            } else {
                if ($course->discount_flag == 1) {
                    $courses[$key]->price = currency($course->discounted_price);
                    $courses[$key]->price_cart = $course->discounted_price;
                } else {
                    $courses[$key]->price_cart = $course->price;
                    $courses[$key]->price = currency($course->price);
                }
            }
            // // $total_rating =  get_ratings('course', $course->id, true)->row()->rating;
            // // $number_of_ratings = get_ratings('course', $course->id)->num_rows();
            // if ($number_of_ratings > 0) {
            // 	$courses[$key]->rating = ceil($total_rating / $number_of_ratings);
            // } else {
            // 	$courses[$key]->rating = 0;
            // }
            // $courses[$key]->number_of_ratings = $number_of_ratings;
            $instructor_details = get_user_info($course->user_id);
            $courses[$key]->instructor_name = $instructor_details->name;
            $courses[$key]->instructor_image = url('public/' . $instructor_details->photo);
            $courses[$key]->total_enrollment = enroll_history($course->id)->count();
            $courses[$key]->shareable_link = url('course/' . slugify($course->title));

            $review = Review::where('course_id', $course->id)->get();

            $total = $review->count();
            $rating = array_sum(array_column($review->toArray(), 'rating'));

            $average_rating = 0.0;
            if ($total != 0.0) {
                $average_rating = round($rating / $total);
            }

            $courses[$key]->total_reviews = $total;
            // $courses[$key]->average_rating = $course->average_rating;
            $courses[$key]->average_rating = number_format($average_rating, 1, '.', '');
        }

        return $courses;
    }
}


if (!function_exists('course_data_by_details')) {
    function course_data_by_details($course)
    {
        $user = auth('api')->user();
        $user_id = $user ? $user->id : 0;

        $course->requirements = json_decode($course->requirements) == null ? [] : json_decode($course->requirements);
        $course->outcomes = json_decode($course->outcomes) == null ? [] : json_decode($course->outcomes);
        $course->faqs = json_decode($course->faqs) == null ? [] : json_decode($course->faqs);
        $course->instructors = json_decode($course->instructor_ids) == null ? [] : json_decode($course->instructor_ids);
        $course->thumbnail = get_photo('course_thumbnail', $course->thumbnail);
        $course->banner = get_photo('course_banner', $course->banner);
        if (strpos($course->preview, 'youtube.com') !== false || strpos($course->preview, 'youtu.be') !== false) {
        } elseif (strpos($course->preview, 'vimeo.com') !== false) {
        } elseif (strpos($course->preview, 'drive.google.com') !== false) {
        } elseif (strpos($course->preview, '.mp4') !== false && strpos($course->preview, 'http') !== false) {
        } else {
            $course->preview = url('public/' . $course->preview);
        }

        if ($course->is_paid == 0) {
            $course->price = 'Free';
        } else {
            $course->discounted_price = currency($course->discounted_price);
            $course->price = currency($course->price);
        }
        $course->description = $course->description == null ? "No description added right now" : $course->description;
        $instructor_details = get_user_info(user_id: $course->user_id);
        $course->instructor_name = $instructor_details->name;
        $course->instructor_image = url('public/' . $instructor_details->photo);
        $course->instructor_about = $instructor_details->about;
        $course->total_enrollment = enroll_history($course->id)->count();
        $course->shareable_link = url('course/' . slugify($course->title));
        $course->total_number_of_lessons = get_lessons('course', $course->id)->count();
        $course->is_purchased = is_purchased($user_id, $course->id);
        $course->is_cartItem = is_cart_item($user_id, $course->id);

        return $course;
    }
}


if (!function_exists('get_photo')) {
    function get_photo($type, $identifier)
    { // type is the flag to realize whether it is course, category or user image. For course, user image Identifier is id but for category Identifier is image name
        if ($type == 'user_image') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/assets/upload/users/student/placeholder/placeholder.png');
            }
        } elseif ($type == 'course_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/course-thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'course_banner') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/course-banner/placeholder/placeholder.png');
            }
        } elseif ($type == 'course_preview') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/course-preview/placeholder/placeholder.png');
            }
        } elseif ($type == 'category_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/category-thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'category_logo') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/category-logo/placeholder/placeholder.png');
            }
        } elseif ($type == 'category_logo') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/category-logo/placeholder/placeholder.png');
            }
        } elseif ($type == 'blog_banner') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/banner/placeholder/placeholder.png');
            }
        } elseif ($type == 'blog_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'newsroom_banner') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/newsroom/banner/placeholder/placeholder.png');
            }
        } elseif ($type == 'newsroom_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/newsroom/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'learning_banner') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/learning/banner/placeholder/placeholder.png');
            }
        } elseif ($type == 'learning_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/learning/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'bootcamp_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/bootcamp/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'banner_image') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'light_logo') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'dark_logo') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'small_logo') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'favicon') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'motivational_speech') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/blog/thumbnail/placeholder/placeholder.png');
            }
        } elseif ($type == 'certificate_thumbnail') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/certificate-program/placeholder/placeholder.png');
            }
        } elseif ($type == 'certificate_template') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/certificate-program/placeholder/placeholder.png');
            }
        } elseif ($type == 'subscription_banner') {
            if (file_exists('public/' . $identifier) && $identifier != "") {
                return url('public/' . $identifier);
            } else {
                return url('public/uploads/subscription-package/placeholder/placeholder.png');
            }
        }
    }
}


if (!function_exists('get_category_wise_courses')) {
    function get_category_wise_courses($category_id = "")
    {
        // $category_details = get_category_details_by_id($category_id);
        // $courses = Course::where('category_id', $category_id)->where('status', 'active')->get();
        if ($category_id == "") {
            $courses = Course::where('status', 'active')
                ->get();
        } else {
            $courses = Course::where('category_id', $category_id)
                ->where('status', 'active')
                ->get();
        }
        return $courses;
    }
}


if (!function_exists('get_category_details_by_id')) {
    function get_category_details_by_id($id)
    {
        return DB::table('categories')->where('id', $id)->first();
    }
}

if (!function_exists('get_blog_category_details_by_id')) {
    function get_blog_category_details_by_id($id)
    {
        return DB::table('blog_categories')->where('id', $id)->first();
    }
}

if (!function_exists('get_newsroom_category_details_by_id')) {
    function get_newsroom_category_details_by_id($id)
    {
        return DB::table('newsroom_categories')->where('id', $id)->first();
    }
}

if (!function_exists('get_learning_category_details_by_id')) {
    function get_learning_category_details_by_id($id)
    {
        return DB::table('learning_categories')->where('id', $id)->first();
    }
}


if (!function_exists('sub_categories')) {
    // Get sub categories
    function sub_categories($parent_category_id)
    {
        $response = array();

        $categories = DB::table('categories')->where('parent_id', $parent_category_id)->get();

        foreach ($categories as $key => $category) {

            $number_of_courses = DB::table('courses')->where('status', 'active')->where('category_id', $category->id)->count();
            $category->number_of_courses = $number_of_courses;
            $category->thumbnail = get_photo('category_thumbnail', $category->thumbnail);
            $response[] = $category;
        }

        return $response;
    }
}


if (!function_exists('course_details_by_id')) {
    // Get sub categories
    function course_details_by_id($user_id = "", $course_id = "")
    {
        $course_details = get_course_by_id($course_id);

        $response = course_data_by_details($course_details);
        $response->sections = sections($course_id);
        $response->reviews = review($course_id);
        $response->is_wishlisted = is_added_to_wishlist($user_id, $course_id);
        $response->is_purchased = is_purchased($user_id, $course_id);
        $response->liveClass = live_class_schedules($course_id);
        $response->is_cart = is_cart_item($user_id, $course_id);
        $response->duration = get_total_duration_of_lesson_by_course_id($course_id);
        // $response->lessons = get_lessons('course', $course_id)->count() . ' Lessons';
        // $response->includes = array(
        //     get_total_duration_of_lesson_by_course_id($course_id) . ' On demand videos',
        //     get_lessons('course', $course_id)->count() . ' Lessons',
        //     'High quality videos',
        //     'Life time access'
        // );
        return $response;
    }
}


if (!function_exists('course_related_category_course_for_course_details')) {
    function course_related_category_course_for_course_details($course_id = "")
    {
        $course_find = Course::where('id', $course_id)->first();
        if (!$course_find)
            return [];

        $category = $course_find->category_id;

        // Get 10 random courses in the same category, excluding the current course
        $courses_find = Course::where('category_id', $category)
            ->where('id', '!=', $course_id)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $related_courses = [];
        $user = auth('api')->user();
        $user_id = $user ? $user->id : 0;

        foreach ($courses_find as $course) {
            $instructor_details = get_user_info($course->user_id);

            $related_courses[] = [
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
                'is_wishlisted' => is_added_to_wishlist($user_id, $course->id),
                'is_purchased' => is_purchased($user_id, $course->id),
                'is_cart' => is_cart_item($user_id, $course->id),
                'price' => currency($course->price),
                'isDiscount' => $course->discount_flag,
                'discount_price' => currency($course->discounted_price),
                'minute' => get_total_duration_of_lesson_by_course_id($course->id),
                'lessons' => get_lessons('course', $course->id)->count(),
                'instructor_name' => $instructor_details->name,
                'instructor_image' => url('public/' . $instructor_details->photo),
            ];
        }

        return $related_courses;
    }
}


function get_course_by_id($course_id = "")
{
    return DB::table('courses')->where('id', $course_id)->first();
}


function single_course_by_id($course_id = "")
{
    return DB::table('courses')->where('id', $course_id)->first();
}


function is_added_to_wishlist($user_id = 0, $course_id = "")
{
    if ($user_id > 0) {
        $wishlists = array();
        $wishlist_check = DB::table('wishlists')->where('user_id', $user_id)->where('course_id', $course_id)->first();
        if (!empty($wishlist_check)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


// function is_purchased($user_id = 0, $course_id = "")
// {
//     // 0 represents Not purchased, 1 represents Purchased, 2 represents Pending
//     if ($user_id > 0) {
//         if (enroll_status($course_id, $user_id) == 'valid') {
//             return true;
//         } else {
//             return false;
//         }
//     } else {
//         return false;
//     }
// }

// function is_purchased($user_id = 0, $course_id = "")
// {
//     // 0 represents not purchased, 1 represents purchased
//     if ($user_id > 0) {
//         $status = enroll_status($course_id, $user_id);
//         return $status === 'valid'; // Return true only if status is 'valid'
//     } else {
//         return false; // User ID is not valid
//     }
// }


if (!function_exists('is_purchased')) {
    function is_purchased($user_id = 0, $course_id = "")
    {
        if ($user_id > 0) {
            $status = enroll_status_api($course_id, $user_id);
            if ($status == true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


if (!function_exists('enroll_status_api')) {
    function enroll_status_api($course_id = "", $user_id = "")
    {
        $enrolled_history = DB::table('enrollments')
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->first();

        if ($enrolled_history) {
            return true;
        } else {
            return false;
        }
    }
}


function get_total_duration_of_lesson_by_course_id($course_id)
{
    $total_duration = 0;
    $lessons = get_lessons('course', $course_id);
    foreach ($lessons as $lesson) {
        if ($lesson->lesson_type != "other" && $lesson->lesson_type != "text") {
            $time_array = !empty($lesson->duration) ? explode(':', $lesson->duration) : explode(':', '00:00:00');
            $hour_to_seconds = $time_array[0] * 60 * 60;
            $minute_to_seconds = $time_array[1] * 60;
            $seconds = $time_array[2];
            $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
        }
    }
    // return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    $hours = floor($total_duration / 3600);
    $minutes = floor(($total_duration % 3600) / 60);
    $seconds = $total_duration % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds) . ' ' . get_phrase('hours');
}


if (!function_exists('course_progress')) {
    function course_progress($course_id = "", $user_id = "", $return_type = "")
    {
        $watch_history = DB::table('watch_histories')->where('student_id', $user_id)->where('course_id', $course_id)->first();
        $total_lessons = DB::table('lessons')->where('course_id', $course_id)->count();

        if (!empty($watch_history)) {
            $completed_lessons = json_decode($watch_history->completed_lesson, true);
            // Check if completed_lesson is an array and is not empty
            if (is_array($completed_lessons) && !empty($completed_lessons)) {
                $total_completed_lesson = count($completed_lessons);
                $watch_history->course_progress = ($total_completed_lesson / $total_lessons) * 100;
            } else {
                $total_completed_lesson = 0;
                $watch_history->course_progress = 0; // Ensure course_progress is set to 0 if no lessons are completed
            }
        } else {
            $total_completed_lesson = 0;
        }

        if ($return_type == "completed_lesson_ids") {
            // Return completed_lesson as an array, or an empty array if it's empty
            return is_array($completed_lessons) ? $completed_lessons : [];
        }
        if (!empty($watch_history) && $watch_history->course_progress > 0) {
            return $watch_history->course_progress;
        } else {
            return 0;
        }
    }
}


if (!function_exists('lesson_progress_api')) {
    function lesson_progress_api($lesson_id = "", $user_id = "", $course_id = "")
    {
        if ($course_id == "") {
            $course_id = DB::table('lessons')->where('id', $lesson_id)->value('course_id');
        }

        $query = DB::table('watch_histories')->where('student_id', $user_id)->where('course_id', $course_id)->first();

        if (!empty($query)) {
            $lesson_ids = json_decode($query->completed_lesson, true);
            if (is_array($lesson_ids) && in_array($lesson_id, $lesson_ids)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}


if (!function_exists('get_lessons')) {
    function get_lessons($type = "", $id = "")
    {
        $lessons = array();

        if ($type == "course") {
            $lessons = DB::table('lessons')->where('course_id', $id)->orderBy("sort", "asc")->get();
        } elseif ($type == "section") {
            $lessons = DB::table('lessons')->where('section_id', $id)->orderBy("sort", "asc")->get();
        } elseif ($type == "lesson") {
            $lessons = DB::table('lessons')->where('id', $id)->orderBy("sort", "asc")->get();
        } else {
            $lessons = DB::table('lessons')->orderBy("sort", "asc")->get();
        }

        return $lessons;
    }
}


if (!function_exists('update_watch_history_manually')) {
    // code of mark this lesson as completed
    function update_watch_history_manually($lesson_id = "", $course_id = "", $user_id = "")
    {
        $is_completed = 0;

        $query = DB::table('watch_histories')->where('course_id', $course_id)->where('student_id', $user_id)->first();

        $course_progress = course_progress($course_id, $user_id);

        if (!empty($query)) {
            $lesson_ids = json_decode($query->completed_lesson, true);
            if (!is_array($lesson_ids))
                $lesson_ids = array();
            if (!in_array($lesson_id, $lesson_ids)) {
                array_push($lesson_ids, $lesson_id);
                $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
                $course_progress = (100 / count($total_lesson)) * count($lesson_ids);

                if ($course_progress >= 100 && $query->completed_date == null) {
                    $completed_date = time();
                } else {
                    $completed_date = $query->completed_date;
                }

                DB::table('watch_histories')->where('id', $query->id)->update([
                    'completed_lesson' => json_encode($lesson_ids),
                    'completed_date' => $completed_date,
                ]);

                $is_completed = 1;
            } else {
                if (($key = array_search($lesson_id, $lesson_ids)) !== false) {
                    unset($lesson_ids[$key]);
                }

                $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
                $course_progress = (100 / count($total_lesson)) * count($lesson_ids);

                if ($course_progress >= 100 && $query->completed_date == null) {
                    $completed_date = time();
                } else {
                    $completed_date = $query->completed_date;
                }

                DB::table('watch_histories')->where('id', $query->id)->update([
                    'completed_lesson' => json_encode($lesson_ids),
                    'completed_date' => $completed_date,
                ]);

                $is_completed = 0;
            }
        } else {
            $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
            $course_progress = (100 / count($total_lesson));

            $insert_data['course_id'] = $course_id;
            $insert_data['student_id'] = $user_id;
            $insert_data['completed_lesson'] = json_encode(array($lesson_id));
            $insert_data['watching_lesson_id'] = $lesson_id;
            DB::table('watch_histories')->insert($insert_data);
        }

        return json_encode(array('lesson_id' => $lesson_id, 'course_progress' => round($course_progress), 'is_completed' => $is_completed));
    }
}


// if (!function_exists('update_watch_history_manually')) {
//     // code of mark this lesson as completed
//     function update_watch_history_manually($lesson_id = "", $course_id = "", $user_id = "")
//     {
//         $is_completed = 0;

//         $query = DB::table('watch_histories')->where('course_id', $course_id)->where('student_id', $user_id)->first();

//         $course_progress = course_progress($course_id, $user_id);

//         if (!empty($query)) {
//             $lesson_ids = json_decode($query->completed_lesson, true);
//             if (!is_array($lesson_ids))
//                 $lesson_ids = array();
//             if (!in_array($lesson_id, $lesson_ids)) {
//                 array_push($lesson_ids, $lesson_id);
//                 $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
//                 $course_progress = (100 / count($total_lesson)) * count($lesson_ids);

//                 if ($course_progress >= 100 && $query->completed_date == null) {
//                     $completed_date = time();
//                 } else {
//                     $completed_date = $query->completed_date;
//                 }

//                 DB::table('watch_histories')->where('id', $query->id)->update([
//                     'completed_lesson' => json_encode($lesson_ids),
//                     'completed_date' => $completed_date,
//                 ]);

//                 $is_completed = 1;

//             } else {
//                 if (($key = array_search($lesson_id, $lesson_ids)) !== false) {
//                     unset($lesson_ids[$key]);
//                 }

//                 $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
//                 $course_progress = (100 / count($total_lesson)) * count($lesson_ids);

//                 if ($course_progress >= 100 && $query->completed_date == null) {
//                     $completed_date = time();
//                 } else {
//                     $completed_date = $query->completed_date;
//                 }

//                 DB::table('watch_histories')->where('id', $query->id)->update([
//                     'completed_lesson' => json_encode($lesson_ids),
//                     'completed_date' => $completed_date,
//                 ]);

//                 $is_completed = 0;
//             }

//         } else {
//             $total_lesson = DB::table('lessons')->where('course_id', $course_id)->get();
//             $course_progress = (100 / count($total_lesson));

//             $insert_data['course_id'] = $course_id;
//             $insert_data['student_id'] = $user_id;
//             $insert_data['completed_lesson'] = json_encode(array($lesson_id));
//             $insert_data['watching_lesson_id'] = $lesson_id;
//             DB::table('watch_histories')->create($insert_data);
//         }

//         return json_encode(array('lesson_id' => $lesson_id, 'course_progress' => round($course_progress), 'is_completed' => $is_completed));
//     }
// }

function course_completion_data($course_id = "", $user_id = "")
{
    $response = array();
    $response['course_id'] = $course_id;
    $response['number_of_lessons'] = get_lessons('course', $course_id)->count();
    $response['number_of_completed_lessons'] = get_completed_number_of_lesson($user_id, 'course', $course_id);
    $response['course_progress'] = round(course_progress($course_id, $user_id));
    return $response;
}


if (!function_exists('get_completed_number_of_lesson')) {
    function get_completed_number_of_lesson($user_id = "", $type = "", $id = "")
    {
        $counter = 0;
        if ($type == 'section') {
            $lessons = get_lessons('section', $id);
        } else {
            $lessons = get_lessons('course', $id);
        }
        foreach ($lessons as $key => $lesson) {
            if (lesson_progress_api($lesson->id, $user_id)) {
                $counter = $counter + 1;
            }
        }
        return $counter;
    }
}


//get all sections
function sections($course_id = "", $user_id = "")
{
    $response = array();
    $lesson_counter_starts = 0;
    $lesson_counter_ends = 0;
    $sections = api_get_section('course', $course_id);
    foreach ($sections as $key => $section) {
        $sections[$key]->lessons = section_wise_lessons($section->id, $user_id);
        $sections[$key]->total_duration = str_replace(' Hours', "", get_total_duration_of_lesson_by_section_id($section->id));
        if ($key == 0) {
            $lesson_counter_starts = 1;
            $lesson_counter_ends = count($sections[$key]->lessons);
        } else {
            $lesson_counter_starts = $lesson_counter_ends + 1;
            $lesson_counter_ends = $lesson_counter_starts + count($sections[$key]->lessons);
        }
        $sections[$key]->lesson_counter_starts = $lesson_counter_starts;
        $sections[$key]->lesson_counter_ends = $lesson_counter_ends;
        if ($user_id > 0) {
            $sections[$key]->completed_lesson_number = get_completed_number_of_lesson($user_id, 'section', $section->id);
        } else {
            $sections[$key]->completed_lesson_number = 0;
        }
    }
    $response = add_user_validity($sections);
    return $response;
}


function api_get_section($type_by, $id)
{
    $sections = array();

    if ($type_by == 'course') {
        $sections = DB::table('sections')->where('course_id', $id)->orderBy("sort", "asc")->get();
    } elseif ($type_by == 'section') {
        $sections = DB::table('sections')->where('id', $id)->orderBy("sort", "asc")->get();
    }

    return $sections;
}


function section_wise_lessons($section_id = "", $user_id = "")
{
    $response = array();
    $lessons = get_lessons('section', $section_id);
    foreach ($lessons as $key => $lesson) {
        $response[$key]['id'] = $lesson->id;
        $response[$key]['title'] = $lesson->title;
        $response[$key]['duration'] = readable_time_for_humans($lesson->duration);
        $response[$key]['course_id'] = $lesson->course_id;
        $response[$key]['section_id'] = $lesson->section_id;
        $response[$key]['video_type'] = ($lesson->video_type == "" ? "" : $lesson->video_type);
        if ($lesson->lesson_type == 'system-video') {
            $response[$key]['video_url'] = ($lesson->lesson_src == "" ? "" : asset($lesson->lesson_src));
        } else {
            $response[$key]['video_url'] = ($lesson->lesson_src == "" ? "" : $lesson->lesson_src);
        }
        // $response[$key]['video_url_web'] = $lesson->video_url;
        // $response[$key]['video_type_web'] = $lesson->video_type;
        $response[$key]['lesson_type'] = $lesson->lesson_type;
        $response[$key]['is_free'] = $lesson->is_free;
        if ($lesson->lesson_type == 'text') {
            $response[$key]['attachment'] = remove_js(htmlspecialchars_decode_($lesson->attachment));
        } else {
            $response[$key]['attachment'] = $lesson->attachment;
        }
        $response[$key]['attachment_url'] = $lesson->attachment ? url('/public/uploads/lesson_file/attachment') . '/' . $lesson->attachment : $lesson->attachment;
        $response[$key]['attachment_type'] = $lesson->attachment_type;
        $response[$key]['audio'] = $lesson->audio ? $lesson->audio : "";
        $response[$key]['audio_url'] = $lesson->audio ? asset($lesson->audio) : "";

        // flashcards
        $allFlashcards = $lesson->flashcards ? json_decode($lesson->flashcards, true) : [];
        $totalCount = count($allFlashcards);
        $randomCount = rand(10, min(20, $totalCount));
        $selectedFlashcards = collect($allFlashcards)->shuffle()->take($randomCount)->values();
        $response[$key]['flashcards'] = $selectedFlashcards;
        $response[$key]['flashcard_total_count'] = $totalCount;
        $response[$key]['flashcard_selected_count'] = $selectedFlashcards->count();
        // ends

        // mcq_question question
        $mcq = $lesson->mcq_question;

        if (preg_match('/```json\s*(.*?)\s*```/is', $mcq, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = trim($mcq);
            if (str_starts_with($jsonString, '```json')) {
                $jsonString = preg_replace('/^```json\s*/i', '', $jsonString);
                $jsonString = preg_replace('/\s*```$/', '', $jsonString);
            }
        }

        $questionAnswerPairs = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionAnswerPairs)) {
            $response[$key]['mcq_question'] = [];
            continue;
        }

        $totalCount = count($questionAnswerPairs);
        $randomCount = rand(7, min(15, $totalCount));
        $selectedMcq = collect($questionAnswerPairs)->shuffle()->take($randomCount)->values();

        $response[$key]['mcq_question'] = $selectedMcq;

        // end

        // free response question
        $rawText = $lesson->free_response_question;

        if (preg_match('/```json\s*(.*?)\s*```/is', $rawText, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = trim($rawText);
            if (str_starts_with($jsonString, '```json')) {
                $jsonString = preg_replace('/^```json\s*/i', '', $jsonString);
                $jsonString = preg_replace('/\s*```$/', '', $jsonString);
            }
        }

        $questionAnswerPairs = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionAnswerPairs)) {
            $response[$key]['free_response_question'] = [];
            continue;
        }
        $totalCount = count($questionAnswerPairs);
        $randomCount = rand(7, min(15, $totalCount)); // adjust as needed
        $selectedFreeResponse = collect($questionAnswerPairs)->shuffle()->take($randomCount)->values();
        $response[$key]['free_response_question'] = $selectedFreeResponse;

        // end

        // both question
        $combinedQuestions = collect()
            ->merge($selectedMcq)
            ->merge($selectedFreeResponse);
        $combinedQuestions = $combinedQuestions->shuffle();
        $totalCombinedCount = $combinedQuestions->count();
        $randomTotal = rand(7, min(15, $totalCombinedCount));
        $randomSubset = $combinedQuestions->take($randomTotal)->values();
        $response[$key]['both_mcq_free_question'] = $randomSubset;
        $response[$key]['both_question_total_count'] = $totalCombinedCount;
        $response[$key]['both_question_selected_count'] = $randomSubset->count();
        // end

        $response[$key]['summary'] = remove_js(htmlspecialchars_decode_($lesson->summary));
        if ($user_id > 0) {
            $response[$key]['is_completed'] = lesson_progress_api($lesson->id, $user_id);
        } else {
            $response[$key]['is_completed'] = 0;
        }
        $response[$key]['user_validity'] = true;
    }

    return $response;
}

// function cleanFetchedMCQs($mcqs)
// {
//     $cleaned_mcqs = [];

//     foreach ($mcqs as $mcq) {
//         $question = preg_replace('/^\d+\.\s*/', '', $mcq['question']);
//         $question = str_replace('**', '', $question);

//         $options = [];
//         foreach ($mcq['options'] as $key => $option) {
//             $option = preg_replace('/^' . $key . '\)\s*/', '', $option);
//             $options[$key] = trim(str_replace('**', '', $option));
//         }

//         $correct_answer = str_replace('**', '', $mcq['correct_answer']);
//         preg_match('/([A-D])/', $correct_answer, $match);
//         $correct_answer = $match[1] ?? null;

//         $cleaned_mcqs[] = [
//             'question' => trim($question),
//             'options' => $options,
//             'correct_answer' => $correct_answer,
//         ];
//     }

//     return $cleaned_mcqs;
// }


function add_user_validity($responses = array())
{
    foreach ($responses as $key => $response) {
        $responses[$key]->user_validity = true;
    }
    return $responses;
}


function get_total_duration_of_lesson_by_section_id($section_id)
{
    $total_duration = 0;
    $lessons = get_lessons('section', $section_id);
    foreach ($lessons as $lesson) {
        if ($lesson->lesson_type != "other" && $lesson->lesson_type != "text") {
            $time_array = !empty($lesson->duration) ? explode(':', $lesson->duration) : explode(':', '00:00:00');
            $hour_to_seconds = $time_array[0] * 60 * 60;
            $minute_to_seconds = $time_array[1] * 60;
            $seconds = $time_array[2];
            $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
        }
    }
    //return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    $hours = floor($total_duration / 3600);
    $minutes = floor(($total_duration % 3600) / 60);
    $seconds = $total_duration % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}


// Human readable time
if (!function_exists('readable_time_for_humans')) {
    function readable_time_for_humans($duration)
    {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour . ' ' . get_phrase('hr') . ' ' . $minute . ' ' . get_phrase('min');
            } elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute + 1) . ' ' . get_phrase('min');
                } else {
                    $duration = $minute . ' ' . get_phrase('min');
                }
            } elseif ($second > 0) {
                $duration = $second . ' ' . get_phrase('sec');
            } else {
                $duration = '00:00';
            }
        } else {
            $duration = '00:00';
        }
        return $duration;
    }
}


if (!function_exists('remove_js')) {
    function remove_js($description = '')
    {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $description);
    }
}


// if (!function_exists('get_course_ratings')) {
//     function get_course_ratings($course_id)
//     {

//         $query = Review::where('review_type', 'course')
//             ->where('course_id', $course_id)
//             ->get();

//         $reviews = $query->result_array(); // Fetch results as array

//         $total_reviews = count($reviews); // Count total reviews
//         $total_rating = array_sum(array_column($reviews, 'rating')); // Sum up all ratings

//         $average_rating = $total_reviews > 0 ? round($total_rating / $total_reviews) : 0; // Calculate average

//         return [
//             'total_reviews' => $total_reviews,
//             'average_rating' => $average_rating
//         ];
//     }
// }

if (!function_exists('get_user_info_api')) {
    function get_user_info_api($user_id = "")
    {
        $user_info = App\Models\User::where('id', $user_id)->firstOrNew();
        return $user_info;
    }
}


if (!function_exists('count_student_by_instructor_api')) {
    function count_student_by_instructor_api($user_id = "")
    {
        if ($user_id != '') {
            $course = DB::table('courses')->where('user_id', $user_id)->get();
            $total_student = 0;
            foreach ($course as $courses) {
                $total_student = DB::table('enrollments')->where('course_id', $courses->id)->count();
            }
            return ($total_student > 1) ? "{$total_student} " . get_phrase('Students') : "{$total_student} " . get_phrase('Student');
        }
    }
}


if (!function_exists('count_course_by_instructor_api')) {
    function count_course_by_instructor_api($user_id = "")
    {
        if ($user_id != '') {
            $count_course = DB::table('courses')->where('status', 'active')->where('user_id', $user_id)->count();
            return ($count_course > 1) ? "{$count_course} " . get_phrase('Courses') : "{$count_course} " . get_phrase('Course');
        }
    }
}


if (!function_exists('get_image_by_id_api')) {
    function get_image_by_id_api($user_id = "")
    {
        $image_path = DB::table('users')->where('id', $user_id)->value('photo');
        return get_photo('user_image', $image_path);
    }
}


if (!function_exists('is_cart_item')) {

    function is_cart_item($user_id = "", $course_id = "")
    {
        $is_cart = CartItem::where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->exists();
        return $is_cart ? true : false;
    }
}


function live_class_schedules($course_id)
{
    $response = array();
    $classes = array();

    $live_classes = Live_class::where('course_id', $course_id)->orderBy('class_date_and_time', 'desc')->get();

    foreach ($live_classes as $key => $live_class) {
        $additional_info = json_decode($live_class->additional_info, true);


        $class_date_and_time = new DateTime($live_class->class_date_and_time);
        $formatted_date_time = $class_date_and_time->format('h:i A - D, d M Y');

        $classes[$key]['class_topic'] = $live_class->class_topic;
        $classes[$key]['provider'] = $live_class->provider;
        $classes[$key]['note'] = $live_class->note;
        $classes[$key]['class_date_and_time'] = $formatted_date_time;
        $classes[$key]['meeting_id'] = $additional_info['id'];
        $classes[$key]['meeting_password'] = $additional_info['password'];
        $classes[$key]['start_url'] = $additional_info['start_url'];
        $classes[$key]['join_url'] = $additional_info['join_url'];
        $classes[$key]['zoom_sdk'] = get_settings('zoom_web_sdk');
        $classes[$key]['zoom_sdk_client_id'] = get_settings('zoom_sdk_client_id');
        $classes[$key]['zoom_sdk_client_secret'] = get_settings('zoom_sdk_client_secret');
    }

    $response = $classes;

    return $response;
}


function review($course_id = "")
{
    $response = array();
    $reviews = Review::where('course_id', $course_id)->get();
    foreach ($reviews as $key => $review) {
        $user = User::where('id', $review->user_id)->first();
        $review->photo = get_photo('user_image', $user->photo);
        $review->name = $user->name;

        $date = new DateTime($review->createdAt);
        $formattedDate = $date->format('d M, Y H:i');


        $review->createtime = $formattedDate;
    }
    $response = $reviews;
    return $response;
}


if (!function_exists('format_text_settings')) {
    function format_text_settings($text)
    {
        return ucwords(str_replace('_', ' ', $text));
    }
}

