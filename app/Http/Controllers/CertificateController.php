<?php

namespace App\Http\Controllers;

use App\Models\CertificateProgram;
use App\Models\FileUploader;
use Illuminate\Http\Request;

class CertificateController extends Controller
{

    public function certificate_program()
    {
        $status     = 'all';
        $query  = CertificateProgram::query();
        // search filter
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where('title', 'LIKE', '%' . $_GET['search'] . '%');
        }

        // status filter
        if (isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] != 'all') {
            if ($_GET['status'] == 'active') {
                $search_status = 'active';
                $query         = $query->where('status', $search_status);
            } elseif ($_GET['status'] == 'inactive') {
                $search_status = 'inactive';
                $query         = $query->where('status', $search_status);
            }
            $status = $_GET['status'];
        }

        $page_data['status']           = $status;
        $page_data['certificate_programs']  = $query->paginate(20)->appends(request()->query());
        $page_data['active_certificate']  = CertificateProgram::where('status', 'active')->count();
        $page_data['inactive_certificate']  = CertificateProgram::where('status', 'inactive')->count();
        return view("admin.certificate_program.index", $page_data);
    }

    public function create()
    {
        return view('admin.certificate_program.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                 => 'required|max:255',
            'status'                => 'required|in:active,inactive',
        ]);
        //for normal form submission

        $data['title']                 = $request->title;
        $data['slug']                  = slugify($request->title);
        $data['user_id']               = auth()->user()->id;
        $data['status']                = $request->status;
        $data['short_description'] = $request->short_description;
        $data['description']       = $request->description;
        $data['certificated_course_count']       = $request->certificated_course_count;
        $data['course_ids'] = json_encode($request->course_ids);
        $data['created_at']  = date('Y-m-d H:i:s');
        $data['updated_at']  = date('Y-m-d H:i:s');

        if ($request->final_pdf) {
            $data['final_pdf'] = "uploads/certificate-program/final_exam_pdf/" . nice_file_name($request->title, $request->final_pdf->extension());
            FileUploader::upload($request->final_pdf, $data['final_pdf'], null, null, null, null);
        }
        if ($request->logo) {
            $data['logo'] = "uploads/certificate-program/logo/" . nice_file_name($request->title, $request->logo->extension());
            FileUploader::upload($request->logo, $data['logo'], 400, null, 200, 200);
        }
        if ($request->thumbnail) {
            $data['thumbnail'] = "uploads/certificate-program/certificate-thumbnail/" . nice_file_name($request->title, $request->thumbnail->extension());
            FileUploader::upload($request->thumbnail, $data['thumbnail'], 400, null, 200, 200);
        }

        if ($request->certificate_template) {
            $data['certificate_template'] = "uploads/certificate-program/certificate-template/" . nice_file_name($request->title, $request->certificate_template->extension());
            FileUploader::upload($request->certificate_template, $data['certificate_template']);
        }

        $certificate_id = CertificateProgram::insertGetId($data);
        CertificateProgram::where('id', $certificate_id)->update(['slug' => slugify($request->title . '-' . $certificate_id)]);

        //for normal form submission
        return redirect(route('admin.certificate_program'))->with('success', get_phrase('Certificate program create successfully'));
    }
    public function edit($id)
    {
        $page_data['certificate_programs'] = CertificateProgram::where('id', $id)->first();
        return view('admin.certificate_program.edit', $page_data);
    }
    public function update(Request $request, $id)
    {
        // $data = $request->all();
        // dd($data);
        // die;
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'status'  => 'required|in:active,inactive',
        ]);

        $certificate = CertificateProgram::find($id);

        // Prepare data for update
        $data['title']              = $request->title;
        $data['slug']               = slugify($request->title);
        $data['status']             = $request->status;
        $data['short_description']  = $request->short_description;
        $data['description']        = $request->description;
        $data['certificated_course_count']        = $request->certificated_course_count;
        $data['course_ids']         = json_encode($request->course_ids);
        $data['updated_at']         = now();

        // Handle Thumbnail Upload (if new file is uploaded)
        if ($request->hasFile('final_pdf')) {
            // Delete old file if exists
            if ($certificate->final_pdf && file_exists(public_path($certificate->final_pdf))) {
                unlink(public_path($certificate->final_pdf));
            }

            // Save new file (without resizing)
            $data['final_pdf'] = "uploads/certificate-program/final_exam_pdf/" . nice_file_name($request->title, $request->final_pdf->extension());

            // Ensure it uploads without treating as image
            FileUploader::upload($request->final_pdf, $data['final_pdf'], null, null, null, null);
        }


        if ($request->hasFile('logo')) {
            // Delete old file if exists
            if ($certificate->logo && file_exists(public_path($certificate->logo))) {
                unlink(public_path($certificate->logo));
            }

            // Save new file
            $data['logo'] = "uploads/certificate-program/logo/" . nice_file_name($request->title, $request->logo->extension());
            FileUploader::upload($request->logo, $data['logo'], 400, null, 200, 200);
        }
        if ($request->hasFile('thumbnail')) {
            // Delete old file if exists
            if ($certificate->thumbnail && file_exists(public_path($certificate->thumbnail))) {
                unlink(public_path($certificate->thumbnail));
            }

            // Save new file
            $data['thumbnail'] = "uploads/certificate-program/certificate-thumbnail/" . nice_file_name($request->title, $request->thumbnail->extension());
            FileUploader::upload($request->thumbnail, $data['thumbnail'], 400, null, 200, 200);
        }

        // Handle Certificate Template Upload (if new file is uploaded)
        if ($request->hasFile('certificate_template')) {
            // Delete old file if exists
            if ($certificate->certificate_template && file_exists(public_path($certificate->certificate_template))) {
                unlink(public_path($certificate->certificate_template));
            }

            // Save new file
            $data['certificate_template'] = "uploads/certificate-program/certificate-template/" . nice_file_name($request->title, $request->certificate_template->extension());
            FileUploader::upload($request->certificate_template, $data['certificate_template']);
        }

        // Update certificate program data
        $certificate->update($data);

        // Update slug with ID
        $certificate->update(['slug' => slugify($request->title . '-' . $certificate->id)]);

        return redirect(route('admin.certificate_program'))
            ->with('success', get_phrase('Certificate program updated successfully'));
    }
    public function delete($id)
    {
        // Find the certificate program by ID
        $certificate = CertificateProgram::findOrFail($id);

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
        return redirect()->route('admin.certificate_program')
            ->with('success', get_phrase('Certificate program deleted successfully'));
    }
}
