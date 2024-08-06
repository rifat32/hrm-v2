<?php

namespace App\Http\Requests;

use App\Http\Utils\BasicUtil;
use App\Models\Department;
use App\Models\EmployeeRightToWorkHistory;
use App\Models\User;
use App\Rules\ValidateUser;
use Illuminate\Foundation\Http\FormRequest;

class UserRightToWorkHistoryUpdateRequest extends BaseFormRequest
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
                    $exists = EmployeeRightToWorkHistory::where('id', $value)
                        ->where('employee_right_to_work_histories.user_id', '=', $this->user_id)
                        // ->where('employee_right_to_work_histories.is_manual', '=', 1)
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


            'right_to_work_code' => 'required|string',
            'right_to_work_check_date' => 'required|date',
            'right_to_work_expiry_date' => 'required|date',

            'right_to_work_docs' => 'required|array',
            'right_to_work_docs.*.file_name' => 'required|string',
            'right_to_work_docs.*.description' => 'required|string',


        ];
    }
}
