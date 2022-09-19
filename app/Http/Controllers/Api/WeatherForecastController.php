<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\OpenWeatherMapLib;
use Illuminate\Support\Facades\Cache;

class WeatherForecastController extends Controller
{
    public $openWeatherMapLib;

    /**
     * Constructor of WeatherForecastController
     * 
     * @param App\Libraries\OpenWeatherMapLib $openWeatherMapLib
     */
    public function __construct(OpenWeatherMapLib $openWeatherMapLib)
    {
        $this->openWeatherMapLib = $openWeatherMapLib;
    }

    /**
     * Get list of cities(cached) and return 
     * the list w/ basic open weather data
     * 
     * @return Responder
     */
    public function generalList() 
    {
        $cities = Cache::get('cities');
        $generalList = [];

        if (count($cities) > 0) {
            foreach ($cities as $key => $city) {
                array_push($generalList, 
                    $this->openWeatherMapLib
                        ->fetchWeatherData($city['latitude'], $city['longitude'])
                );
            }
        }

        return responder()->success($generalList);
    }
}
