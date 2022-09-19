<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IWeatherForecastRepository;
use Illuminate\Database\Eloquent\Model;

class WeatherForecastRepository extends BaseRepository implements IWeatherForecastRepository
{
    protected $model;

    /**
     * WeatherForecastRepository constructor
     * 
     * @param Model $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}