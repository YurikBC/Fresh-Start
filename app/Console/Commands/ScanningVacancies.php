<?php

namespace App\Console\Commands;

use App\Services\ScanningVacanciesService;
use App\Vacancy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ScanningVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScanningVacancies:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scanning new vacancies and add their in DB and delete non-actuality';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = Redis::getFacadeRoot();
        $service = new ScanningVacanciesService(new Vacancy(),$redis);
        $service->updateVacancies();
    }
}