<?php



namespace App\Http\Controllers\Backend;



use App\ClientSetting;

use App\ProfileCard;

use App\AccountCard;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\ProfileCardRequest;

use DB;

use Validator;



class ClientSettingController extends BackendController

{

    /**

     * Print Size Label

     */

    public function labelSetting($id)

    {

        $label_size = '';

        $client_user_name = null;

        $labelSize = ClientSetting::where('user_id', $id)->first();

        if (!empty($labelSize)) {

            $label_size = $labelSize->print_size;

            $client_user_name = $labelSize->id;

        }



        return backend_view('client-setting.label-setting', compact('label_size', 'client_user_name'));

    }





    public function labelSizeCreate(Request $request)

    {

        $client_setting = ClientSetting::where("id", "=", $request->client_user_id)->first();

        if (!is_null($client_setting)) {

            $client_setting->print_size = $request->print_size;

            $client_setting->save();



            session()->flash('alert-success', 'Page size has been updated successfully!');



            return redirect('label-setting/' . Auth::user()->id);

        } else {

            $createPrintSize = [

                'user_id' => Auth::user()->id,

                'print_size' => $request->print_size

            ];



            ClientSetting::create($createPrintSize);



            session()->flash('alert-success', 'Page size has been created successfully!');



            return redirect('label-setting/' . Auth::user()->id);

        }



    }

    public function profileCreate($id)

    {
       
       $profile = ProfileCard::where('user_id', $id)->first();

        return backend_view('client-setting.profile-create', compact('profile','id'));

    }

    public function accountCreate($id)

    {

        $profile = AccountCard::where('user_id', $id)->first();

      

        return backend_view('client-setting.account-create', compact('profile','id'));

    }

    public function profileStore(Request $request)

    {

        $this->validate($request, [

            'first_name' => 'required|min:3|max:50',

            'email' => 'required|email:rfc,dns|max:25',

            'address' => 'required|max:50',

            'city' => 'required|max:10',

            'state' => 'required|max:10',

            'zip' => 'required|max:6',

            'card_name' => 'required|max:50',

            'card_number' => 'required|max:19|min:19',

            'exp_month' => 'required|digits:2|max:12|numeric',

            'exp_year' => 'required|digits:4|min:'.date("Y").'|max:'.(date("Y")+10).'|numeric',

           

            'cvv' => 'required|digits:3',

        ], [

            'first_name.required' => 'full name is required',

            'email.required' => 'email is required',

            'address.required' => 'address is required',

            'city.required' => 'city is required',

            'state.required' => 'state is required',

            'zip.required' => 'zip code is required',

            'card_name.required' => 'name on card is required',

            'card_number.required' => 'credit card number is required',

            // 'exp_month.required' => 'Exp Month is required',

            'exp_year.required' => 'exp year is required',

            'cvv.required' => 'CVV is required',

            // max message

            'first_name.max' => 'full name may not be greater than 50 characters',

            'email.max' =>       'email may not be greater than 25 characters',

            'address.max' => 'address may not be greater than 50 characters',

            'city.max' => 'city may not be greater than 10 characters',

            'state.max' => 'state may not be greater than 10 characters',

            'zip.max' => 'zip code may not be greater than 6 characters',

            'card_name.max' => 'card name may not be greater than 50 characters',

            'card_number.max' => 'card number may not be greater than 16 characters',

            'exp_month.max' => 'month between 01 to 12',

            'exp_year.max' => 'card is expired',

            'cvv.digit' => 'cvv digit must be 3 ex:123',

        //    min message

        'first_name.min' => 'full name must be 3 characters',

        'card_number.min' => 'card number must be 16 characters',

        'exp_year.min' => 'year not less than current year',



        ]);

// add bank account



        $profile = ProfileCard::where('user_id', $request->id)->first();
if(!$profile){
    $profile = new ProfileCard();
    $profile->user_id =  $request->id;
}
        $profile->first_name = $request->first_name;

        $profile->email = $request->email;

        $profile->address = $request->address;

        $profile->city = $request->city;

        $profile->state = $request->state;

        $profile->zip = $request->zip;

        $profile->card_name = $request->card_name;

        $profile->card_number = $request->card_number;

        $profile->exp_month = $request->exp_month;

        $profile->exp_year = $request->exp_year;

        $profile->cvv = $request->cvv;

        $profile->save();





        return back()->with('message', 'Card info successfully update');

    }







    public function accountStore(Request $request)

    {

       

        $this->validate($request, [

            'account_type' => 'required|min:3|max:50',

            'country_type' => 'required|min:3|max:50',

            'iban' => 'required|max:16|min:16',

            'account_number' => 'required|max:10',

            'bank_number' => 'required|max:10',

            'currency' => 'required|max:10',

            'branch_id' => 'required|max:6',

            'check_digit' => 'required|digits:6',

        ], [

            'account_type.required' => 'account type is required',

            'country_type.required' => 'country type is required',

            'iban.required' => 'IBAN is required',

            'account_number.required' => 'account number is required',

            'bank_number.required' => 'bank number is required',

            'currency.required' => 'currency is required',

            'branch_id.required' => 'branch id is required',

            'check_digit.required' => 'check digit is required',

          

            // // max message

            // 'first_name.max' => 'full name may not be greater than 50 characters',

            // 'email.max' =>       'email may not be greater than 25 characters',

            // 'address.max' => 'address may not be greater than 50 characters',

            // 'city.max' => 'city may not be greater than 10 characters',

            // 'state.max' => 'state may not be greater than 10 characters',

            // 'zip.max' => 'zip code may not be greater than 6 characters',

            // 'card_name.max' => 'card name may not be greater than 50 characters',

            // 'card_number.max' => 'card number may not be greater than 16 characters',

            'cvv.digit' => 'Check Digit  must be 6 ex:123456',



        ]);

  $request->all();

        $user = AccountCard::UpdateOrCreate([

            'user_id' => $request->id

        ], [

            'account_type' =>$request->account_type,

            'country_type' => $request->country_type,

            'iban' => $request->iban,

            'account_number' =>$request->account_number,

            'currency' => $request->currency,

            'branch_id' => $request->branch_id,

            'check_digit' => $request->check_digit,

            'user_id' => $request->id,
            'bank_number' => $request->bank_number,
        ]);

    

     

        return back()->with('message', 'Account info successfully update');

    }

}