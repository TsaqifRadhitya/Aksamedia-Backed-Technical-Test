<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = $this->route()->getName();
        if ($routeName === "employees.store") {
            return [
                'image' => ['required','file','mimes:png,jpg','max:2048'],
                'name' => ['required'],
                'phone' => ['required','unique:employees,phone'],
                'division' => ['required','uuid',Rule::exists('divisions','id')],
                'position' => ['required']
            ];
        }

        if ($routeName === "employees.update") {
            return [
                'image' => ['sometimes','file','mimes:png,jpg','max:2048'],
                'name' => ['required'],
                'phone' => [
                    'required',
                    Rule::unique('employees','phone')->ignore($this->route('employee'))
                ],
                'division' => ['required','uuid',Rule::exists('divisions','id')],
                'position' => ['required']
            ];
        }

        return [
            //
        ];
    }
}
