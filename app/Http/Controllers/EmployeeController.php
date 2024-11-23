<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
       public function create(){
        return view('admin.employee.create');
       }
    
       public function list()
        {
            $sql = "SELECT * FROM employees";
            $employees = \DB::select($sql);
            return view('admin.employee.list', compact('employees'));
        }

       public function store(Request $request)
       {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:employees,email',
            'phone_number' => 'required',
            'profile_image' => 'nullable|image',
            'joining_date' => 'required|date',
            'gender' => 'required',
            'state' => 'required',
            'file' => 'nullable|file',
        ]);

        // Handle file uploads if necessary
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/employee_upload'), $imageName);
            $data['profile_image'] = $imageName;
        }

        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('upload/employee_files'), $fileName);
            $data['file'] = $fileName;
        }

        // Prepare raw SQL query
        $sql = "INSERT INTO employees (name, surname, email, phone_number, profile_image, joining_date, gender, state, file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Execute raw SQL query
        \DB::insert($sql, [
            $data['name'],
            $data['surname'],
            $data['email'],
            $data['phone_number'],
            $data['profile_image'] ?? null,
            $data['joining_date'],
            $data['gender'],
            $data['state'],
            $data['file'] ?? null,
        ]);

        $notification = [
            'message' => 'Employees Profile Created Successfully',
            'alert-type' => 'success',
        ];

        // Optionally, redirect or return a response
        return redirect()->route('employee.list')->with($notification);
       }


       public function edit($id)
        {
            // Execute a raw SQL query to get the employee by ID
            $employee = \DB::select('SELECT * FROM employees WHERE id = ?', [$id]);
            // Since `\DB::select()` returns an array of results, 
            // we need to access the first element if we expect only one record
            $employee = $employee ? $employee[0] : null;

            return view('admin.employee.edit', compact('employee'));
        }



        public function update(Request $request, $id)
{
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone_number' => 'required',
            'profile_image' => 'nullable|image',
            'joining_date' => 'required|date',
            'gender' => 'required',
            'state' => 'required',
            'file' => 'nullable|file',
        ]);

        // Retrieve the current employee data
        $employee = \DB::select('SELECT * FROM employees WHERE id = ?', [$id]);
        $employee = $employee ? $employee[0] : null;

        // Prepare the data for update
        $updateData = [
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'joining_date' => $request->input('joining_date'),
            'gender' => $request->input('gender'),
            'state' => $request->input('state'),
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete the old profile image if it exists
            if ($employee && $employee->profile_image) {
                $oldImagePath = public_path('upload/employee_upload') . '/' . $employee->profile_image;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            // Save the new profile image
            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/employee_upload'), $imageName);
            $updateData['profile_image'] = $imageName;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($employee && $employee->file) {
                $oldFilePath = public_path('upload/employee_files') . '/' . $employee->file;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            // Save the new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/employee_files'), $fileName);
            $updateData['file'] = $fileName;
        }

        // Generate the raw SQL update query
        $sql = 'UPDATE employees SET 
                name = ?, 
                surname = ?, 
                email = ?, 
                phone_number = ?, 
                profile_image = ?, 
                joining_date = ?, 
                gender = ?, 
                state = ?, 
                file = ? 
                WHERE id = ?';

        // Execute the raw SQL query
        \DB::update($sql, [
            $updateData['name'],
            $updateData['surname'],
            $updateData['email'],
            $updateData['phone_number'],
            $updateData['profile_image'] ?? $employee->profile_image,
            $updateData['joining_date'],
            $updateData['gender'],
            $updateData['state'],
            $updateData['file'] ?? $employee->file,
            $id
        ]);

        $notification = [
            'message' => 'Employee Profile Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('employee.list')->with($notification);
    }


    public function delete($id)
        {
            // Retrieve the current employee data using a raw SQL query
            $employee = \DB::select('SELECT * FROM employees WHERE id = ?', [$id]);
            $employee = $employee ? $employee[0] : null;

            // Handle file deletions if they exist (if applicable)
            if ($employee && $employee->profile_image) {
                $oldImagePath = public_path('upload/employee_upload') . '/' . $employee->profile_image;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            if ($employee && $employee->file) {
                $oldFilePath = public_path('upload/employee_files') . '/' . $employee->file;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            // Delete the employee record using a raw SQL query
            \DB::delete('DELETE FROM employees WHERE id = ?', [$id]);

            $notification = [
                'message' => 'Employee Profile Deleted Successfully',
                'alert-type' => 'success'
            ];

            return redirect()->route('employee.list')->with($notification);
        }
}