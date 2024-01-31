<?php

namespace App\Http\Controllers\Backend;
use App\Dispatch;
use Illuminate\Http\Request;
use App\Sprint;
use App\Task;
use App\Country;
use App\City;
use App\State;
use App\Locations;
//use App\LocationsUnencrypted;
use App\SprintContact;
use App\ContactEnc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StepFormController extends BackendController{
    /**
     * Get  profiles
     */
    public function index(){
        return backend_view( 'step-form.index');
    }

    public function save_data(Request $request){
        // return json_encode($request->all());
//        $key = 'c9e92bb1ffd642abc4ceef9f4c6b1b3aaae8f5291e4ac127d58f4ae29272d79d903dfdb7c7eb6e487b979001c1658bb0a3e5c09a94d6ae90f7242c1a4cac60663f9cbc36ba4fe4b33e735fb6a23184d32be5cfd9aa5744f68af48cbbce805328bab49c99b708e44598a4efe765d75d7e48370ad1cb8f916e239cbb8ddfdfe3fe';
//        $iv ='f13c9f69097a462be81995330c7c68f754f0c6026720c16ad2c1f5f316452ee000ce71d64ed065145afdd99b43c0d632b1703fc6a6754284f5d19b82dc3697d664dc9f66147f374d46c94cf23a78f14f0c6823d1cbaa19c157b4cb81e106b79b11593dcddf675951bc07f54528fc8c03cf66e9c437595d1cac658a737ab1183f';
        
        // creating sprint
        $sprint = Sprint::create([
            'creator_id' => Auth::user()->id,
            'creator_type' => 'vendor',
            'is_reattempt' => 0
        ]);

        /**
         * Pick up detail start
         */

        //Country
        $pickup_country = Country::where('name',$request->pickup_country)->first();
        if($pickup_country){
            $pickup_country_id = $pickup_country->id;
        }
        else{
            $add_country_pickup = Country::create([
                'tax_id' => 1,
                'name' => $request->pickup_country,
                'code' => 'SA'
            ]);
            $pickup_country_id = $add_country_pickup->id;
        }

        //State
        $pickup_state = State::where('name',$request->pickup_state)->first();
        if($pickup_state){
            $pickup_state_id = $pickup_state->id;
        }
        else{
            $add_state_pickup = State::create([
                'country_id' => $pickup_country_id,
                'tax_id' => 1,
                'name' => $request->pickup_state,
                'code' => 'SA',
            ]);
            $pickup_state_id = $add_state_pickup->id;
        }
        
        //City
        $pickup_city = City::where('name',$request->pickup_city)->first();
        if($pickup_city){
            $pickup_city_id = $pickup_city->id;
        }
        else{
            $add_city_pickup = City::create([
                'country_id' => $pickup_country_id,
                'state_id' => $pickup_state_id,
                'name' => $request->pickup_city,
            ]);
            $pickup_city_id = $add_state_pickup->id;
        }

        // checking location
        $latitude_int = str_replace(".", "", $request->pickup_latitude);;
        $longitude_int = $request->pickup_longitude* 1000000;
        if(strlen($latitude_int) > 8){
            $latitude_int = (int)substr($latitude_int, 0, 8);
        }
        else{
            $diff = 8 - strlen($latitude_int);
            $fixed_lat = $latitude_int;
            while($diff>0){
                $fixed_lat .= "0";
                $diff--;
            }
            $latitude_int = $fixed_lat;
        }

        if(strlen($longitude_int) > 9){
            $longitude_int = (int)substr($longitude_int, 0, 9);
        }
        else{
            $diff = 9 - strlen($longitude_int);
            $fixed_lng = $longitude_int;
            while($diff>0){
                $fixed_lng .= "0";
                $diff--;
            }
            $longitude_int = $fixed_lng;
        }
        
        // dd($pickup_city_id, $pickup_state_id, $pickup_country_id);

        $location_pickup = Locations::where('latitude',(int)$request->pickup_latitude)
        ->where('longitude',(int)$request->pickup_longitude)
        ->first();
        if($location_pickup){
            $location_pickup_id = $location_pickup->id;
        }
        else{
            // creating locations
            $location_pickup = Locations::create([
                'address' => $request->pickup_address,
                'city_id' => $pickup_city_id,
                'state_id' => $pickup_state_id,
                'country_id' => $pickup_country_id,
                'postal_code' => $request->pickup_postal_code,
                'latitude' => (int) $latitude_int,
                'longitude' => (int) $longitude_int,
            ]);
            $location_pickup_id = $location_pickup->id;

//            $enc_location = DB::table('locations_enc')->insert(
//                array(
//                    'id' => $location_pickup_id,
//                    'address' => DB::raw("AES_ENCRYPT('". $request->pickup_address."', '".$key."', '".$iv."')"),
//                    'city_id' => $pickup_city_id,
//                    'state_id' => $pickup_state_id,
//                    'country_id' => $pickup_country_id,
//                    'postal_code' => DB::raw("AES_ENCRYPT('".$request->pickup_postal_code."', '".$key."', '".$iv."')"),
//                    // 'suite' => DB::raw("AES_ENCRYPT('".$location_suite."', '".$key."', '".$iv."')"),
//                    'latitude' => DB::raw("AES_ENCRYPT('".(int) $latitude_int ."', '".$key."', '".$iv."')"),
//                    'longitude' => DB::raw("AES_ENCRYPT('". (int) $longitude_int."', '".$key."', '".$iv."')"),
//                    'created_at' => date('Y-m-d H:i:s'),
//                    'updated_at' => date('Y-m-d H:i:s'),
//                    )
//            );
        }

        // Contacts 
        $contact_details = SprintContact::where('phone',$request->user_phone)
        ->where('email',$request->usr_regemail)
        ->where('name',$request->reg_fname)->first();
        if($contact_details){
            $contact_details_id = $contact_details->id;
        }
        else{
            $add_contact_details = SprintContact::create([
                'name' => $request->reg_fname,
                'email' => $request->usr_regemail,
                'phone' => $request->user_phone,
            ]);
            $contact_details_id = $add_contact_details->id;
//            $enc_contact = DB::table('contacts_enc')->insert(
//                array(
//                    'id' => $contact_details_id,
//                    'name' => DB::raw("AES_ENCRYPT('".$request->reg_fname."', '".$key."', '".$iv."')"),
//                    'email' => DB::raw("AES_ENCRYPT('".$request->usr_regemail."', '".$key."', '".$iv."')"),
//                    'phone' => DB::raw("AES_ENCRYPT('".$request->user_phone."', '".$key."', '".$iv."')"),
//                    'created_at' => date('Y-m-d H:i:s'),
//                    'updated_at' => date('Y-m-d H:i:s'),
//                )
//            );
        }

        // Sprint Task pickup 
        Task::create([
            'sprint_id' => $sprint->id,
            'ordinal' => 1,
            'type' => "pickup",
            'location_id' => $location_pickup_id,
            'contact_id' => $contact_details_id,
            'status_id' => 61,
        ]);
        // pick up details ===================== end

        /**
         * Drop off detail start
         */

        //Country
        $dropoff_country = Country::where('name',$request->dropoff_country)->first();
        if($dropoff_country){
            $dropoff_country_id = $dropoff_country->id;
        }
        else{
            $add_country_dropoff = Country::create([
                'tax_id' => 1,
                'name' => $request->dropoff_country,
                'code' => 'CA'
            ]);
            $dropoff_country_id = $add_country_dropoff->id;
        }

        //State
        $dropoff_state = State::where('name',$request->dropoff_state)->first();
        if($dropoff_state){
            $dropoff_state_id = $dropoff_state->id;
        }
        else{
            $add_state_pickup = Country::create([
                'country_id' => $dropoff_country_id,
                'tax_id' => 1,
                'name' => $request->dropoff_state,
                'code' => 'ON',
            ]);
            $dropoff_state_id = $add_state_pickup->id;
        }
        
        //City
        $dropoff_city = City::where('name',$request->dropoff_city)->first();
        if($dropoff_city){
            $dropoff_city_id = $dropoff_city->id;
        }
        else{
            $add_city_dropoff = City::create([
                'country_id' => $dropoff_country_id,
                'state_id' => $dropoff_state_id,
                'name' => $request->dropoff_city,
            ]);
            $dropoff_city_id = $add_city_dropoff->id;
        }

        // checking location
        $latitude_int_dropoff = str_replace(".", "", $request->dropoff_latitude);;
        $longitude_int_dropoff = $request->dropoff_longitude* 1000000;
        if(strlen($latitude_int_dropoff) > 8){
            $latitude_int_dropoff = (int)substr($latitude_int, 0, 8);
        }
        else{
            $diff = 8 - strlen($latitude_int);
            $fixed_lat = $latitude_int;
            while($diff>0){
                $fixed_lat .= "0";
                $diff--;
            }
            $latitude_int_dropoff = $fixed_lat;
        }

        if(strlen($longitude_int_dropoff) > 9){
            $longitude_int_dropoff = (int)substr($longitude_int, 0, 9);
        }
        else{
            $diff = 9 - strlen($longitude_int);
            $fixed_lng = $longitude_int;
            while($diff>0){
                $fixed_lng .= "0";
                $diff--;
            }
            $longitude_int_dropoff = $fixed_lng;
        }
        
        

        $location_dropoff = Locations::where('latitude',(int) $latitude_int_dropoff)
        ->where('longitude',(int) $longitude_int_dropoff)
        ->first();
        if($location_dropoff){
            $location_dropoff_id = $location_dropoff->id;
        }
        else{
            // creating locations
            $location_dropoff = Locations::create([
                'address' => $request->dropoff_address,
                'city_id' => $dropoff_city_id,
                'state_id' => $dropoff_state_id,
                'country_id' => $dropoff_country_id,
                'postal_code' => $request->dropoff_postal_code,
                'latitude' => (int) $latitude_int_dropoff,
                'longitude' => (int) $longitude_int_dropoff,
            ]);
            $location_dropoff_id = $location_dropoff->id;
//            $enc_location = DB::table('locations_enc')->insert(
//                array(
//                    'id' => $location_dropoff_id,
//                    'address' => DB::raw("AES_ENCRYPT('". $request->dropoff_address."', '".$key."', '".$iv."')"),
//                    'city_id' => $dropoff_city_id,
//                    'state_id' => $dropoff_state_id,
//                    'country_id' => $dropoff_country_id,
//                    'postal_code' => DB::raw("AES_ENCRYPT('".$request->dropoff_postal_code."', '".$key."', '".$iv."')"),
//                    // 'suite' => DB::raw("AES_ENCRYPT('".$location_suite."', '".$key."', '".$iv."')"),
//                    'latitude' => DB::raw("AES_ENCRYPT('".(int) $latitude_int_dropoff ."', '".$key."', '".$iv."')"),
//                    'longitude' => DB::raw("AES_ENCRYPT('". (int) $longitude_int_dropoff."', '".$key."', '".$iv."')"),
//                    'created_at' => date('Y-m-d H:i:s'),
//                    'updated_at' => date('Y-m-d H:i:s'),
//                    )
//            );
        }

        // Contacts 
        $contact_details_dropoff = SprintContact::where('phone',$request->dropoff_user_phone)
        ->where('email',$request->usr_regemail)
        ->where('name',$request->dropoff_reg_fname)->first();
        if($contact_details_dropoff){
            $contact_details_dropoff_id = $contact_details->id;
        }
        else{
            $add_contact_details_dropoff = SprintContact::create([
                'name' => $request->dropoff_reg_fname,
                'email' => $request->usr_regemail,
                'phone' => $request->dropoff_user_phone,
            ]);
            $contact_details_dropoff_id = $add_contact_details_dropoff->id;
//            $enc_contact = DB::table('contacts_enc')->insert(
//                array(
//                    'id' => $contact_details_dropoff_id,
//                    'name' => DB::raw("AES_ENCRYPT('".$request->dropoff_reg_fname."', '".$key."', '".$iv."')"),
//                    'email' => DB::raw("AES_ENCRYPT('".$request->usr_regemail."', '".$key."', '".$iv."')"),
//                    'phone' => DB::raw("AES_ENCRYPT('".$request->dropoff_user_phone."', '".$key."', '".$iv."')"),
//                    'created_at' => date('Y-m-d H:i:s'),
//                    'updated_at' => date('Y-m-d H:i:s'),
//                )
//            );
        }

        // Sprint Task pickup 
        Task::create([
            'sprint_id' => $sprint->id,
            'ordinal' => 2,
            'type' => "dropoff",
            'location_id' => $location_dropoff->id,
            'contact_id' => $contact_details_dropoff_id,
            'status_id' => 61,
        ]);
        // dropoff details ===================== end

        $distance = $this->getDistanceBetweenPoints($request->pickup_latitude,$request->pickup_longitude,$request->dropoff_latitude, $request->dropoff_longitude);

        Dispatch::create([
            'order_id' => $sprint->id,
            'num' => 'CR-'.$sprint->id,
            'creator_id' => Auth::user()->id,
            'sprint_id' => $sprint->id,
            'status' => 61,
            'distance' => $distance,
            'active' => 1,
            'type' => 'custom-run',
            'pickup_location_id' => $location_pickup_id,
            'pickup_contact_name' => $request->reg_fname,
            'pickup_address' => $request->pickup_address,
            'pickup_contact_phone' => $request->user_phone,
            'dropoff_contact_phone' => $request->dropoff_user_phone,
            'dropoff_location_id' => $location_dropoff_id,
            'dropoff_address' => $request->dropoff_address,
            'date' => time(),

        ]);


        return json_encode($request->all());
    }

    public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {

        $token='pk.eyJ1Ijoiam9leWNvIiwiYSI6ImNpbG9vMGsydzA4aml1Y2tucjJqcDQ2MDcifQ.gyd_3OOVqdByGDKjBO7lyA';
        // $response = file_get_contents('https://api.mapbox.com/directions/v5/mapbox/driving/'.$first_pickup["lng"].','.$first_pickup["lat"].';'.$dropoff_string.'?access_token='.$token);

        $response = file_get_contents('https://api.mapbox.com/directions/v5/mapbox/driving/'.$lon2.','.$lat2.';'.$lon1.','.$lat1.'?access_token='.$token);
        // $response = file_get_contents('https://api.mapbox.com/directions/v5/mapbox/driving/-79.5747,43.7112;-79.5962,43.6236?access_token='.$token);


        $response=json_decode($response,true);

        if(isset($response['routes'][0]['distance'])){
            return $response['routes'][0]['distance'];
        }else{
            $theta = $lon1 - $lon2;
            $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;
            $feet = $miles * 5280;
            $yards = $feet / 3;
            $kilometers = $miles * 1.609344;
            $meters = $kilometers * 1000;
            return $meters;
        }

    }

}
