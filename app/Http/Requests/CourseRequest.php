<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'crn' => 'required',
            'subject' => 'required',
            'name' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $crn = $this->crn;
        $validator->after(function ($validator) use ($crn) {
            
            $firestore = app("firebase.firestore");
            $collection = $firestore->database()->collection('courses');
            $result = $collection->where("crn", "=", $crn)->documents();
            
            if($result->isEmpty() == false) {
                $validator->errors()->add('crn', 'The crn has already been taken.');
            }
        });
    }
}
