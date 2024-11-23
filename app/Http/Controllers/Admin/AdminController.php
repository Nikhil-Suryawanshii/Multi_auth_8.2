<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class AdminController extends Controller
{
    public function index(){
        return view("admin.index");
    }

    public function adminLogin(){
        return view("admin.admin_login");
    }

    public function adminProfile(){

          $id = Auth::user()->id;
          $adminData = User::find($id);
        return view("admin.admin_profile_view",compact('adminData'));
    }

    public function adminDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function adminProfileStore(Request $request): RedirectResponse {
        $id = Auth::user()->id;
        $adminData = User::find($id);

        $adminData->name = $request->name;
        $adminData->username = $request->username;
        $adminData->email = $request->email;
        $adminData->phone = $request->phone;
        $adminData->address = $request->address;

        $oldImagePath = $adminData->photo;

        if($request->hasFile("photo")) {
            if (!is_null($oldImagePath)) {
                $oldImagePath = public_path("upload/admin_upload") . '/' . $oldImagePath;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
            $file = $request->file("photo");
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path("upload/admin_upload"), $imageName);
            $adminData->photo = $imageName;
        }
        $adminData->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    }


    public function AdminUpdatePassword(Request $request){
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
