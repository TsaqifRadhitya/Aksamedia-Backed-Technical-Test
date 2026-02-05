<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EmployeeResource",
    title: "Employee Resource",
    description: "Schema output data pegawai",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid", example: "uuid-1234"),
        new OA\Property(property: "image", type: "string", example: "http://domain.com/storage/uploads/foto.jpg"),
        new OA\Property(property: "name", type: "string", example: "Budi Santoso"),
        new OA\Property(property: "phone", type: "string", example: "08123456789"),
        new OA\Property(property: "division", ref: "#/components/schemas/DivisionResource"),
        new OA\Property(property: "position", type: "string", example: "Staff IT")
    ]
)]
class employee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image'=> $this->image,
            'name' => $this->name,
            'phone' => $this->phone,
            'division' =>  new division($this->division),
            'position' => $this->position
        ];
    }
}
