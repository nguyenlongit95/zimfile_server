<?php

namespace App\Helpers;

class ResponseHelper
{
    const TXT_TIME_OUT = 'Server error time out has data: ';
    const INTERNAL_SERVER_ERROR = 'Internal server error';

    /**
     * return success json, add payload
     *
     * @param int $statusCode
     * @param null $data
     * @param $message
     * @return JsonResponse
     */
    public function success($data = null, $statusCode = 200, $message = 'success')
    {
        return response()->json([
            'message' => $message,
            'code' => $statusCode,
            'data' => $data,
        ], 200);
    }

    /**
     * Function define bad request
     *
     * @return JsonResponse
     */
    public function badRequest()
    {
        return response()->json([
            'message' => 'Bad Request',
            'code' => 400,
            $data = null,
        ], 400);
    }

    /**
     * unauthenticated error
     *
     * @return JsonResponse
     */
    public function unAuthenticated()
    {
        return response()->json([
            'message' => 'Unauthorized',
            'code' => 401,
            'data' => null,
        ], 401);
    }

    /**
     * not found
     *
     * @param string $message
     * @return JsonResponse
     */
    public function notFound($message)
    {
        return response()->json([
            'message' => $message,
            'code' => 404,
            'data' => null,
        ], 404);
    }

    /**
     * Errors validation
     *
     * @param $message
     * @return JsonResponse
     */
    public function validation($message)
    {
        return response()->json([
            'message' => $message,
            'code' => 422,
            'data' => null,
        ], 422);
    }

    /**
     * error
     *
     * @param int $statusCode
     * @param null $data
     * @param string $message
     * @return JsonResponse
     */
    public function error($data = null, $statusCode = 500, $message = 'error')
    {
        \Log::error(self::INTERNAL_SERVER_ERROR . json_encode($data));
        return response()->json([
            'message' => $message,
            'code' => $statusCode,
            'data' => $data,
        ], 500);
    }

    /**
     * Time out function response
     *
     * @param $data
     * @return JsonResponse
     */
    public function timeOut($data)
    {
        \Log::error(self::TXT_TIME_OUT . json_encode($data));
        return response()->json([
            'message' => '',
            'code' => 504,
            'data' => $data
        ]);
    }
}
