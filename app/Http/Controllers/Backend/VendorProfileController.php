<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;

class VendorProfileController extends Controller
{
    public function index()
    {
        return view("vendor.dashboard.profile");
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->id],
            'phone' => ['max:12'],
            'image' => ['image', 'max:20000']
        ]);

        $user = Auth::user();

        // Check if there is an image attached to the request, if doesnt exist then for loop will be ignored
        if ($request->hasFile('image')) {

            // This loop check if the image attached already existed or not in the app storage,
            // If the image already exist, the old image will be deleted and replaced with the image in the request
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }

            // This code will get the image in the request
            $image = $request->image;

            // This code will make sure the every image have unique name, if not, there will be an issue if multiple image have same name
            $imageName = rand() . '_' . $image->getClientOriginalName();

            // This code will move/upload the image in the request to uploads folder in public folder
            $image->move(public_path('uploads'), $imageName);

            // This code append path name to the image name so the image path will be stored in the image attributes in database
            $path = "/uploads/" . $imageName;
            $user->image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        toastr()->success('Profile Updated Successfully', 'Success');
        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);


        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        toastr()->success('Password Updated Successfully', 'Success');
        return redirect()->back();
    }
}
