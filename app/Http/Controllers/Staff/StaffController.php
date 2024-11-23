<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class StaffController extends Controller
{
    public function index(){
        return view("staff.index");
    }


    public function staffLogin(){
        return view("staff.staff_login");
    }


    public function staffDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/staff/login');
    }

    public function staffProfile(){

        $id = Auth::user()->id;
        $staffData = User::find($id);
      return view("staff.staff_profile_view",compact('staffData'));
  }



  public function staffProfileStore(Request $request): RedirectResponse {
    $id = Auth::user()->id;
    $staffData = User::find($id);

    $staffData->name = $request->name;
    $staffData->username = $request->username;
    $staffData->email = $request->email;
    $staffData->phone = $request->phone;
    $staffData->address = $request->address;

    $oldImagePath = $staffData->photo;

    if($request->hasFile("photo")) {
        if (!is_null($oldImagePath)) {
            $oldImagePath = public_path("upload/staff_upload") . '/' . $oldImagePath;
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
        $file = $request->file("photo");
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path("upload/staff_upload"), $imageName);
        $staffData->photo = $imageName;
    }
    $staffData->save();

    $notification = array(
        'message' => 'Staff Profile Updated Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
}

public function StaffChangePassword(){

    return view('staff.staff_change_password');
}


public function StaffUpdatePassword(Request $request){

    // Validation
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|confirmed',
    ]);

    // Match The Old Password
    if (!Hash::check($request->old_password, auth::user()->password)) {
        return back()->with("error", "Old Password Doesn't Match!!");
    }

    // Update The new password
    User::whereId(auth()->user()->id)->update([
        'password' => Hash::make($request->new_password)

    ]);
    return back()->with("status", " Password Changed Successfully");

} // End Mehtod





}
