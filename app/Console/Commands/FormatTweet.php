<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class FormatTweet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweet:format {datetime} {body} {link} {lat} {lon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Formats the string to be sent to twitter';

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
        $body = $this->formatBody($this->argument('body'));

        $date = Carbon::parse($this->argument('datetime'));

        $tweet = 'Earthquake alert! Near '.$this->formatLocation($body['Location']).' '.$date->diffForHumans().". \n".
        'Magnitude '.$body['Magnitude'].', depth of '.$body['Depth'].". \n".
        'UKGS: '.$this->formatLink()."\n".
        'Map: '.$this->formatGmap();
        
        $this->call('tweet:send', ['tweet' => $tweet]);
    }

    private function formatBody($body) {
        $ret = [];
        $bits = explode(';', $body);
        foreach($bits as $bit) {
            $ex = explode(':', $bit, 2);
            $ex = array_map('trim', $ex);
            $ret[$ex[0]] = $ex[1];
        }
        return $ret;
    }

    private function formatLocation($loc) {
        $tc = explode(',', $loc);
        $tc = array_map(function($i){
            return ucwords(strtolower(trim($i)));
        }, $tc);
        return $tc[0].', '.$tc[1];
    }

    private function formatGmap() {
        return $this->createTinyUrl('https://www.google.com/maps/search/?api=1&query='.$this->argument('lat').','.$this->argument('lon'));
    }

    private function formatLink() {
        return $this->createTinyUrl($this->argument('link'));
    }

    private function createTinyUrl($strURL) {
        $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$strURL);
        return $tinyurl;
    }
}
