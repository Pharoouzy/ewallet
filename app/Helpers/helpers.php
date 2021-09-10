<?php

function successResponse($message, $data = [], $status = 200) {

    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data
    ], $status);

}

function errorResponse($message, $data = [], $status = 400) {

    return response()->json([
        'status' => false,
        'message' => $message,
        'errors' => $data
    ], $status);

}


