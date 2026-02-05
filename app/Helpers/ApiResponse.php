<?php

namespace App\Helpers;

Class ApiResponse {
    public static function success($data = null,$pagination = null,$message = "Ok",$status = 200){
        $response = [
            'status' => $status,
            'message' => $message
        ];
        if($data){
            $response = [...$response,'data' => $data];
        }

        if($pagination){
            $response = [...$response,'pagination' => $pagination];
        }

        return response()->json($response,$status);
    }

    public static function error($error = null,$message = "Internal Server Error",$status = 500){
        $response = [
            'status' => $status,
            'message' => $message
        ];
        return response()->json($error ? [...$response,'error' => $error] : $response,$status);
    }
}
