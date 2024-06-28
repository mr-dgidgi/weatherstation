<?php


require __DIR__ . '/vendor/autoload.php';
use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

/////////////////////////////////////////
//InfluxDB connection 

$token = 'vt_pVtKvP4awVbAHyndAl--2-3rbkHydE9EZzMi26tlkVXTO8wpdenhhpo-AGWJTap1vyY_YVok-VtYlnsg-lQ==';
$org = 'dgidgi.ovh';
$bucket = 'Weatherstation ';

$client = new Client([
    "url" => "http://192.168.3.5:8086",
    "token" => $token,
    "bucket" => $bucket,
    "org" => $org,
    "precision" => InfluxDB2\Model\WritePrecision::S,
]);


/////////////////////////////////////////

// OVERLY VERBOSE HELPER STUFF
function RoundIt($ee){
  return round($ee, 2);
}
function toKM( $a) {
  return  RoundIt( floatval($a)*1.60934);
}
function toC( $a) {
  return RoundIt(  (floatval($a)-32) * (5/9) );
}
function toMM( $a) {
    return RoundIt( floatval($a)*25.4);
}
  
function toHPA( $a) {
  return RoundIt(floatval($a)*33.8639);
}
function toMS( $a) {
	return RoundIt(floatval($a)*3.6);
}

#// FOUND THIS ON THE NET IN SOME PASTEBIN
#function wind_cardinal( $degree ) { 
#  switch( $degree ) {
#      case ( $degree >= 348.75 && $degree <= 360 ):
#          $cardinal = "N";
#      break;
#      case ( $degree >= 0 && $degree <= 11.249 ):
#          $cardinal = "N";
#      break;
#      case ( $degree >= 11.25 && $degree <= 33.749 ):
#          $cardinal = "N NO";
#      break;
#      case ( $degree >= 33.75 && $degree <= 56.249 ):
#          $cardinal = "NO";
#      break;
#      case ( $degree >= 56.25 && $degree <= 78.749 ):
#          $cardinal = "O NO";
#      break;
#      case ( $degree >= 78.75 && $degree <= 101.249 ):
#          $cardinal = "O";
#      break;
#      case ( $degree >= 101.25 && $degree <= 123.749 ):
#          $cardinal = "O ZO";
#      break;
#      case ( $degree >= 123.75 && $degree <= 146.249 ):
#          $cardinal = "ZO";
#      break;
#      case ( $degree >= 146.25 && $degree <= 168.749 ):
#          $cardinal = "N";
#      break;
#      case ( $degree >= 168.75 && $degree <= 191.249 ):
#          $cardinal = "Z";
#      break;
#      case ( $degree >= 191.25 && $degree <= 213.749 ):
#          $cardinal = "Z ZW";
#      break;
#      case ( $degree >= 213.75 && $degree <= 236.249 ):
#          $cardinal = "ZW";
#      break;
#      case ( $degree >= 236.25 && $degree <= 258.749 ):
#          $cardinal = "W ZW";
#      break;
#      case ( $degree >= 258.75 && $degree <= 281.249 ):
#          $cardinal = "W";
#      break;
#      case ( $degree >= 281.25 && $degree <= 303.749 ):
#          $cardinal = "W NW";
#      break;
#      case ( $degree >= 303.75 && $degree <= 326.249 ):
#          $cardinal = "NW";
#      break;
#      case ( $degree >= 326.25 && $degree <= 348.749 ):
#          $cardinal = "N NW";
#      break;
#      default:
#          $cardinal = null;
#  }
# return $cardinal;
#}
#



$datetime = date('Y-m-d H:i:s');
$baromin = toHPA($_GET["baromin"]);
$temp = toC($_GET["tempf"]);
$dewpt = toC($_GET["dewptf"]);
$humidity = $_GET["humidity"];
$windspeedkph = toKM($_GET["windspeedmph"]);
$windspeedms = toMS($windspeedkph);
$windgustkph = toKM($_GET["windgustmph"]);
$winddir = $_GET["winddir"];
$rainmm = toMM($_GET["rainin"]);
$dailyrainmm = toMM($_GET["dailyrainin"]);
$indoortemp = toC($_GET["indoortempf"]);
$indoorhumidity = $_GET["indoorhumidity"];

# Windchill
$windchill = RoundIt(13.12+(0.6215*$temp)-(11.37+pow($windspeedms,0.16))+(0.3965*$temp*pow($windspeedms,0.16)));

# Indice de chaleur
$heatindice = RoundIt(-8.785+(1.611*$temp)+(2.339*$humidity)-(0.146*$temp*$humidity)-(1.231*10^-2*pow($temp,2))-(1.642*pow(10,-2)*pow($humidity,2))+(2.212*pow(10,-3)*pow($temp,2)*$humidity)+(7.255*pow(10,2)*$temp*pow($humidity,2))-(3.582*pow(10,6)*pow($temp,2)*pow($humidity,2)));



$writeApi = $client->createWriteApi();

$data = "meteo,location=Sanxay baromin=$baromin,temp=$temp,dewpt=$dewpt,humidity=$humidity,windspeedkph=$windspeedkph,windgustkph=$windgustkph,winddir=$winddir,rainmm=$rainmm,dailyrainmm=$dailyrainmm,indoortemp=$indoortemp,indoorhumidity=$indoorhumidity";
$writeApi->write($data, WritePrecision::S, $bucket, $org);

$client->close();
?>
success
