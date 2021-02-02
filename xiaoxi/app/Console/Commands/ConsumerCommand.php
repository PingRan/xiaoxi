<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pheanstalk\Pheanstalk;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer';

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

// we want jobs from 'testtube' only.
        $pheanstalk->watch('myTube');

// this hangs until a Job is produced.
        $job = $pheanstalk->reserve();

        try {
            $jobPayload = $job->getData();
            // do work.

            sleep(2);
            // If it's going to take a long time, periodically
            // tell beanstalk we're alive to stop it rescheduling the job.
            $pheanstalk->touch($job);
            sleep(2);

            // eventually we're done, delete job.
            $pheanstalk->delete($job);
        }
        catch(\Exception $e) {
            // handle exception.
            // and let some other worker retry.
            $pheanstalk->release($job);
        }
    }
}
