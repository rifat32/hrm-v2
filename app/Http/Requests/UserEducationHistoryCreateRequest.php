<?php

namespace App\Http\Requests;

use App\Http\Utils\BasicUtil;
use App\Rules\ValidateUser;


class UserEducationHistoryCreateRequest extends BaseFormRequest
{
    use BasicUtil;
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
        $all_manager_department_ids = $this->get_all_departments_of_manager();
        return [
            'user_id' => [
                'required',
                'numeric',
                new ValidateUser($all_manager_department_ids)
            ],
            'degree' => 'required|string',
            'major' => 'required|string',
            'school_name' => 'required|string',
            'start_date' => 'required|date',
            'graduation_date' => 'required|date|after_or_equal:start_date',

            'achievements' => 'nullable|string',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
            'is_current' => 'required|boolean',



            'attachments' => 'present|array',
            'attachments.*' => 'string',
        ];
    }
}
