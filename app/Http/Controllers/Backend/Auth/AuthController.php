<?php



namespace App\Http\Controllers\Backend\Auth;



use App\City;
use App\Classes\Google\GoogleAuthenticator;

use App\Country;
use App\DashboardLoginIp;

use App\Http\Requests\Backend\GoogleAuthRequest;

use App\Http\Requests\Backend\SignupRequest;

use App\Locations;
use App\Mail\VerificationCode;

use App\State;
use App\TemporaryPassword;

use App\Vendor;

use Illuminate\Support\Facades\Mail;

use Illuminate\View\View;

use Laravel\Socialite\Facades\Socialite;

use Validator;

// use App\Http\Controllers\Controller;

use App\Http\Controllers\Backend\BackendController;

use Illuminate\Foundation\Auth\ThrottlesLogins;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;

use Illuminate\Support\Facades\Hash;





class AuthController extends BackendController

{

    /*

    |--------------------------------------------------------------------------

    | Registration & Login Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles the registration of new users, as well as the

    | authentication of existing users. By default, this controller uses

    | a simple trait to add these behaviors. Why don't you explore it?

    |

    */



    use AuthenticatesAndRegistersUsers, ThrottlesLogins;



    /**

     * Where to redirect users after login / registration.

     *

     * @var string

     */

    

    protected $loginView           = 'backend.auth.login';

    protected $redirectTo          = 'dashboard';

    protected $redirectAfterLogout = 'login';



    /**

     * Create a new authentication controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);

    }



    /**

     * Get the needed authorization credentials from the request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return array

     */

    public function getCredentials(Request $request)

    {

        return $request->only($this->loginUsername(), 'password');

    }



    //To Check Permission

    public function checkPermission(Request $request){

        $admin = User::where('email', '=', $request->get('email'))->where('micro_sub_admin','1')->first();



        return $admin;

    }



    public function adminLogin(Request $request)

    {


        if ($request->isMethod('GET'))

            return $this->showLoginForm();



        $admin = User::where('email', '=', $request->email)->first();


        //Mark:- It will notify the user after matching the credentials -- Daniyal Khan

        //        if ($admin and (Hash::check($request->get('password'), $admin['password']) or  ($request->get('password') == $admin['temporary_password'])))

        //        {

        //            // Password match condition...

        //        }

        //        else{

        //

        //            return redirect('login')->withErrors('Invalid Email Address or Password!');

        //        }



        if ($admin) {
         
            $credentials = $request->only('email', 'password');



        //    if (!Auth::attempt($credentials)) {

        //        // Authentication passed...

        //        dd('ss');

        //        return redirect()->intended('dashboard');

        //    }

        //    dd(!Auth::attempt(['email'=>$request->get('email'),'password'=>bcrypt($request->get('password'))]));

        //        if (!Auth::attempt(['email'=>$request->get('email'),'password'=>$request->get('password')]))

        //        {

        //            return redirect('login')->withErrors('Invalid Username or Password.');

        //        }

            return $this->login($request);

        } else {

            return redirect('login')->withErrors('Invalid Email Address or Password!');

        }

    }







    public function redirectToProvider($driver)

    {

        return Socialite::driver($driver)->redirect();

    }



    public function handleProviderCallback($driver)

    {

        try {



            $user = Socialite::driver($driver)->user();





            $existingUser = User::where('email', $user->getEmail())->where('provider_name', $driver)->first();





            if($existingUser){



                if($existingUser->location_id == null){

                    Auth::login($existingUser);

                    return redirect()->intended('profile');

                }

                return redirect()->intended('dashboard');



            }else{

                $newUser = User::create([

                    'name' => $user->getName(),

                    'email' => $user->getEmail(),

                    'first_name' => ($driver == 'google') ? $user['given_name'] : '',

                    'last_name' => ($driver == 'google') ? $user['family_name'] : '',

                    'password' => bcrypt('admin123'),

//                    'email_verified_at' => date('Y-m-d H:i:s'),

                    'provider_name' => $driver,

                    'provider_id'=> $user->getId(),

                ]);





                Auth::login($newUser);



                return redirect()->intended('profile');

            }



        } catch (\Exception $e) {

            return redirect()->route('login');

        }



    }



    public function getType(Request $request){

        return backend_view('auth.logintype',$request->all() );

    }



    public function posttypeauth(Request $request)

    {

        $data=$request->all();

        if(strcmp("Scan",$data['type'])==0){



            return redirect('google-auth?id=' . $data['id'] . "&key=" . $data['key']);

        }

        else{



            $randomid = mt_rand(100000,999999);



            $admin = User::where('id','=', base64_decode($data['id']))->first();

            $admin['emailauthanticationcode'] = $randomid;



            $admin->save();





            //\JoeyCo\Tools\PHPMail::send("JOEYCO",$admin->attributes['email'], "Your 6 digit code for Authentication", "Your code is ".$randomid);

           $admin->sendWelcomeEmail($randomid);



            $data['email'] = base64_encode($admin['email']);



            return redirect('verify-code?key=' . $data['key'] . '&email=' . $data['email']);



        }

    }



    public function getgoogleAuth(Request $request){



        $admin = User::where('id', '=', base64_decode($request->get('id')))->first();

        $authenticator = new GoogleAuthenticator();



        if( empty($admin['googlecode']) ){



            $admin['googlecode'] = $authenticator->createSecret();

            $admin->save();

        }



        $adminLoginIpTrusted = DashboardLoginIp::where( 'dashboard_user_id','=', $admin['id'] )->whereNull('deleted_at')->first();



        if( is_null($adminLoginIpTrusted) ){

            $qrUrl =  $authenticator->getQRCodeGoogleUrl($admin['email'], $admin['googlecode']);

        }else{

            $qrUrl = null;

        }



        $data = ['secret' => $admin['googlecode'], 'qrUrl' => $qrUrl, 'email' => $admin['email'], 'key' => $request->get('key') ];



        return backend_view('auth.googleauth', $data );

    }



    public function postgoogleAuth(GoogleAuthRequest $request){





        $inputs = $request->all();



        $admin = User::where('email', '=', $request->get('email'))->where('role_id','5')->first();



        $passworddecode = base64_decode($request->get('key'));

        $request['password'] = $passworddecode;



        $authenticator = new GoogleAuthenticator();





        if( !$authenticator->verifyCode( $request->get('secret'),  $request->get('code'))) {

            return redirect('google-auth?id=' . base64_encode($admin['id']) . "&key=" . $inputs['key'])->withErrors('Your Verification Code is not Valid!.');

        }

        else if (!Auth::attempt(['email'=>$request->get('email'),'password'=>$passworddecode,'role_id'=>'4','status'=>'1']))

        {

            return redirect('login')->withErrors('Invalid Username or Password.');

        }

        else {

            if (isset($inputs['is_trusted'])) {

                $now = new \DateTime();



                DashboardLoginIp::where('dashboard_user_id', '=', $admin['id'])->where('ip', '=', $this->get_ipaddress())->delete();

                DashboardLoginIp::create(['dashboard_user_id' => $admin['id'], 'ip' => $this->get_ipaddress(), 'trusted_date' => $now->modify('+30 days')]);

            } else {



                DashboardLoginIp::create(['dashboard_user_id' => $admin['id'], 'ip' => $this->get_ipaddress()]);

            }

            return $this->login($request);

        }



    }





    public function getverifycode(Request $request){



        return backend_view('auth.verificationcode', $request->all());

    }



    public function postverifycode(Request $request){





        $code=$request->get('code');







        $data= User::where('email','=', base64_decode($request->get('email')))->where('role_id','5')->where('emailauthanticationcode','=',$code)->first();





        $email = base64_decode($request->get('email'));

        $passworddecode = base64_decode($request->get('key'));







        $request['email'] = $email;

        $request['password'] = $passworddecode;



        $email = $request->get('email');

        $key = $request->get('key');





        if(empty($data)){

            return redirect('verify-code?key=' . $key . '&email=' . base64_encode($email))->withErrors('Invalid verification code!');

        }



        else if (!Auth::attempt(['email'=>$email,'password'=>$passworddecode,'role_id'=>'5','status'=>'1']))

        {

            return redirect('login')->withErrors('Invalid Username or Password.');

        }



        return $this->login($request);

    }



    public function signup(Request $request)

    {

        if ($request->isMethod('GET'))

            return  backend_view('auth.signup');

    }



    public function signupPost(SignupRequest $request){

        //country creation
        $country = Country::where('name', $request->country)->first();
        $countryId = '';
        if(!$country){
            $country = Country::create([
                'name' => $request->country,
                'code' => $request->countryCode,
            ]);
            $countryId = $country->id;
        }else{
            $countryId = $country->id;
        }

        //state creation
        $state = State::where('name', $request->state)->first();
        $stateId = '';
        if(!$state){
            $state = State::create([
                'country_id' => $countryId,
                'name' => $request->state,
            ]);
            $stateId = $state->id;
        }else{
            $stateId = $state->id;
        }

        //city creation
        $city = City::where('name', $request->city)->first();
        $cityId = '';
        if(!$city){
            $city = State::create([
                'country_id' => $countryId,
                'state_id' => $stateId,
                'name' => $request->state,
            ]);
            $cityId = $city->id;
        }else{
            $cityId = $city->id;
        }

        //location creations
        $location = Locations::create([
            'address' => $request->address,
            'city_id' => $cityId,
            'state_id' => $stateId,
            'country_id' => $countryId,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);


        $data = [
            'first_name' => ucfirst($request->get('first_name')),
            'last_name' => ucfirst($request->get('last_name')),
            'name' => ucfirst($request->get('first_name')." ".$request->get('last_name')),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'password' => bcrypt($request->get('password')),
            'business_address' => $request->get('business_address'),
            'location_id' => $location->id
        ];

        Vendor::create($data);

        return redirect()->route('sign_up')->with('success', 'Vendor Register Successfully');

    }



    public function logout()

    {



        Auth::guard($this->getGuard())->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');

    }



    private function get_ipaddress() {



        $ipaddress = null;

        if (isset($_SERVER['HTTP_CLIENT_IP']))

            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];

        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))

            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];

        else if(isset($_SERVER['HTTP_X_FORWARDED']))

            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];

        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))

            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];

        else if(isset($_SERVER['HTTP_FORWARDED']))

            $ipaddress = $_SERVER['HTTP_FORWARDED'];

        else if(isset($_SERVER['REMOTE_ADDR']))

            $ipaddress = $_SERVER['REMOTE_ADDR'];

        return $ipaddress;

    }

}

