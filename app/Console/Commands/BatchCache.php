<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BatchCache extends Command
{

    CONST CATEGORIES = 'commercial';
    CONST JAPAN_COORDINATES = 'place:51754d6407e217614059d7badc5abd144240f00101f90169d5050000000000c0020b920306e697a5e69cac';
    CONST LANG = 'en';
    CONST API_KEY = '2051704e1e454bb6a245fc914375a055';
    CONST END_POINT = 'https://api.geoapify.com/v2/places';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:cache {--limit=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache places by consuming geoapify';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $places = $this->places($this->option('limit'));
        
        return $this->cities($places);
    }

    /**
     * Get places with commercial categories in
     * japan coordicates.
     * 
     * @param int $limit
     */
    public function places(int $limit = 10)
    {
        $response = Http::accept('application/json')
            ->get(self::END_POINT, [
                'categories' => self::CATEGORIES,
                'filter' => self::JAPAN_COORDINATES,
                'limit' => $limit,
                'apiKey' => self::API_KEY
            ]);
        
        return $response->json()['features'];
    }

    /**
     * Store cities in cache.
     * 
     * @param array $places
     */
    public function cities($places)
    {
        $cities = [];

        if (count($places) > 0) {
            foreach ($places as $key => $place) {
                $properties = $place['properties'];

                array_push($cities, [
                    'geoapify_id' => $properties['place_id'],
                    'longitude' => $properties['lon'],
                    'latitude' => $properties['lat']
                ]);
            }
        }

        return $cities;
    }
}
