<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => ['required'],
            'user_type_id'   => [
                'required',
                Rule::in([1, 2, 3, 4])
            ]
        ];
    }

    public function validated()
    {
        return array_merge(
            $this->validator->validated(),
            ['updated_by' => auth()->id()]
        );
    }
}
