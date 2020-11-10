<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class RegisterRequest extends FormRequest
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
        return [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'birthdate' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'country_code' => 'required|numeric',
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function withValidator($validator)
    {
        $email = $this->email;
        $phone = $this->phone;
        $country_code = $this->country_code;
        
        if($email) {
            $validator->after(function ($validator) use ($email) {
                try{
                    $auth = app('firebase.auth');
                    $auth->getUserByEmail($email);
                    $validator->errors()->add('email', 'The Email has already been taken.');
                } catch(UserNotFound $e) {
                }
            });
        }

        if($phone && $country_code) {
            $validator->after(function ($validator) use ($phone, $country_code) {
                try{
                    $phone = ($country_code[0] == '+' ? $country_code : "+".$country_code).$phone;
                    $auth = app('firebase.auth');
                    $auth->getUserByPhoneNumber($phone);
                    $validator->errors()->add('phone', 'The phone has already been taken.');
                } catch(UserNotFound $e) {
                }
            });
        }

    }
}
