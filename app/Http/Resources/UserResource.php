<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UserResource",
    title: "User Resource",
    description: "Schema data user/admin",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid", example: "98a7s6d5-...", description: "ID User"),
        new OA\Property(property: "name", type: "string", example: "Administrator", description: "Nama Lengkap"),
        new OA\Property(property: "username", type: "string", example: "admin", description: "Username Login"),
        new OA\Property(property: "phone", type: "string", example: "08123456789", description: "Nomor Telepon"),
        new OA\Property(property: "email", type: "string", format: "email", example: "admin@aksamedia.com", description: "Email User")
    ]
)]
class UserResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}
