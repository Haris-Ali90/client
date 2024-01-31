<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class SignupRequest extends Request {

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
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name field is required',
            'last_name.required' => 'Last name field is required',
            'email.required' => 'Email field is required',
            'phone.required' => 'Phone number field is required',
//            'phone.min' => 'Invalid Format',
            'password.required' => 'Password field is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'phone' => 'required|max:15|min:12',
            'email' => 'required|email|max:100|unique:joeys,email,NULL,id,deleted_at,NULL',
            'password' => 'min:8|required|max:40',
        ];

    }
}