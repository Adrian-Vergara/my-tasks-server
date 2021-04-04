<?php


namespace App\Traits;


use App\Entities\Response;

trait JsonApiResponse
{
    public function jsonResponse(Response $response){
        return response()->json($response, $response->code);
    }
}
