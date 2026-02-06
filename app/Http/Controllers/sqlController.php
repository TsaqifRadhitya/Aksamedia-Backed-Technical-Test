<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use DB;
use Str;
use OpenApi\Attributes as OA;

class sqlController extends Controller
{

    #[OA\Get(
    path: "/api/nilaiRT",
    summary: "Get Nilai RT Siswa",
    description: "Mengambil daftar nilai RT seluruh siswa berdasarkan materi_uji_id = 7",
    tags: ["Nilai"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Success",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 200
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "ok"
                    ),
                    new OA\Property(
                        property: "data",
                        type: "array",
                        items: new OA\Items(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "nama",
                                    type: "string",
                                    example: "Ahmad Fadlan"
                                ),
                                new OA\Property(
                                    property: "nisn",
                                    type: "string",
                                    example: "3097012709"
                                ),
                                new OA\Property(
                                    property: "nilaiRt",
                                    type: "array",
                                    items: new OA\Items(
                                        type: "object",
                                        example: [
                                            'artistic' => 5,
                                            'conventional' => 4,
                                            'enterprising' => 2,
                                            'investigative' => 3,
                                            'realistic' => 5,
                                            'social' => 1
                                        ]
                                    )
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function nilaiRT(){
        $rows = DB::select("
            SELECT nisn, nama, nama_pelajaran, skor
            FROM nilai
            WHERE materi_uji_id = 7
            AND nama_pelajaran != 'PELAJARAN KHUSUS'
            ORDER BY nama, nama_pelajaran");

        $grouped = collect($rows)->groupBy('nisn');

        $data = $grouped->map(function ($items) {

        $first = $items->first();

        $nilaiRt = $items->mapWithKeys(function ($item) {
            return [
                Str::lower($item->nama_pelajaran) => (int) $item->skor
            ];
        });

        return [
            'nama' => $first->nama,
            'nilaiRt' => $nilaiRt,
            'nisn' => $first->nisn,
            ];
        })->values();
        return ApiResponse::success($data);
    }

    #[OA\Get(
    path: "/api/nilaiST",
    summary: "Get Nilai ST Siswa",
    description: "Mengambil daftar nilai ST seluruh siswa berdasarkan materi_uji_id = 4, dihitung berdasarkan bobot masing-masing pelajaran dan diurutkan dari total terbesar",
    tags: ["Nilai"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Success",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "integer",
                        example: 200
                    ),
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "ok"
                    ),
                    new OA\Property(
                        property: "data",
                        type: "array",
                        items: new OA\Items(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "nama",
                                    type: "string",
                                    example: "Muhammad Sanusi"
                                ),
                                new OA\Property(
                                    property: "nisn",
                                    type: "string",
                                    example: "0094494403"
                                ),
                                new OA\Property(
                                    property: "listNilai",
                                    type: "object",
                                    properties: [
                                        new OA\Property(
                                            property: "verbal",
                                            type: "number",
                                            format: "float",
                                            example: 208.35
                                        ),
                                        new OA\Property(
                                            property: "kuantitatif",
                                            type: "number",
                                            format: "float",
                                            example: 89.01
                                        ),
                                        new OA\Property(
                                            property: "penalaran",
                                            type: "number",
                                            format: "float",
                                            example: 200
                                        ),
                                        new OA\Property(
                                            property: "figural",
                                            type: "number",
                                            format: "float",
                                            example: 142.86
                                        )
                                    ]
                                ),
                                new OA\Property(
                                    property: "total",
                                    type: "number",
                                    format: "float",
                                    example: 640.22
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function nilaiST(){
        $data = DB::select("
            SELECT
                nisn,
                nama,
                SUM(
                    CASE
                        WHEN pelajaran_id = 44 THEN skor * 41.67
                        WHEN pelajaran_id = 45 THEN skor * 29.67
                        WHEN pelajaran_id = 46 THEN skor * 100
                        WHEN pelajaran_id = 47 THEN skor * 23.81
                        ELSE 0
                    END
                ) as total,
                SUM(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) as verbal,
                SUM(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) as kuantitatif,
                SUM(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) as penalaran,
                SUM(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) as figural
            FROM nilai
            WHERE materi_uji_id = 4
            GROUP BY nisn, nama
            ORDER BY total DESC
        ");

        $mappedData = collect($data)->map(function($dm){
            return [
                'listNilai' => [
                    'figural' => (float) $dm->figural,
                    'kuantitatif' => (float) $dm->kuantitatif,
                    'penalaran' => (float) $dm->penalaran,
                    'verbal' => (float) $dm->verbal
                ],
                'nama' => $dm->nama,
                'nisn' => $dm->nisn,
                'total' => (float) $dm->total
            ];
        });

        return ApiResponse::success($mappedData);
    }
}
