<?php

namespace App\Trait;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ResponseApi
{
    /**
     * Response api template convention
     *
     * @param  mixed  $message
     * @param  mixed  $data
     * @param  mixed  $statusCode
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function returnResponseApi(bool $statusMessage = true, ?string $message = null, $data = null, ?int $statusCode = null)
    {
        if ($statusMessage == false) {
            return throw new HttpResponseException(
                response()->json([
                    'status' => $statusMessage,
                    'message' => $message,
                    'data' => $data,
                ], $statusCode)
            );
        } elseif ($statusMessage == true) {
            return response()->json([
                'status' => $statusMessage,
                'message' => $message,
                'data' => $data,
            ], $statusCode);
        } else {
            return throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Method Parameter Violation, Input Parameter Must Be Follow the rules',
                ], 403)
            );
        }
    }
}
