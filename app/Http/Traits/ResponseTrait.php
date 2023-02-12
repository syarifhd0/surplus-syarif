<?php

namespace App\Http\Traits;

trait ResponseTrait
{
    /**
     * @param $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($msg, $code = 400, $data = [])
    {
        return response()->json([
            'error' => [
                'message'     => $msg,
                'status' => $code,
                'data'        => $data
            ]
        ], $code);
    }
    
    /**
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code = 200)
    {
        return response()->json([
            'success' => [
                'message'     => $code . ' OK',
                'status' => $code,
                'data'        => $data
            ]
        ], $code);
    }
}