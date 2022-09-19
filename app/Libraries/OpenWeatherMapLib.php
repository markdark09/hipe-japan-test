<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class OpenWeatherMapLib
{
    CONST API_KEY = '42df33a708ae411b04a4706763c8a6bf';
    CONST DOMAIN = 'https://api.openweathermap.org/data/2.5';
    CONST WEATHER_END_POINT = self::DOMAIN . '/weather';
    CONST FORECAST_END_POINT = self::DOMAIN . '/forecast';
    CONST CNT = 7;
    CONST UNITS = 'metric';

    /**
     * Get weather data of specified
     * using by coordinates.
     * 
     * @param int $latitude
     * @param int $longitude
     * 
     * @return array
     */
    public function fetchWeatherByCoordinates(int $latitude, int $longitude)
    {
        $response = Http::accept('application/json')
            ->get(self::WEATHER_END_POINT, [
                'lat' => $latitude,
                'lon' => $longitude,
                'cnt' => self::CNT,
                'units' => self::UNITS,
                'appid' => self::API_KEY
            ]);
        
        return $response->json() ?? null;
    }

    /**
     * Get forecasted data 5days with every 
     * 3hrs using by coordinates.
     * 
     * @param int $latitude
     * @param int $longitude
     *
     * @return array
     */
    public function fetchForecastedWeatherByCoordinates(int $latitude, int $longitude)
    {
        $response = Http::accept('application/json')
            ->get(self::FORECAST_END_POINT, [
                'lat' => $latitude,
                'lon' => $longitude,
                'cnt' => self::CNT,
                'units' => self::UNITS,
                'appid' => self::API_KEY
            ]);
        
        return $response->json() ?? null;
    }
}

