<?php

namespace App\Http\Controllers\Backend;


use App\Vendor;
use Illuminate\Http\Request;

class ProfileController extends BackendController
{
    public function profile(Request $request)
    {
        $vendorData = Vendor::where('id', auth()->user()->id)->first();
        if ($request->isMethod('GET'))
            return  backend_view('auth.profile', compact('vendorData'));

        if($request->get('password') == ''){
           $password = auth()->user()->password;
        }else{
            $password = bcrypt($request->get('password'));
        }
        $data = [
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'password' => $password,
            'business_address' => $request->get('business_address')
        ];
        Vendor::where('id', auth()->user()->id)->update($data);
        return redirect()->route('profile')->with('success', 'Update Your Profile Successfully');
    }
}
