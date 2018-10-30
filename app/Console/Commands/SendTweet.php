<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTweet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweet:send {tweet}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the tweet';

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
        if(\App::environment('production'))
        { 
            $response = \Twitter::postTweet(['status' => $this->argument('tweet')]);
            \Log::info('Tweet posted', ['status' => $this->argument('tweet')]);
        }
        else {
            \Log::info('Tweet tested', ['status' => $this->argument('tweet')]);
        }
    }
}
