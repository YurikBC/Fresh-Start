<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="API for job posting website",
 *         description="API for website that provides job vacancies in various IT companies all over Belarus. This web-application is ment for students as well as for people with no (or little) working experience in IT sphere, as it offers exactly those positions, where no experience is required.",
 *     )
 * )
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function exceptionResponse($message)
    {
        return response()->json(
            [
                'error' => [
                    "status" => 204,
                    "title" => $message,
                    "source" => [
                        "parameter" => Request::fullUrl()
                    ],

                ]
            ]);
    }
}
