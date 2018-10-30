<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches a feed and sends a tweet for any new entries';

    private $filename = 'latestTimestamp.json';

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
        $items = $this->getReversedItems();
        
        if($items) { 
            foreach($items as $item) {
                $this->call('tweet:format', $item);
            }
            $this->saveTimestamp($item['datetime']);
        }
    }

    private function getXMLFeed(){
        $url = "http://quakes.bgs.ac.uk/feeds/MhSeismology.xml";
        return simplexml_load_file($url);
    }

    private function getReversedItems() {
        $xml = $this->getXMLFeed();
        $timestamp = $this->getMostRecentTimeStamp();
        $items = [];

        foreach($xml->channel->item as $item) {
            $itemTime = new DateTime($item->pubDate);
            if($itemTime > $timestamp) {
                $ns_dc = $item->children('http://www.w3.org/2003/01/geo/wgs84_pos#');
                $items[] = [
                    'datetime' => (string) $item->pubDate,
                    'body' => (string) $item->description,
                    'link' => (string) $item->link,
                    'lat' => (string) $ns_dc->lat,
                    'lon' => (string) $ns_dc->long,
                ];
            }
        }
        return array_reverse($items);
    }

    private function getMostRecentTimeStamp() : DateTime {        
        $json = Storage::disk('local')->get($this->filename);
        $obj = json_decode($json);
        return new DateTime($obj->timestamp);
    }

    private function saveTimestamp($timestamp) {
        $timestamp = new DateTime($timestamp);
        return Storage::disk('local')->put($this->filename, json_encode(['timestamp' => $timestamp->format('Y-m-d H:i:s')]));
    }
}
