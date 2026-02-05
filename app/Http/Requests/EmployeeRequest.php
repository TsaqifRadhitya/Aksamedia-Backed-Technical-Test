<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EmployeeRequest",
    title: "Employee Request",
    description: "Format input untuk create/update pegawai (Multipart/Form-Data)",
    properties: [
        new OA\Property(
            property: "image",
            description: "File foto pegawai (jpg, png, max 2MB)",
            type: "string",
            format: "binary"
        ),
        new OA\Property(
            property: "name",
            description: "Nama Lengkap Pegawai",
            type: "string",
            example: "Budi Santoso"
        ),
        new OA\Property(
            property: "phone",
            description: "Nomor Telepon (Unik)",
            type: "string",
            example: "081234567890"
        ),
        new OA\Property(
            property: "division",
            description: "UUID Divisi",
            type: "string",
            format: "uuid",
            example: "98a7s6d5-4f3g-2h1j-..."
        ),
        new OA\Property(
            property: "position",
            description: "Jabatan",
            type: "string",
            example: "Staff IT"
        ),
        new OA\Property(
            property: "_method",
            description: "Wajib diisi 'PUT' hanya saat update data dengan file gambar",
            type: "string",
            example: "PUT"
        )
    ]
)]
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
