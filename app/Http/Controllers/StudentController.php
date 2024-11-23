<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Admin\Student;
use Illuminate\Support\Facades\Storage;
class StudentController extends Controller
{

    public function list()
    {
        $students = Student::all();
        return view('admin.student.list', compact('students'));
    }

    public function create(){
        return view('admin.student.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:students',
            'phone_number' => 'required',
            'profile_image' => 'nullable|image',
            'joining_date' => 'required|date',
            'gender' => 'required',
            'state' => 'required',
            'file' => 'nullable|file',
        ]);
    
        $student = new Student();
        $student->fill($request->all());
    
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/student_upload'), $imageName);
            $student->profile_image = $imageName;
        }
    
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('upload/student_files'), $fileName);
            $student->file = $fileName;
        }
    
        $student->save();
    
        $notification = array(
            'message' => 'Student Profile Created Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('student.list')->with($notification);
    }
    

    public function edit($id){
        $student = Student::findOrFail($id);
        return view('admin.student.edit', compact('student'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone_number' => 'required',
            'profile_image' => 'nullable|image',
            'joining_date' => 'required|date',
            'gender' => 'required',
            'state' => 'required',
            'file' => 'nullable|file',
        ]);
    
        $student = Student::findOrFail($id);
        $student->fill($request->all());
    
        if ($request->hasFile('profile_image')) {
            // Delete the old profile image if it exists
            if ($student->profile_image) {
                $oldImagePath = public_path('upload/student_upload') . '/' . $student->profile_image;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
            // Save the new profile image
            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/student_upload'), $imageName);
            $student->profile_image = $imageName;
        }
    
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($student->file) {
                $oldFilePath = public_path('upload/student_files') . '/' . $student->file;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }
            // Save the new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/student_files'), $fileName);
            $student->file = $fileName;
        }
    
        $student->save();
    
        $notification = array(
            'message' => 'Student Profile Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('student.list')->with($notification);
    }
    


    public function delete($id)
{
    $student = Student::findOrFail($id);

    $student->delete();

    
    $notification = array(
        'message' => 'Student Profile Deleted Successfully',
        'alert-type' => 'success'
    );


    return redirect()->route('student.list')->with($notification);
}

}
