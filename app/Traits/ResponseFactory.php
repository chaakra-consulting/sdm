<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


trait ResponseFactory
{
    /**
     * @param mixed $message
     * @param int $statusCode
     * @param Exception|null $exception
     * @param int $error_code
     * 
     * @return JsonResponse
     */
    private function RespondError($message, int $statusCode = 400, Exception $exception = null, int $error_code = 1): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message ?? "Oops, Something wrong",
                'exception' => $exception,
                'error_code' => $error_code
            ],
            $statusCode
        );
    }

    /**
     * @param mixed $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function successResponse($message, $status = 200): JsonResponse
    {
        return response()->json(
            [
                "success" => true,
                "message" => $message,
            ],
            $status
        );
    }


    /**
     * @param mixed $message
     * @param mixed $data
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function successResponseData($message, $data, $status = 200): JsonResponse
    {
        return response()->json(
            [
                "success" => true,
                "message" => $message,
                "data" => $data
            ],
            $status
        );
    }


    /**
     * @param mixed $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function emptyResponse($message, $status = 200): JsonResponse
    {
        return response()->json(
            [
                "success" => true,
                "message" => $message,
            ],
            $status
        );
    }


    /**
     * @param mixed $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function internalErrorResponse($message, $status = 500): JsonResponse
    {
        return $this->RespondError($message, $status);
    }

    /**
     * @param mixed $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function notFoundResponse($message = "Oops. Request not found", $status = 404): JsonResponse
    {
        return $this->RespondError($message, $status);
    }

    /**
     * @param mixed $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    protected function unauthorizedResponse($message = "Oops. You cannot access the data", $status = 401): JsonResponse
    {
        return $this->RespondError($message, $status);
    }

    /**
     * @param ValidationException $ex
     * 
     * @return JsonResponse
     */
    public function validationErrorResponse(ValidationException $ex): JsonResponse
    {
        return response()->json(
            [
                "success" => false,
                "message" => $ex->getMessage(),
                "errors" => $ex->errors(),
            ],
            422
        );
    }

    /**
     * @param mixed $message
     * @param Collection $collection
     * 
     * @return JsonResponse
     */
    public function responseCollection($message, Collection $collection): JsonResponse
    {
        return response()->json(
            [
                "success" => true,
                "message" => $message,
                "data" => $collection
            ],
        );
    }
}
