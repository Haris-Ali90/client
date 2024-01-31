<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;
//use App\Rules\CheckBetweenDateRange;

class JoeyPayoutReportRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->all();

        if (count($request) > 0){
            $after_or_equal_to = $yesterday = date('Y-m-d', strtotime($request['start_date'] .'-1 days'));
            return [
                'joye_name' => 'required_without_all:joye_id,vendor_id,city,plan',
                'joye_id' => 'required_without_all:joye_name,vendor_id,city,plan',
                'vendor_id' => 'required_without_all:joye_id,joye_name,city,plan',
                'city' => 'required_without_all:joye_id,joye_name,vendor_id,plan',
                'plan' => 'required_without_all:joye_id,joye_name,vendor_id,city',
                'start_date' => 'required|date|after:2021-03-06',
                //'end_date' => ['required','date','after:start_date', $after_or_equal_to],
                'end_date' => ['required','after:'.$after_or_equal_to.''],
            ];
       }
       else
       {
               return [];
       }
    }
}
