<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function ($router) {
    $router->post('login', [AuthController::class , 'login']);

    $router->group(['middleware' => 'auth:sanctum'], function ($router) {
        $router->post('logout', [AuthController::class , 'logout']);
        $router->get('me', [AuthController::class , 'me']);
    });
});
