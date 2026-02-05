<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class division extends JsonResource
{

    #[OA\Schema(
    schema: "DivisionResource",
    title: "Division Resource",
    description: "Schema data untuk object Divisi",
    properties: [
        new OA\Property(
            property: "id",
            type: "string",
            format: "uuid",
            example: "98a7s6d5-4f3g-2h1j-...",
            description: "ID Divisi"
        ),
        new OA\Property(
            property: "name",
            type: "string",
            example: "Mobile Development",
            description: "Nama Divisi"
        )
    ]
    )]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
