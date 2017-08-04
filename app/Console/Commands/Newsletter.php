<?php

namespace App\Console\Commands;

use App\Services\MailerService;
use Illuminate\Console\Command;

class Newsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Newsletter:sending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending mails subscribers';

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
        $service = new MailerService();
        $service->sendVacancyOnFilter();
    }
}
