<?php
namespace App\Services;

use App\Libraries\OpenWeatherMapLib;
use App\Repositories\Interfaces\IWeatherForecastRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class WeatherForecastService 
{
    protected $weatherForecastRepository;
    protected $openWeatherMapLib;
    
    const MAX_ATTEMPTS = 10;
    const SECONDS_LOCKED = 3600;

    /**
     * WeatherForecastService constructor
     * 
     * @param \IWeatherForecastRepository $iWeatherForecastRepository
     */
    public function __construct(
        IWeatherForecastRepository $iWeatherForecastRepository,
        OpenWeatherMapLib $openWeatherMapLib
    )
    {
        $this->weatherForecastRepository = $iWeatherForecastRepository;
        $this->openWeatherMapLib = $openWeatherMapLib;
    }

    /**
     * Get list of cities(cached) and return 
     * the list w/ basic open weather data
     * 
     * @return array
     */
    public function getListOfCitiesWithWeatherForecast() 
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

        return $generalList;
    }

    /**
     * Use the getListOfCitiesWithWeatherForecast 
     * and categorized base on their weather ex. 
     * by Rain, by Sunny, and etc.
     * 
     * @return array
     */
    public function sortListOfCitiesByBaseWeather()
    {
        $generalList = $this->getListOfCitiesWithWeatherForecast();

        usort($generalList, function($a, $b){
            $a = $a['weather'][0]['id'];
            $b = $b['weather'][0]['id'];

            return ($a < $b) ? -1 : 1;
        });

        return $generalList;
    }
}
