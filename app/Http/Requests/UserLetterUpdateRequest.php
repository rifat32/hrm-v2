<?php




namespace App\Http\Requests;

use App\Models\UserLetter;
use App\Rules\ValidateUser;
use App\Rules\ValidateUserLetterName;
use Illuminate\Foundation\Http\FormRequest;

class UserLetterUpdateRequest extends BaseFormRequest
{
/**
* Determine if the user is authorized to make this request.
*
* @return  bool
*/
public function authorize()
{
return true;
}

/**
* Get the validation rules that apply to the request.
*
* @return  array
*/
public function rules()
{
    $all_manager_department_ids = $this->get_all_departments_of_manager();


$rules = [
    'id' => [
        'required',
        'numeric',
        function ($attribute, $value, $fail) {
            $exists = UserLetter::where('id', $value)
                ->where('user_letters.user_id', '=', $this->user_id)
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
    'issue_date' => [
    'required',
    'string',
],

    'status' => [
    'required',
    'string',
],

    'letter_content' => [
    'required',
    'string',
],

    'sign_required' => [
    'required',
    'boolean',
],

    'attachments' => [
    'present',
    'array',
],









];



return $rules;
}
}



