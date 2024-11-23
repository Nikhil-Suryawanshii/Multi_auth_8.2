<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class BrokerController extends Controller
{
    public function index(){
        return view("broker.index");
    }

    public function brokerLogin(){
        return view("broker.broker_login");
    }


    public function brokerDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/broker/login');
    }

    public function brokerProfile(){

        $id = Auth::user()->id;
        $brokerData = User::find($id);
      return view("broker.broker_profile_view",compact('brokerData'));
  }



  public function brokerProfileStore(Request $request): RedirectResponse {
    $id = Auth::user()->id;
    $brokerData = User::find($id);

    $brokerData->name = $request->name;
    $brokerData->username = $request->username;
    $brokerData->email = $request->email;
    $brokerData->phone = $request->phone;
    $brokerData->address = $request->address;

    $oldImagePath = $brokerData->photo;

    if($request->hasFile("photo")) {
        if (!is_null($oldImagePath)) {
            $oldImagePath = public_path("upload/broker_upload") . '/' . $oldImagePath;
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
        $file = $request->file("photo");
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path("upload/broker_upload"), $imageName);
        $brokerData->photo = $imageName;
    }
    $brokerData->save();

    $notification = array(
        'message' => 'Broker Profile Updated Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
}

public function BrokerChangePassword(){
    return view('broker.broker_change_password');
}


public function BrokerUpdatePassword(Request $request){
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
