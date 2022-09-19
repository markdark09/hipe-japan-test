<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class OpenWeatherMapLib
{
    CONST API_KEY = '42df33a708ae411b04a4706763c8a6bf';
    CONST END_POINT = 'https://api.openweathermap.org/data/2.5/weather';

    /**
     * Get weather data of specified
     * coordinates.
     * 
     * @return array
     */
    public function fetchWeatherData($latitude, $longitude)
    {
        $response = Http::accept('application/json')
            ->get(self::END_POINT, [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => self::API_KEY
            ]);
        
        return $response->json() ?? null;
    }
}

