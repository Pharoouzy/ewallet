<?php

/**
 * @param $message
 * @param array $data
 * @param int $status
 * @return \Illuminate\Http\JsonResponse
 */
function successResponse($message, $data = [], $status = 200) {

    if(empty($data)){
        return response()->json([
            'status' => true,
            'message' => $message
        ], $status);
    }

    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data
    ], $status);

}

/**
 * @param $message
 * @param array $data
 * @param int $status
 * @return \Illuminate\Http\JsonResponse
 */
function errorResponse($message, $data = [], $status = 400) {

    if(empty($data)){
        return response()->json([
            'status' => false,
            'message' => $message
        ], $status);
    }

    return response()->json([
        'status' => false,
        'message' => $message,
        'errors' => $data
    ], $status);

}


