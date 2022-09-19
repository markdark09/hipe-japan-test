<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherForecastService;
use Illuminate\Support\Facades\Cache;

class WeatherForecastController extends Controller
{
    public $weatherForecastService;

    /**
     * Constructor of WeatherForecastController
     * 
     * @param \WeatherForecastService $weatherForecastService
     */
    public function __construct(WeatherForecastService $weatherForecastService)
    {
        $this->weatherForecastService = $weatherForecastService;
    }

    /**
     * Get list of cities(cached) and return 
     * the list w/ basic open weather data
     * 
     * @return Responder
     */
    public function generalList() 
    {
        $list = $this->weatherForecastService
            ->generalList();

        return responder()->success($list);
    }
}
