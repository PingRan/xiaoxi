<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pheanstalk\Pheanstalk;

class ProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $pheanstalk = Pheanstalk::create('127.0.0.1');

        $pheanstalk
            ->useTube('myTube')
            ->put("this is my first job !");

        $pheanstalk
            ->useTube('myTube')
            ->put(
                json_encode(['test' => 'data']),  // encode data in payload
                Pheanstalk::DEFAULT_PRIORITY,     // default priority
                30, // delay by 30s
                60  // beanstalk will retry job after 60s
            );
    }
}
