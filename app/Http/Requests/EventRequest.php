<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EventRequest extends FormRequest
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
            'title'           => 'required|max:255',
            'description'     => 'string',
            'start_date_time' => 'required|date_format:' . DATE_RFC3339,
            'end_date_time'   => 'required|date_format:' . DATE_RFC3339 . '|after:start_date_time',
            'organizers'      => [
                'required', 'array', 'min:1',
                function ($attribute, $value, $fail) {
                    foreach ($value as $email) {
                        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("The organizer '{$email}' is not valid e-mail.");
                            break;
                        }
                    }
                }
            ]
        ];
    }
}
