<?php

namespace App;

trait ResponseApi
{
    /**
     * Response api template convention
     * @param bool $statusMessage
     * @param mixed $message
     * @param mixed $data
     * @param mixed $statusCode
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function returnResponseApi(bool $statusMessage = true, ?string $message = null, $data = null, ?int $statusCode = null)
    {
        return response()->json([
            'status' => $statusMessage,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
