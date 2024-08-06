<?php

namespace App\Http\Requests;

use App\Http\Utils\BasicUtil;
use App\Models\Department;
use App\Models\EmployeeSponsorshipHistory;
use App\Models\User;
use App\Rules\ValidateUser;
use Illuminate\Foundation\Http\FormRequest;

class UserSponsorshipHistoryUpdateRequest extends BaseFormRequest
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

                    $exists = EmployeeSponsorshipHistory::where('id', $value)
                        ->where('employee_sponsorship_histories.user_id', '=', $this->user_id)
                        // ->where('employee_sponsorship_histories.is_manual', '=', 1)
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



            "date_assigned" => 'required|date',
            "expiry_date" => 'required|date',
            // "status" => 'required|in:pending,approved,denied,visa_granted',
            "note" => 'required|string',
            "certificate_number" => 'required|string',
            "current_certificate_status" => 'required|in:unassigned,assigned,visa_applied,visa_rejected,visa_grantes,withdrawal',
            "is_sponsorship_withdrawn" => 'required|boolean',


            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ];
    }
}
