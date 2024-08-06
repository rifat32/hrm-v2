<?php



namespace App\Http\Requests;


use App\Rules\ValidateLetterTemplateName;
use Illuminate\Foundation\Http\FormRequest;

class LetterTemplateCreateRequest extends BaseFormRequest
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

        $rules = [
            'name' => [
                "required",
                'string',
                new ValidateLetterTemplateName(NULL)
            ],
            'description' => 'nullable|string',
            'template' => 'required|string',
        ];



        return $rules;
    }
}


