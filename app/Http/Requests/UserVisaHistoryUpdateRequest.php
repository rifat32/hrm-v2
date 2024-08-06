<?php

namespace App\Http\Requests;

use App\Http\Utils\BasicUtil;
use App\Models\Department;
use App\Models\EmployeeVisaDetailHistory;
use App\Models\User;
use App\Rules\ValidateUser;
use Illuminate\Foundation\Http\FormRequest;

class UserVisaHistoryUpdateRequest extends BaseFormRequest
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
            'id' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $exists = EmployeeVisaDetailHistory::where('id', $value)
                        ->where('employee_visa_detail_histories.user_id', '=', $this->user_id)
                        // ->where('employee_visa_detail_histories.is_manual', '=', 1)
                        ->exists();

                    if (!$exists) {
                        $fail($attribute . " is invalid.");
                    }
                },
            ],
            'user_id' => [
                'required',
                'numeric',
                new ValidateUser($all_manager_department_ids)
            ],
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',


            'BRP_number' => 'required|string',
            'visa_issue_date' => 'required|date',
            'visa_expiry_date' => 'required|date',
            'place_of_issue' => 'required|string',
            'visa_docs' => 'required|array',
            'visa_docs.*.file_name' => 'required|string',
            'visa_docs.*.description' => 'required|string',

        ];
    }
}
