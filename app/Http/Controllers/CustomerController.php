<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
   public function list(){
    $customers = DB::table('customers')->get();
    return view('admin.customer.list',compact('customers'));
   }

   public function create(){
    return view('admin.customer.create');
   }

   
   public function store(Request $request)
   {
       // Validate the request data
       $data = $request->validate([
           'name' => 'required',
           'surname' => 'required',
           'email' => 'required|email|unique:customers,email',
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
           $file->move(public_path('upload/customer_upload'), $imageName);
           $data['profile_image'] = $imageName; // Use $data['profile_image'] instead of $data->profile_image
       }
   
       if ($request->hasFile('file')) {
           $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
           $request->file('file')->move(public_path('upload/customer_files'), $fileName);
           $data['file'] = $fileName; // Use $data['file'] instead of $data->file
       }
   
       // Insert the data into the database
       DB::table('customers')->insert($data);
   
       $notification = array(
           'message' => 'Customer Profile Created Successfully',
           'alert-type' => 'success'
       );
   
       // Optionally, redirect or return a response
       return redirect()->route('customer.list')->with($notification);
   }

   

   public function edit($id)
   {
       $customer = DB::table('customers')->where('id', $id)->first();
       return view('admin.customer.edit', compact('customer'));
   }


   public function update(Request $request, $id)
   {
       $request->validate([
           'name' => 'required',
           'surname' => 'required',
           'email' => 'required|email|unique:customers,email,' . $id,
           'phone_number' => 'required',
           'profile_image' => 'nullable|image',
           'joining_date' => 'required|date',
           'gender' => 'required',
           'state' => 'required',
           'file' => 'nullable|file',
       ]);
   
       // Retrieve the current customer data
       $customer = DB::table('customers')->where('id', $id)->first();
   
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
           if ($customer->profile_image) {
               $oldImagePath = public_path('upload/customer_upload') . '/' . $customer->profile_image;
               if (file_exists($oldImagePath)) {
                   @unlink($oldImagePath);
               }
           }
   
           // Save the new profile image
           $file = $request->file('profile_image');
           $imageName = time() . '_' . $file->getClientOriginalName();
           $file->move(public_path('upload/customer_upload'), $imageName);
           $updateData['profile_image'] = $imageName;
       }
   
       // Handle file upload
       if ($request->hasFile('file')) {
           // Delete the old file if it exists
           if ($customer->file) {
               $oldFilePath = public_path('upload/customer_files') . '/' . $customer->file;
               if (file_exists($oldFilePath)) {
                   @unlink($oldFilePath);
               }
           }
   
           // Save the new file
           $file = $request->file('file');
           $fileName = time() . '_' . $file->getClientOriginalName();
           $file->move(public_path('upload/customer_files'), $fileName);
           $updateData['file'] = $fileName;
       }
   
       // Update the customer data
       DB::table('customers')->where('id', $id)->update($updateData);
   
       $notification = array(
           'message' => 'Customer Profile Updated Successfully',
           'alert-type' => 'success'
       );
   
       return redirect()->route('customer.list')->with($notification);
   }


   public function delete($id)
{
    // Retrieve the current customer data
    $customer = DB::table('customers')->where('id', $id)->first();



    // Handle file deletions if they exist (if applicable)
    if ($customer->profile_image) {
        $oldImagePath = public_path('upload/customer_upload') . '/' . $customer->profile_image;
        if (file_exists($oldImagePath)) {
            @unlink($oldImagePath);
        }
    }

    if ($customer->file) {
        $oldFilePath = public_path('upload/customer_files') . '/' . $customer->file;
        if (file_exists($oldFilePath)) {
            @unlink($oldFilePath);
        }
    }

    // Delete the customer record
    DB::table('customers')->where('id', $id)->delete();

    $notification = array(
        'message' => 'Customers Profile Deleted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('customer.list')->with($notification);
}
}
