<?php

namespace App\Console\Commands;

use App\Libraries\GeoapifyLib;
use Illuminate\Console\Command;

class BatchCache extends Command
{
    public $geoapifyLib;

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
    protected $description = 'Cache cities by consuming geoapify';

    /**
     * Constructor
     */
    public function __construct(GeoapifyLib $geoapifyLib)
    {
        parent::__construct();
        $this->geoapifyLib = $geoapifyLib;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $geoapifyLib = $this->geoapifyLib;
        $fetchedPlaces = $geoapifyLib->fetchPlaces($this->option('limit'));
        dd($fetchedPlaces);
        $cities = $this->geoapifyLib->getCities($fetchedPlaces);
        $this->isSuccessMessage($cities);

        return $cities;
    }

    /**
     * Pop successful message if response 
     * was success!
     * 
     * @param array $cities
     * @return info string
     */
    public function isSuccessMessage(array $cities)
    {
        return count($cities) > 0 ? $this->info('Successfully cached cities!') 
            : $this->info('No places fetched from geoapify!');
    }
}
