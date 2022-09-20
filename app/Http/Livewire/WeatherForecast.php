<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class WeatherForecast extends Component
{
    public $generalList = [];
    public $loadedList = [];

    public function render()
    {
        return view('livewire.weather-forecast')->layout('layouts.app');
    }
}
