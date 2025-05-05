<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsroomCategory;
use Illuminate\Support\Facades\Session;

class NewsroomCategoryController extends Controller
{
    public function index()
    {
        $page_data["categories"] = NewsroomCategory::all();
        return view("admin.newsroom_category.index", $page_data);
    }

    public function store(Request $request)
    {
        $data['title']    = $request->title;
        $data['subtitle'] = $request->subtitle;
        $data['slug']     = str_replace(" ", "-", $request->title);

        NewsroomCategory::insert($data);
        Session::flash('success', get_phrase('Category add successfully'));
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        $query = NewsroomCategory::where('id', $id);
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
        $query = NewsroomCategory::where('id', $id);
        if ($query->doesntExist()) {
            Session::flash('success', get_phrase('Data not found.'));
            return redirect()->back();
        }

        $query->delete();
        Session::flash('success', get_phrase('Category Delete successfully'));
        return redirect()->back();
    }
}
