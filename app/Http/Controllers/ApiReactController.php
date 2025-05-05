<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ApiReactController extends Controller
{
    public function all_category()
    {
        $all_categories = array();
        $categories = Category::where('parent_id', 0)->get();
        foreach ($categories as $key => $category) {
            $all_categories[$key] = $category;
            $all_categories[$key]['thumbnail'] = get_photo('category_thumbnail', $category['thumbnail']);
            $all_categories[$key]['number_of_courses'] = get_category_wise_courses($category['id'])->count();

            $all_categories[$key]['number_of_sub_categories'] = $category->childs->count();

            // $sub_categories = $category->childs;
        }
        return $all_categories;
    }
}
