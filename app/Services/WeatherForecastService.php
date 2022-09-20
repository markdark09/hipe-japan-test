<?php
namespace App\Services;

use App\Libraries\OpenWeatherMapLib;
use App\Repositories\Interfaces\IWeatherForecastRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WeatherForecastService 
{
    protected $weatherForecastRepository;
    protected $openWeatherMapLib;
    
    const MAX_ATTEMPTS = 10;
    const SECONDS_LOCKED = 3600;
    const DEFAULT_PAGINATION = 6;

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
     * @param $paginate
     * @return array
     */
    public function getListOfCitiesWithWeatherForecast($paginate = null) 
    {
        $paginate = $paginate ?? self::DEFAULT_PAGINATION;
        $cities = $this->paginate(Cache::get('cities'), $paginate);
        $generalList = [];

        if (count($cities) > 0) {
            foreach ($cities as $key => $city) {
                $weatherData = $this->openWeatherMapLib
                    ->fetchWeatherByCoordinates($city['latitude'], $city['longitude']);

                $weatherData['geoapify_coordinates'] = $city;
                array_push($generalList, $weatherData);
            }
        }

        return $generalList;
    }
    
    /**
     * Paginate custom arrays.
     * 
     * @return object
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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

        usort($generalList, function($a, $b) {
            $a = $a['weather'][0]['id'];
            $b = $b['weather'][0]['id'];

            return ($a < $b) ? -1 : 1;
        });

        return $generalList;
    }

    /**
     * Get weather forecast of specified
     * city by id.
     * 
     * @param float $latitude
     * @param float $longitude
     * 
     * @return collection
     */
    public function getWeatherDataByCoordinates(float $latitude, float $longitude)
    {
        $cityWeather = $this->openWeatherMapLib
            ->fetchWeatherByCoordinates($latitude, $longitude);

        return $cityWeather;
    }

    /**
     * Get weather forecast of specified
     * city by id.
     * 
     * @param float $latitude
     * @param float $longitude 
     * 
     * @return collection
     */
    public function getForecastedWeatherDataByCoordinates(float $latitude, float $longitude)
    {
        $forecastedCity = $this->openWeatherMapLib
            ->fetchForecastedWeatherByCoordinates($latitude, $longitude);

        return $forecastedCity;
    }
}
