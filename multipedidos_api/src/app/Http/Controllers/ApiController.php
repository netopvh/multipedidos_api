<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function respondWithCreated($data, $message = 'Created', $status = Response::HTTP_CREATED)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function respondWithSuccess($data, $message = 'Success', $status = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function respondWithNoContent($message = 'No Content', $status = Response::HTTP_NO_CONTENT)
    {
        return response()->json([
            'message' => $message
        ], $status);
    }

    public function respondWithError($message = 'Error', $status = Response::HTTP_BAD_REQUEST, $errors = [])
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
