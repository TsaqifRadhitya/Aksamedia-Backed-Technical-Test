<?

Class ApiRespone {
    public static function success($status = 200,$data = null,$message = "Ok"){
        $response = [
            'status' => $status,
            'message' => $message
        ];
        return response()->json($data ? [...$response,'data' => $data] : $response,$status);
    }

    public static function error($status = 500,$error = null,$message = "Internal Server Error"){
        $response = [
            'status' => $status,
            'message' => $message
        ];
        return response()->json($error ? [...$response,'error' => $error] : $response,$status);
    }
}
