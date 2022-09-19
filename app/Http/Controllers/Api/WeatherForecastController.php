<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoordinatesRequest;
use App\Services\WeatherForecastService;

class WeatherForecastController extends Controller
{
    public $weatherForecastService;

    /**
     * Constructor of WeatherForecastController
     * 
     * @param \Services\WeatherForecastService $weatherForecastService
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
            ->getListOfCitiesWithWeatherForecast();

        return responder()->success($list);
    }

    /**
     * Use the getListOfCitiesWithWeatherForecast 
     * and categorized base on their weather ex. 
     * by Rain, by Sunny, and etc.
     * 
     * @return Responder
     */
    public function listByCurrentBaseWeather() 
    {
        $sortedByBaseWeather = $this->weatherForecastService
            ->sortListOfCitiesByBaseWeather();

        return responder()->success($sortedByBaseWeather);
    }

    /**
     * Get single city w/ basic open weather data
     * 
     * @param CoordinatesRequest $request
     * 
     * @return Responder
     */
    public function singleCurrentDetails(CoordinatesRequest $request)
    {
        $sortedByBaseWeather = $this->weatherForecastService
            ->getWeatherDataByCoordinates($request['latitude'], $request['longitude']);

        return responder()->success($sortedByBaseWeather);
    }

    /**
     * Get single city w/ basic open weather data
     * 
     * @param CoordinatesRequest $request
     * 
     * @return Responder
     */
    public function singleGetFullDetails(CoordinatesRequest $request)
    {
        $sortedByBaseWeather = $this->weatherForecastService
            ->getForecastedWeatherDataByCoordinates($request['latitude'], $request['longitude']);

        return responder()->success($sortedByBaseWeather);
    }
}
