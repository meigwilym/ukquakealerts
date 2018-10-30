# UK Earthquake Alert Twitter Bot

This is the code that runs the [@uk\_quake](https:/twitter.com/uk\_quake) twitter account. 

It's a Laravel app (PHP) that: 

* Checks the UK Geological Survey RSS feed
* Compares with the latest date saved
* If there's fresh items, tweets an alert with a link to the UKGS page, and to a map

The feed is checked every 5 minutes. 

## Sources

UK Geological Survey RSS feed: http://quakes.bgs.ac.uk/feeds/MhSeismology.xml

More sources are available from [The BGS online data feed page](http://www.earthquakes.bgs.ac.uk/feeds/feeds.html). 

Code by [@meigwilym](https://twitter.com/meigwilym). 
