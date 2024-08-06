<?php

namespace App\Http\Requests;

use App\Http\Utils\BasicUtil;
use App\Models\Department;
use App\Models\User;
use App\Models\UserNote;
use App\Rules\ValidateUser;
use Illuminate\Foundation\Http\FormRequest;

class UserNoteUpdateByBusinessOwnerRequest extends BaseFormRequest
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
                    $exists = UserNote::where('id', $value)
                        ->where('user_notes.user_id', '=', $this->user_id)
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
            'title' => 'required|string',
            'description' => 'required|string',
            // 'hidden_note' => 'present|string',

            'created_at' => 'required|date',
            'updated_at' => 'required|date',

        ];
    }
}
