<?php


namespace App\Traits;


use App\Entities\Response;
use Illuminate\Http\Response as HttpStatus;

trait ApiResponse
{
    public function successResponse($message = '', $content = null, $code = HttpStatus::HTTP_OK): Response{
        return $this->loadDataResponse($code, true, $message, $content);
    }

    public function errorResponse($message, $code, $content = null): Response{
        return $this->loadDataResponse($code, false, $message, $content);
    }

    private function loadDataResponse($code, $success, $message, $content): Response{
        $response = new Response();
        $response->code = $code;
        $response->success = $success;
        $response->message = $message;
        $response->content = $content;
        return $response;
    }
}
