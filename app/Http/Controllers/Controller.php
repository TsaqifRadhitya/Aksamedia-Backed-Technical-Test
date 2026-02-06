<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Technical Test Backend Aksamedia",
    description: "API Documentation for Technical Test Backend Aksamedia"
)]
#[OA\Server(
    url: "https://aksamedia-backed.laravel.cloud",
    description: "API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Sanctum",
    description: "Enter token in format (Bearer <token>)"
)]
class Controller
{
    //
}
