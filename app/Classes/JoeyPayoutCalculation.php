<?php

namespace App\Classes;

/**
 * Created by Muhammad Adnan Nadeem.
 * Email: adnannadeem1994@gamil.com
 * Date: 6/02/2021
 * Time: 5:38 PM
 */

class JoeyPayoutCalculation
{

    static function calculate($data = [],$extra_query_data = [])
    {
        $return_data = [];
        $system_parameters = $extra_query_data['system_parameters'];
        $JoeysPlanTypes = $extra_query_data['JoeysPlanTypes'];
        $per_page_records = $data->perPage();
        $page_no = $data->currentPage();
        $iteration = ($per_page_records * $page_no) - $per_page_records;
        $iteration++;

        // static work for temp
        $city_names = [
            "montreal" => [477260],
            "ottawa" => [477282,477340,477341,477342,477342,477343,477344,477345,477346],
            'toronto'=>[477255,477254,477283,477284,477286,477287,477288,477289,477307,477308,477309,477310,477311,477312,477313,477314,477292,477294,477315,477317,477316,477295,477302,477303,477304,477305,477306,477296,477290,477297,477298,477299,477300,477320,477301,477318,477328,476294,477334,477335,477336,477337,477338,477339],
        ];

        $city_name = '';

        $index = 0;
        // looping up data for calculation
        foreach($data as $single_data)
        {

            // initializing variables for loop data which are calling the same function multiple time causing query load
			//setting filter date
            $single_data->DateRangeSelected['start_date'] = $extra_query_data['start_date'];
            $single_data->DateRangeSelected['end_date'] = $extra_query_data['end_date'];

            /*block for checking the RouteHistoryLatest have data */
            $FirstPickUpScan = $single_data->JoeyFirstPickUpScan();
            $FirstDropScan = $single_data->JoeyFirstDropScan();
            $LastDropScan = $single_data->JoeyLastDropScan();
            $TotalKM = $single_data->TotalKM();
            $ActualTotalKM = $single_data->JoeyActualTotalKM();
            // adding less then sign if its zero
            $ActualTotalKM = ($ActualTotalKM > 0 )? $ActualTotalKM : '< 1';
            $ActualTotalKM_numaric = floatval($ActualTotalKM);
            $TotalOrderDropsCount = (isset($single_data->JoeyRouteLocationsByRouteID))? $single_data->JoeyRouteLocationsByRouteID->count() : 0 ;
            $TotalOrderDropsCompletedCount = $single_data->JoeyTotalOrderDropsCompletedCount();
            $TotalOrderPickedCount = $single_data->JoeyTotalOrderPickedCount();
            $TotalOrderReturnCount = $single_data->JoeyTotalOrderReturnCount();
            $TotalOrderUnattemptedCount = $single_data->JoeyTotalOrderUnattemptedCount();

            // checking if the joey did not attempt any order then remove this route from report
            if($TotalOrderDropsCompletedCount <= 0 && $TotalOrderReturnCount <= 0 && $TotalOrderPickedCount <=0)
            {
                continue;
            }

            $ActualDuration = DifferenceTwoDataTime($FirstDropScan,$LastDropScan);
            $TotalDuration = DifferenceTwoDataTime($FirstPickUpScan,$LastDropScan);

            // getting joey plan
            $plan_name = 'Not set new plan';
            $plan_internal_name = 'Not set';

            $brooker_name = (count($single_data->joey->Brooker) > 0)? $single_data->joey->Brooker->first()->name: 'N/A';
            $is_tax_applied = ($single_data->Joey->is_tax_applied == 0 &&  $brooker_name == 'N/A')? false: true;
            $is_tax_applied_string = ($is_tax_applied)? 'true' : 'false';
            $joey_plan_data = (isset($single_data->joey->plan))? $single_data->joey->plan: null ;
            $joey_plan_detail_data = (isset($joey_plan_data->PlanDetails))? $joey_plan_data->PlanDetails : null;


            /*calculation for payout report*/
            $tech_amount = $system_parameters['tech']->value * $TotalOrderDropsCompletedCount;
            $gas_amount = 0;
            $truck_amount = 0;
            $hourly = 0;
            $total_cost = 0;
            $flag_bonus = 0;
            $flag_deduction = 0;
            $payout = 0;
            $payout_with_actual_duraion = 0;
            $payout_with_total_duraion = 0;
            $cost_per_drop_on_hourly = 0;
            $payout_calculations = "";


            // checking the route type
            $zone_id = $single_data->JoeyRoute->zone;

            $zone_routing_data = $single_data->ZoneRouting;
            dd([$zone_id,$zone_routing_data]);
            $is_custom_route = 'false';
            $route_type = 'normal_route';
            $zone_name = (isset($zone_routing_data->title))? $zone_routing_data->title : 'Not set' ;

            if($zone_routing_data != null)
            {
                // now checking this route type
                if($zone_routing_data->is_custom_routing == 1)
                {
                    $is_custom_route = 'true';
                    $route_type = 'custom_route';
                }

            }
            elseif(is_null($zone_id))
            {
                $is_custom_route = 'true';
                $route_type = 'custom_route';
            }

            // checking this route use big box
            if($single_data->IsthisRouteUseBigBox())
            {
                $route_type = 'big_box_route';
            }


            // checking the type exist and plan details
            if($joey_plan_data != null && $joey_plan_detail_data != null)
            {

                // static setting for plan type by area sub or downtown
                $planTypeSelection = [
                    "normal_route"=> [1=>0,2=>1],
                    "custom_route"=> [1=>2,2=>3],
                    "big_box_route"=>[1=>4,2=>5],
                ];



                // setting plan name
                $plan_name = $joey_plan_data->PlanDetailNames;

                $plan_internal_name = $joey_plan_data->internal_name;

                // checking the joey useing company vehicle
                if($joey_plan_data->vehicle_using_type == 1)
                {
                    $gas_amount = $system_parameters['gas']->value *  $ActualTotalKM_numaric;
                }

                if(in_array($joey_plan_data->plan_type, $JoeysPlanTypes['per_drop_plan']))
                {

                    // selecting plan according to route type
                    $selected_joey_plan_detail = null;
                    if($route_type == 'custom_route') // this block use for select custom route rates
                    {
                        $selected_joey_plan_detail = $joey_plan_detail_data[1];
                    }
                    elseif($route_type == 'big_box_route') // block for big box
                    {
                        $selected_joey_plan_detail = $joey_plan_detail_data[2];
                    }
                    else
                    {
                        $selected_joey_plan_detail = $joey_plan_detail_data[0];
                    }

                    $total_cost = ($is_tax_applied) ? $selected_joey_plan_detail->total_cost : $selected_joey_plan_detail->sub_joey_charges;
                    $total_cost = $total_cost / $selected_joey_plan_detail->drops;
                    $payout = ($total_cost * $TotalOrderDropsCompletedCount);
                    $truck_amount = $selected_joey_plan_detail->gas_truck_amount;
                    $hourly = round( HoursToMinutes($ActualDuration) / 60,2) ;
                    $payout_calculations='total cost['.$total_cost.'] x total compeleted drops['.$TotalOrderDropsCompletedCount.']';
                    $payout_calculations.='<br>  Is This Custom Route = '.$is_custom_route;
                    $payout_calculations.='<br>  is applying tax = '.$is_tax_applied_string;

                }
                elseif(in_array($joey_plan_data->plan_type, $JoeysPlanTypes['by_duration'])) // calculation for by duration plan
                {

                    // checking the plan type is set for any error occurred fallback on sub plan
                    $selected_joey_plan_detail  = $joey_plan_detail_data[0];
                    $zone_not_found_massage = 'Zone id not found applying sub plan ';
                    if(isset($zone_routing_data->zone_type) && isset($planTypeSelection['normal_route'][$zone_routing_data->zone_type]))
                    {
                        $selected_joey_plan_detail  =  $joey_plan_detail_data[$planTypeSelection['normal_route'][$single_data->ZoneRouting->zone_type]];
                        $zone_not_found_massage = '';
                    }

                    $database_total_cost = ($is_tax_applied) ? $selected_joey_plan_detail->total_cost : $selected_joey_plan_detail->sub_joey_charges;
                    $database_total_hours = $selected_joey_plan_detail->total_hours;
                    $database_hourly_rate = $selected_joey_plan_detail->hourly_rate;
                    $current_hours_work_in_seconds = TimeStringToSeconds($ActualDuration);
                    $plan_hours_work_in_seconds = TimeStringToSeconds($database_total_hours);
                    $rate_in_seconds = $database_total_cost /  $plan_hours_work_in_seconds;
                    $total_cost = $rate_in_seconds * $current_hours_work_in_seconds;
                    // checking the completed drops count are grater then zero so we can get the cost per drop
                    $cost_per_drop_on_hourly = ($TotalOrderDropsCompletedCount > 0) ? round($total_cost / $TotalOrderDropsCompletedCount,3 ) : 0;

                    //$total_cost = $selected_joey_plan_detail->total_cost ;
                    $payout = round($total_cost,3);
                    $hourly = round(HoursToMinutes($ActualDuration) / 60,3) ;
                    $truck_amount = $selected_joey_plan_detail->gas_truck_amount;

                    // calculating payout on total duration
                    $payout_with_total_duraion =  round($rate_in_seconds * TimeStringToSeconds($TotalDuration),2);
                    $payout_with_actual_duraion = $payout;

                    $payout_calculations= [
                        'Plan hourly rate = '.$database_hourly_rate.'<br>',
                        'Plan cost without tax= '.$selected_joey_plan_detail->sub_joey_charges.'<br>',
                        'Plan tax percentage = '.$selected_joey_plan_detail->tax.' % <br>',
                        'Plan total cost = '.$selected_joey_plan_detail->total_cost.'<br>',
                        'Plan total hours = '.$database_total_hours.'<br>',
                        'Working total hours = '.$hourly.'<br>',
                        'is applying tax ='.$is_tax_applied_string .'<br>',
                        'Working hours in seconds  = '.$current_hours_work_in_seconds.' X  rate in seconds by plane = '.$rate_in_seconds.' ['.$total_cost.'] <br>',
                        'Area Type   = ['.$selected_joey_plan_detail->plane_type.'] <br>',
                        'Error message  ='.$zone_not_found_massage,


                    ];

                    $payout_calculations = implode('',$payout_calculations);

                }
                elseif(in_array($joey_plan_data->plan_type,$JoeysPlanTypes['by_area_per_drop'])) // calculation for by duration plan
                {

                    // checking the plan type is set for any error occurred fallback on sub plan
                    // checking zone id if its exist then applied normal rates if it is not then applied custom route rate
                    $zone_not_found_massage = 'Zone not found applying sub plan ';
                    if(isset($zone_routing_data->zone_type) && isset($joey_plan_detail_data[$planTypeSelection[$route_type][$zone_routing_data->zone_type]]))
                    {

                        $selected_joey_plan_detail = $joey_plan_detail_data[$planTypeSelection[$route_type][$zone_routing_data->zone_type]];
                        $zone_not_found_massage = '';
                    }
                    else
                    {

                        $selected_joey_plan_detail = $joey_plan_detail_data[$planTypeSelection[$route_type][1]];
                    }

                    // getting data base charges
                    $database_total_cost = ($is_tax_applied) ? $selected_joey_plan_detail->total_cost : $selected_joey_plan_detail->sub_joey_charges;
                    $total_cost = $database_total_cost / $selected_joey_plan_detail->drops;
                    $payout = ($total_cost * $TotalOrderDropsCompletedCount);
                    $truck_amount = $selected_joey_plan_detail->gas_truck_amount;
                    $hourly = round( HoursToMinutes($ActualDuration) /60,2) ;

                    $payout_calculations= [
                        'Plan cost without tax= '.$selected_joey_plan_detail->sub_joey_charges.'<br>',
                        'Plan tax= '.$selected_joey_plan_detail->tax.' % <br>',
                        'Plan total cost with tax= '.$selected_joey_plan_detail->total_cost.'<br>',
                        'Plan No Drops = '.$selected_joey_plan_detail->drops.'<br>',
                        'is applying tax ='.$is_tax_applied_string .'<br>',
                        'Area Type   = '.$selected_joey_plan_detail->plane_type.' <br>',
                        'Calculation  = plan pre drop amount ('.$database_total_cost.' / '.$selected_joey_plan_detail->drops.')= '.$total_cost.' , joey completed drop = '.$TotalOrderDropsCompletedCount.' X '.$total_cost.' = ['.$payout.'] <br>',
                        'Error message  ='.$zone_not_found_massage.'<br>',
                        'Is This Custom Route  ='.$is_custom_route,


                    ];
                    $payout_calculations = implode('',$payout_calculations);


                }
                elseif(in_array($joey_plan_data->plan_type, $JoeysPlanTypes['bracket_plan'])) // calculation for bracket plan
                {
                    $plan_for_apply_range = null;
                    if($route_type == 'normal_route') // block for bracket priceing
                    {
                        $plan_for_apply_range = $joey_plan_data->PlanDetails
                            ->where('max_range_drops','>=',$TotalOrderDropsCompletedCount)
                            ->where('plane_type','==','bracket_pricing|per_drop|custom_route|big_box')
                            ->sortBy('max_range_drops')
                            ->first();
                    }
                    elseif($route_type == 'custom_route')
                    {
                        $plan_for_apply_range = $joey_plan_data->PlanDetails
                            ->where('plane_type','==','bracket_pricing_per_drop_custom_route')
                            ->sortByDesc('id')
                            ->first();
                    }
                    elseif($route_type == 'big_box_route')
                    {
                        $plan_for_apply_range = $joey_plan_data->PlanDetails
                            ->where('plane_type','==','bracket_pricing_per_drop_big_box')
                            ->sortByDesc('id')
                            ->first();
                    }

                    if($plan_for_apply_range == null) // fallback if the completed drops  is grater then maximum range
                    {
                        $plan_for_apply_range = $joey_plan_data->PlanDetails
                            ->sortByDesc('max_range_drops')->first();
                    }



                    if($joey_plan_data->plan_type == $JoeysPlanTypes['bracket_plan'][0]) // calculation for range per drop plan
                    {
                        $tax_amount_per_drop = $plan_for_apply_range->tax_charges / $plan_for_apply_range->min_range_drops  ;
                        $total_cost = ($is_tax_applied) ?  $plan_for_apply_range->amount + $tax_amount_per_drop : $plan_for_apply_range->amount;
                        $payout = ($total_cost * $TotalOrderDropsCompletedCount );
                        $truck_amount = $plan_for_apply_range->gas_truck_amount;
                        $hourly =round( HoursToMinutes($ActualDuration) / 60 , 2) ;
                        $payout_calculations='Get range amount per drop , total cost['.$total_cost.'] x total compeleted drops['.$TotalOrderDropsCompletedCount.'] , </br> range apply min='.$plan_for_apply_range->min_range_drops.'max='.$plan_for_apply_range->max_range_drops.' <br> Is This Custom Route  ='.$is_custom_route.'<br> is applying tax ='.$is_tax_applied_string .'<br>';
                    }
                    elseif($joey_plan_data->plan_type == $JoeysPlanTypes['bracket_plan'][1]) // calculation for range hourly plan
                    {
                        $database_total_cost = ($is_tax_applied) ? $plan_for_apply_range->total_cost : $plan_for_apply_range->sub_joey_charges;
                        $database_total_hours = $plan_for_apply_range->total_hours;
                        $database_hourly_rate = $plan_for_apply_range->hourly_rate;
                        $current_hours_work_in_seconds = TimeStringToSeconds($ActualDuration);
                        $plan_hours_work_in_seconds = TimeStringToSeconds($database_total_hours);
                        $rate_in_seconds = $database_total_cost /  $plan_hours_work_in_seconds;
                        $total_cost = $rate_in_seconds * $current_hours_work_in_seconds;
                        // checking the completed drops count are grater then zero so we can get the cost per drop
                        $cost_per_drop_on_hourly = ($TotalOrderDropsCompletedCount > 0) ? round($total_cost / $TotalOrderDropsCompletedCount,2 ): 0;



                        $payout = round( $total_cost , 2);
                        $truck_amount = $plan_for_apply_range->gas_truck_amount;
                        $hourly =round( HoursToMinutes($ActualDuration) / 60,2) ;
                        $payout_calculations='Get range amount Hourly rate ,</br>
                                        original cost by '.$database_total_hours.' hours = ['.$database_total_cost.'] ,</br>
                                        total working hours['.$ActualDuration.'] ,</br>
                                        total working hours['.$ActualDuration.'] ,</br>
                                        is applying tax ['.$is_tax_applied_string.'] ,</br>
                                        range apply min='.$plan_for_apply_range->min_range_drops.'max='.$plan_for_apply_range->max_range_drops.'
                                        calculation formula convert total hours cost in to seconds ( (total plan cost['.$database_total_cost.'] / plan hours_into_seconds['.$plan_hours_work_in_seconds.']) x total_working_hours_in_seconds['.$current_hours_work_in_seconds.' )
                                        ';

                        // calculating payout on total duration
                        $payout_with_total_duraion =  round($rate_in_seconds * TimeStringToSeconds($TotalDuration),2);
                        $payout_with_actual_duraion = $payout;

                    }
                    
                }
				elseif(in_array($joey_plan_data->plan_type, $JoeysPlanTypes['dynamic_section']))
				{
					$plan_for_apply = null;
					// getting  route zone id
					$route_zone = $single_data->JoeyRoute->zone;

					if($route_type == 'normal_route') // block for bracket priceing
					{
						$plan_for_apply = $joey_plan_data->PlanDetails()
							->whereHas('JoeyPlanGroupZones.ZonesRouting', function($query) use ($route_zone) {
								$query->where('zones_routing.id', $route_zone);
							})->first();
					}
					elseif($route_type == 'custom_route')
					{
						$plan_for_apply = $joey_plan_data->PlanDetails
							->where('section_name','==','custom_route_dynamic_section')
							->first();
					}
					elseif($route_type == 'big_box_route')
					{
						$plan_for_apply = $joey_plan_data->PlanDetails
							->where('section_name','==','big_box_dynamic_section')
							->first();
					}

					if($plan_for_apply == null) // fallback if the completed drops  is grater then maximum range
					{
						$plan_for_apply = $joey_plan_data->PlanDetails
							->where('section_name','==','dynamic_section')
							->sortBy('total_cost')
							->first();
					}


					$total_cost = ($is_tax_applied) ? $plan_for_apply->total_cost : $plan_for_apply->sub_joey_charges;
					$total_cost = $total_cost / $plan_for_apply->drops;
					$payout = ($total_cost * $TotalOrderDropsCompletedCount);
					$truck_amount = $plan_for_apply->gas_truck_amount;
					$hourly = round( HoursToMinutes($ActualDuration) / 60,2) ;
					$payout_calculations='total cost['.$total_cost.'] x total compeleted drops['.$TotalOrderDropsCompletedCount.']';
					$payout_calculations.='<br>  Is This Custom Route = '.$is_custom_route;
					$payout_calculations.='<br>  is applying tax = '.$is_tax_applied_string;
					$payout_calculations.='<br>  Zone Name = '.$zone_name;


				}
		}


            // flag deduction and bonus calculation after payout calculation
            $flag_data = $single_data->FlagDataByJoey();

            if($flag_data != null)
            {
                // looping on the flag data
                foreach($flag_data as $flag_data)
                {
                    $applied_values =  json_decode($flag_data->finance_incident_value_applied,true);
                    if($applied_values['operator'] == "+")
                    {
                        $flag_bonus+= $applied_values['value'];
                    }
                    elseif($applied_values['operator'] == "-")
                    {
                        $flag_deduction+= $applied_values['value'];
                    }
                }

            }

            // flag deduction and bonus calculation on route
            $flag_route_value_applied = $single_data->FlagDataByRoute;
            if(!is_null($flag_route_value_applied))
            {
                $flag_bonus_by_route =
                $applied_values =  json_decode($flag_route_value_applied->finance_incident_value_applied,true);
                if($applied_values['operator'] == "+")
                {
                    $flag_bonus+= $applied_values['value'];
                }
                elseif($applied_values['operator'] == "-")
                {
                    $flag_deduction+= $applied_values['value'];
                }
                //dd($flag_deduction,$single_data);
            }

            // calculating payout after flag bonus and deduction
            $payout+=($flag_bonus - $flag_deduction);




            // setting city name
            $vendor_id_test = (isset($single_data->vendor()->id))? $single_data->vendor()->id :'';

            if(in_array($vendor_id_test, $city_names['montreal']))
            {
                $city_name = 'Montreal';
            }
            elseif(in_array($vendor_id_test,$city_names['ottawa']))
            {
                $city_name = 'Ottawa';
            }
            elseif( in_array($vendor_id_test,$city_names['toronto']) )
            {
                $city_name = 'Toronto';
            }


            $return_data[$index++]=[
                'number' => $iteration++,
                'route_id'=> $single_data->route_id,
                'joey_id'=>$single_data->Joey->id ?? 'not set yet' ,
                'joey_full_name'=>$single_data->joey->full_name ?? 'not set yet' ,
                'brooker_name'=>$brooker_name,
                'zone'=> (isset($single_data->Locations()->City->name))? $single_data->Locations()->City->name : $city_name,
                'city'=> $city_name,
                'vendor_id'=>$single_data->vendor()->id ?? 'Not set yet',
                'vendor_full_name'=>$single_data->vendor()->full_name ?? 'Not set yet',
                'total_order_drops_count'=>$TotalOrderDropsCount,
                'total_order_drops_completed_count'=>$TotalOrderDropsCompletedCount,
                'total_order_picked_count'=>$TotalOrderPickedCount,
                'total_order_unattempted_count'=>$TotalOrderUnattemptedCount,
                'total_order_return_count'=>$TotalOrderReturnCount,
                'plan_name'=>$plan_name,
                'plan_internal_name'=>$plan_internal_name,
//                'first_drop_scan'=> (!empty($FirstDropScan)) ? $FirstDropScan->toDateTimeString() : '',
//                'first_pick_up_scan'=> (!empty($FirstPickUpScan)) ? $FirstPickUpScan->toDateTimeString() : '',
//                'last_drop_scan'=> (!empty($LastDropScan)) ? $LastDropScan->toDateTimeString() : '',
                'first_drop_scan'=> $FirstDropScan,
                'first_pick_up_scan'=> $FirstPickUpScan,
                'last_drop_scan'=> $LastDropScan,
                'total_km'=>$TotalKM.' Km',
                'actual_total_km'=>$ActualTotalKM.' Km',
                'total_duration'=>$TotalDuration,
                'actual_duration'=>$ActualDuration,
                'gas_amount'=>$gas_amount,
                'truck_amount'=>$truck_amount,
                'tech_amount'=>$tech_amount,
                'hourly'=>$hourly,
                'flag_bonus'=>$flag_bonus.' $',
                'flag_deduction'=> $flag_deduction.'$',
                'payout'=>$payout.' $',
                'payout_with_actual_duraion'=>$payout_with_actual_duraion.' $',
                'payout_with_total_duraion'=>$payout_with_total_duraion.' $',
                'cost_per_drop_on_hourly'=>$cost_per_drop_on_hourly.' $',
                'route_type'=>str_replace("_"," ",$route_type),
                'payout_calculations' => $payout_calculations,
            ];


        }


        // returning data
        return $return_data;
    }
}