<?php

namespace App\Http\Middleware;

class CheckAuth extends GetUserFromToken
{

    protected function respond($event, $error, $status, $payload = [])
    {
        return $this->response->json(
            [
                'error' => [
                    "status" => $status,
                    "title" => $error,
                    "source" => [
                        "parameter" => Request::fullUrl()
                    ],
                ]
            ], $status
        );
    }
}
