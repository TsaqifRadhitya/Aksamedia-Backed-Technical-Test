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
                                            ['artistic' => 5],
                                            ['conventional' => 4],
                                            ['enterprising' => 2],
                                            ['investigative' => 3],
                                            ['realistic' => 5],
                                            ['social' => 1]
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
        $list_siswa = collect(DB::select('select nisn,nama from nilai group by nisn, nama'));
        $data = [];
        foreach($list_siswa as $siswa){
            $siswaScore = collect(DB::select('select nama_pelajaran, skor from nilai where nisn = '.$siswa->nisn." and materi_uji_id = 7 and nama_pelajaran != 'PELAJARAN KHUSUS' order by nama_pelajaran"));
            $nilaiRt = [];
            foreach($siswaScore as $Score){
                $nilaiRt = [...$nilaiRt,[Str::lower($Score->nama_pelajaran) => (int)$Score->skor]];
            }
            $data = [...$data,[
                'nama' => $siswa->nama,
                'nilaiRt' => $nilaiRt,
                'nisn' => $siswa->nisn
            ]];
        }
        return ApiResponse::success($data);
    }

    public function nilaiST(){
        $list_siswa = collect(DB::select('select nisn,nama from nilai group by nisn, nama'));
        $data = [];
        foreach($list_siswa as $siswa){
            $siswaScore = collect(DB::select('select nama_pelajaran, skor from nilai where nisn = '.$siswa->nisn." and materi_uji_id = 4 order by nama_pelajaran"));
            $nilaiRt = [];
            foreach($siswaScore as $Score){
                $nilaiRt = [...$nilaiRt,[Str::lower($Score->nama_pelajaran) => $Score->skor]];
            }
            $data = [...$data,[
                'nama' => $siswa->nama,
                'nilaiRt' => $nilaiRt,
                'nisn' => $siswa->nisn
            ]];
        }
        return ApiResponse::success($data);
        return ApiResponse::success([]);
    }
}
