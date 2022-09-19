<?php
namespace App\Services;

use App\Libraries\OpenWeatherMapLib;
use App\Repositories\Interfaces\IWeatherForecastRepository;
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

        return $generalList;
    }
}
