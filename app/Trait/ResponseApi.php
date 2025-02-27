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
     * @param  mixed  $addheader  for additional key and value
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function returnResponseApi(bool $statusMessage = true, ?string $message = null, $data = null, ?int $statusCode = null, ?array $addheader = null)
    {
        $response = [
            'status' => $statusMessage,
            $statusMessage == false ? 'error' : 'message' => $message,
            'data' => $data,
        ];

        if ($data == null | $data == '') {
            unset($response['data']);
        }

        if ($addheader !== null) {
            $response = array_merge($response, $addheader);
        }

        if ($statusMessage == false) {
            return throw new HttpResponseException(
                response()->json($response, $statusCode)
            );
        } elseif ($statusMessage == true) {
            return response()->json($response, $statusCode);
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
