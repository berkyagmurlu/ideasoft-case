<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="IdeaSoft Case API Documentation",
 *     description="API documentation for IdeaSoft Case Study",
 *     @OA\Contact(
 *         email="berkyagmurlu@gmail.com",
 *         name="Berk Yağmurlu"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
