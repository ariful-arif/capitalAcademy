<?php

namespace App\Http\Controllers\instructor;

use App\Http\Controllers\Controller;
use App\Models\Course_skill;
use Illuminate\Http\Request;

class CourseSkillController extends Controller
{
    // Course skill started
    function course_skill_store(Request $request, $course_id){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $data['course_id'] = $course_id;
        $data['name']               = $request->name;
        $data['percentage']         = $request->percentage;
        $data['description']        = $request->description;
        $data['created_at']         = date('Y-m-d H:i:s');
        $data['updated_at']         = date('Y-m-d H:i:s');

        Course_skill::insert($data);

        return json_encode([
            'success' => get_phrase('Skill added successfully'),
            'html' => [
                'elem' => '#ajaxModal .modal-body',
                'content' => view('instructor.course.skill.index', ['course_id' => $course_id])->render()
            ]
        ]);
    }

    function course_skill_update(Request $request, $id){
        $current_data = Course_skill::find($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $data['name']               = $request->name;
        $data['percentage']         = $request->percentage;
        $data['description']        = $request->description;
        $data['updated_at']             = date('Y-m-d H:i:s');

        Course_skill::where('id', $id)->update($data);

        return json_encode([
            'success' => get_phrase('Skill updated successfully'),
            'html' => [
                'elem' => '#ajaxModal .modal-body',
                'content' => view('instructor.course.skill.index', ['course_id' => $current_data->course_id])->render()
            ]
        ]);
    }

    function course_skill_delete($id){
        $current_data = Course_skill::find($id);
        Course_skill::where('id', $id)->delete();

        return json_encode([
            'success' => get_phrase('Skill deleted successfully'),
            'html' => [
                'elem' => '#ajaxModal .modal-body',
                'content' => view('instructor.course.skill.index', ['course_id' => $current_data->course_id])->render()
            ]
        ]);
    }
    // Course skill ended
}
