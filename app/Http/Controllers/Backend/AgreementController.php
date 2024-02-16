<?php

namespace App\Http\Controllers\Backend;

use App\Agreements;
use App\AgreementsUser;
use App\HubProcess;
use App\User;
use Illuminate\Http\Request;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgreementController extends Controller
{

    function getIndex(){

        $record = Agreements::where('target', 'vendors')->first();
        return view('backend/agreement', compact('record'));
    }

    public function agreementSigned(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if($user == null){
           return redirect()->route('agreement')->with('error', 'This email does not exists');
        }

        $data = [
            'agreement_id' => $request->agreement_id,
            'user_id' => Auth::user()->id,
            'user_type' => 'vendors',
            'signed_at' => date('Y-m-d H:i:s'),
        ];

        AgreementsUser::create($data);
        return redirect()->route('profile')->with('success', 'You have successfully signed agreement');
    }

}
