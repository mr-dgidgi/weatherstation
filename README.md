# weatherstation
Hack for Bresser Weatherstation to send data to influxdb

Need composer, influxdata

## Intro

The goal of this project is to be able to get the data sent by Bresser Weatherstation and send it to and influxdb database instead of the hardcoded external services.
The project was originaly made for mysql database and was almost a copy of this project : [original forum discussion](https://community.home-assistant.io/t/weather-station-and-weather-underground-work-around/204443). As we store timeseries data Influxdb is more usefull than Mysql.

To make it working you'll need thew things : 
* a local DNS
* an apache2 server with php and composer
* an influxdb server
All theses services can be store on the same server 

---

## DNS configuration
You have to create 2 DNS records to redirect the traffic to your apache2 server :
* rtupdate.wunderground.com
* weatherstation.wunderground.com

## influxdb
* Create a bucket named Weather station (or another name but you'll have to change it in updateweatherstation.php)
* create a token with Read access to Weatherstation bucket

## apache server
* Install a standard apache2 server
* Install php8.2
* Copy weatherstation.conf to ```/etc/apache2/site-available```
  * Change the VirtualHost IP
  * You can change the logs location
* Copy updateweatherstation.php to ```/var/www/weatherstation/```
  * Customise the variables for token org and url
* Install composer : [download](https://getcomposer.org/download/)
* Install influxdb library ```/var/www/weatherstation/``` in : [influxdb-client-php](https://github.com/influxdata/influxdb-client-php)
* test that there is no error in php or with the token
  * ```php /var/www/weatherstation/updateweatherstation.php```
* activate the website ```a2ensite weatherstation```



