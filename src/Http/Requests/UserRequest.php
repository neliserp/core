<?php

namespace Neliserp\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $id = request()->route('user');

        return [
            'username' => [
                'required',
                Rule::unique('users')->ignore($id ? $id : null),
            ],
            'password' => request()->method() == 'POST' ? 'required' : 'nullable',
            'is_active' => 'required',
            'email' => 'nullable',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'position' => 'nullable',
            'mobile' => 'nullable',
            'code' => 'nullable',
        ];
    }
}
