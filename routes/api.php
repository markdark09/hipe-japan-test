<?php

use App\Http\Controllers\Api\WeatherForecastController;
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

Route::prefix('weather/forecast')->group(function(){
    Route::get('/general-list', [WeatherForecastController::class, 'generalList']);
    Route::get('/general-list/sort', [WeatherForecastController::class, 'listByCurrentBaseWeather']);
});
