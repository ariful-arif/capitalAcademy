<?php

namespace App\Http\Controllers;

use App\Models\LearningCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LearningCategoryController extends Controller
{
    public function index()
    {
        $page_data["categories"] = LearningCategory::all();
        return view("admin.learning_category.index", $page_data);
    }

    public function store(Request $request)
    {
        $data['title']    = $request->title;
        $data['subtitle'] = $request->subtitle;
        $data['slug']     = str_replace(" ", "-", $request->title);

        LearningCategory::insert($data);
        Session::flash('success', get_phrase('Category add successfully'));
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        $query = LearningCategory::where('id', $id);
        if ($query->doesntExist()) {
            Session::flash('success', get_phrase('Data not found.'));
            return redirect()->back();
        }

        $data['title']    = $request->title;
        $data['subtitle'] = $request->subtitle;
        $data['slug']     = str_replace(" ", "-", $request->title);

        $query->update($data);
        Session::flash('success', get_phrase('Category update successfully'));
        return redirect()->back();
    }

    public function delete($id)
    {
        $query = LearningCategory::where('id', $id);
        if ($query->doesntExist()) {
            Session::flash('success', get_phrase('Data not found.'));
            return redirect()->back();
        }

        $query->delete();
        Session::flash('success', get_phrase('Category Delete successfully'));
        return redirect()->back();
    }
}
