<?php
/*
  _____               _           _ _            _____        __ _                             _____                       
 |  __ \             (_)         (_) |          / ____|      / _| |                           / ____|                      
 | |__) | __ _____  ___ _ __ ___  _| |_ _   _  | (___   ___ | |_| |___      ____ _ _ __ ___  | |  __ _ __ ___  _   _ _ __  
 |  ___/ '__/ _ \ \/ / | '_ ` _ \| | __| | | |  \___ \ / _ \|  _| __\ \ /\ / / _` | '__/ _ \ | | |_ | '__/ _ \| | | | '_ \ 
 | |   | | | (_) >  <| | | | | | | | |_| |_| |  ____) | (_) | | | |_ \ V  V / (_| | | |  __/ | |__| | | | (_) | |_| | |_) |
 |_|   |_|  \___/_/\_\_|_| |_| |_|_|\__|\__, | |_____/ \___/|_|  \__| \_/\_/ \__,_|_|  \___|  \_____|_|  \___/ \__,_| .__/ 
                                         __/ |                                                                      | |    
                                        |___/                                                                       |_|    
                                            																 ___ __  _  __
                                            																|_  )  \/ |/ /
                                            																 / / () | / _ \
                                            																/___\__/|_\___/

-- PHP Source Code © Proximity Software Group

-- This file is original and proprietary. Unless you have been granted a legal license,
-- you have no legal right to view or use any part of this code. Violations or removal of any
-- copyright notices, or any text herein constitutes copyright infringement and lead to prosecution.
-- Intercepting a copy through a breach of file transfer protocol, or otherwise intercepting a transmission
-- does not constitute a legal license or legal receipt of this code. Our code is transmitted to your device
-- in order to run the software as we dictate. Using this code for your own personal or commercial use without
-- a legal license granted by us may be prosecuted to the fullest extent of law for willful copyright infringement.

-- All Rights Reserved.
-- This material may not be modified, stored, published, rewritten, sold, redistributed, duplicated or reproduced in whole or in part without the
-- express written permission with a lawful license for use granted thereof.
*/

/*
	Module: 		Weather
	Description: 	TBD
	Response:		
					Response =>
						Status 	=> (int) 200 - Success, 500 - Fail
*/
?>

<?php
	require( '../userConfig.php' );
	require( '../vendor/autoload.php' );

	// GuzzleHTTP namespace
	use GuzzleHttp\Client;

	// Pull tokens from userconfig
	$config = new \Configuration();

	class Weather {
		private $requestClient;
		private $config;
		private $icons = array(
			"01d"	=>	"icon_clear_sky_day",
			"01n"	=>	"icon_clear_sky_night",
			"02d"	=>	"icon_few_clouds_day",
			"02n"	=>	"icon_few_clouds_night",
			"03d"	=>	"icon_scattered_clouds_night",
			"03n"	=>	"icon_scattered_clouds_night",
			"04d"	=>	"icon_broken_clouds_day",
			"04n"	=>	"icon_broken_clouds_night",
			"09d"	=>	"icon_shower_rain_day",
			"09n"	=>	"icon_shower_rain_night",
			"10d"	=>	"icon_rain_day",
			"10n"	=>	"icon_rain_night",
			"11d"	=>	"icon_thunderstorm_day",
			"11n"	=>	"icon_thunderstorm_night",
			"13d"	=>	"icon_snow_day",
			"13n"	=>	"icon_snow_night",
			"50d"	=>	"icon_mist_day",
			"50n"	=>	"icon_mist_day"
		);
		private $conditions = array(
			// Thunderstorm Conditions
			200		=>	"Thunderstorms",
			201		=>	"Thunderstorms",
			202 	=>	"Heavy Thunderstorms",
			210		=>	"Light Thunderstorms",
			211		=>	"Thunderstorms",
			212		=>	"Heavy Thunderstorms",
			221 	=>	"Thunderstorms",
			230		=>	"Light Thunderstorms",
			231		=>	"Thunderstorms",
			232 	=> "Heavy Thunderstorms",

			// Drizzle
			300		=>	"Light Drizzle",
			301		=>	"Drizzle",
			302		=>	"Rain",
			310		=>	"Light Rain",
			311		=>	"Rain",
			312		=>	"Heavy Rain",
			313		=> 	"Showers",
			314		=>	"Heavy Showers",
			321		=>	"Rain",

			// Rain
			500		=>	"Light Rain",
			501		=>	"Rain",
			502		=>	"Heavy Rain",
			503		=>	"Heavy Rain",
			504		=>	"Heavy Rain",
			511		=>	"Freezing Rain",
			520		=>	"Showers",
			521		=>	"Showers",
			522		=>	"Heavy Showers",
			531		=>	"Rain",

			// Snow
			600		=>	"Light Snow",
			601		=>	"Snow",
			602		=>	"Heavy Snow",
			611		=>	"Sleet",
			612		=>	"Light Sleet",
			613		=>	"Sleet",
			615		=>	"Snow Showers",
			616		=>	"Snow Showers",
			620		=>	"Light Snow Showers",
			621		=>	"Snow Showers",
			622		=>	"Heavy Snow Showers",

			// Atmosphere
			701 	=>	"Mist",
			711		=>	"Smoke",
			721		=>	"Haze",
			731		=>	"Dust",
			741		=>	"Fog",
			751		=>	"Sand",
			761		=>	"Dust",
			762		=>	"Volcanic Ash",
			771		=>	"Squall",
			781		=>	"Tornado",

			// Clear
			800		=>	"Clear Sky",

			// Clouds
			801		=>	"Partly Cloudy",
			802		=>	"Scattered Clouds",
			803		=>	"Cloudy",
			804		=>	"Cloudy"
		);

		function __construct(){
			// Setup basic request interface
			$this->requestClient = new Client([
				'base_uri' 	=> 'http://api.openweathermap.org',
				'timeout'	=> 5.0,
			]);

			$this->config = new \Configuration();
		}

		/*
			Name: reverseGeocode( (str) longitude, (str) latitude )
			Desc: Reverse geocoding gets name of the location from geographical coordinates
		*/
		function reverseGeocode( $long, $lat ){
			return $this->requestClient->request('GET', 'geo/1.0/reverse', [
				'query' => [
					'lat' 	=> $lat,
					'lon' 	=> $long,
					'limit' => 1,
					'appid' => $this->config->getWeatherAPI(),
				]
			])->getBody();
		}

		/*
			Name: reverseGeocodeByName( (str) name )
			Desc: Reverse geocoding gets name, lat, and long of the location from city/state/country name
		*/
		function reverseGeocodeByName( $name ){
			return $this->requestClient->request('GET', 'geo/1.0/direct', [
				'query' => [
					'q' 	=> $name,
					'limit' => 1,
					'appid' => $this->config->getWeatherAPI(),
				]
			])->getBody();
		}

		/*
			Name: getForecast( (str) longitude, (str) latitude )
			Desc: Reverse geocoding gets name of the location from geographical coordinates
		*/
		function getForecast( $long, $lat ){
			return $this->requestClient->request('GET', 'data/2.5/onecall', [
				'query' => [
					'lat' 	=> $lat,
					'lon' 	=> $long,
					'exclude' => 'minutely,hourly',
					'units'	=> 'imperial',
					'appid' => $this->config->getWeatherAPI(),
				]
			])->getBody();
		}

		/*
			Name: getEquivalentIcon( (str) icon )
			Desc: Returns the proper icon equivalent for our svg image set
		*/
		function getEquivalentIcon( $icon ){
			if( array_key_exists( $icon, $this->icons ) ){
				return $this->icons[ $icon ];
			}

			return '';
		}

		/*
			Name: getEquivalentDescription( (int) id )
			Desc: Returns a descriptive name of the current weather conditions
		*/
		function getEquivalentDescription( $id ){
			if( array_key_exists( $id, $this->conditions ) ){
				return $this->conditions[ $id ];
			}

			return '';
		}
	}

	$weather = new \Weather();
	$response = null;

	try {
		// Check if lat, long, or both are missing or null from config
		if( $config->getLatitude() == '' || $config->getLongitude() == '' || !$config->getLongitude() || !$config->getLatitude() ){
			// Attempt reverse lookup for lat/lon
			if( ($config->getCity() != '' || !$config->getCity()) && ($config->getCountry() != '' || !$config->getCountry()) ){
				$query = ($config->getState() != '' || !$config->getState()) ? ($config->getCity() . ", " . $config->getState() . ", " . $config->getCountry()) : ($config->getCity() . ", " . $config->getCountry() );
				$resp = $weather->reverseGeocodeByName( $query );
				$jsonData = json_decode($resp, true);
				
				if( isset( $jsonData ) && isset( $jsonData[0]) ){
					$config->setLatitude( $jsonData[0]["lat"] );
					$config->setLongitude( $jsonData[0]["lon"] );
				}
			}
		}

		// Call our API method
		$resp = $weather->getForecast($config->getLongitude(), $config->getLatitude());
		$geocode = $weather->reverseGeocode($config->getLongitude(), $config->getLatitude());

		// Attempt to decode API response body as JSON
		$jsonData = json_decode($resp, true);
		
		// Geocoding data (city & state names)
		$geoData = json_decode($geocode, true);
		$jsonData['city'] = $geoData[0]['name'];

		if( isset( $geoData[0]['state'] ) ){
			$jsonData['state'] = $geoData[0]['state'];
		}

		// Current Weather
		$current = $jsonData['current']['weather'][0];
		// Setup with our icon set
		$current['icon'] = $weather->getEquivalentIcon($current['icon']);
		// Setup with grammatically friendly descriptions
		$current['description'] = $weather->getEquivalentDescription($current['id']);

		$sunrise = new DateTime('@' . $jsonData['current']['sunrise']);
		$sunrise->setTimezone(new DateTimeZone($jsonData['timezone']));

		$sunset = new DateTime('@' . $jsonData['current']['sunset']);
		$sunset->setTimezone(new DateTimeZone($jsonData['timezone']));

		$jsonData['current']['sunrise'] = $sunrise->format("h:i A");
		$jsonData['current']['sunset'] = $sunset->format("h:i A");

		// Commit changes
		$jsonData['current']['weather'][0] = $current;

		// Purge current day data in 7-day since its a duplicate of current
		unset( $jsonData['daily'][0] );

		// Forecast Weather
		foreach( $jsonData['daily'] as &$day ){
			// Convert UNIX-timestamp to DateTime object for detecting day of week in local timezone
			$dailyDT = new DateTime( '@' . $day['dt'] );
			$dailyDT->setTimezone(new DateTimeZone($jsonData['timezone']));
			$day['week_day'] = $dailyDT->format('l');

			// Loop inner weather descriptive data
			foreach( $day['weather'] as &$dayWeather ){
				// Setup with our icon set and descriptions
				$dayWeather['icon'] = $weather->getEquivalentIcon($dayWeather['icon']);
				// NOT NEEDED IN CURRENT REVISION => $dayWeather['description'] = $weather->getEquivalentDescription($dayWeather['id']);
			}
		}

		// Exception not caught during heavy work, so looks good from here!
		$jsonData['status'] = 200;
		$response = json_encode($jsonData);

		/*
		$response = json_encode(array(
			'status' 			=> 200,
			'localTimeZone'		=> $jsonData['timezone'],
			'current'			=> array(
									'timestamp' => $jsonData['current']['dt'],
									'sunrise'	=> $jsonData['current']['sunrise'],
									'sunset'	=> $jsonData['current']['sunset'],
									'temp'		=> $jsonData['current']['temp'],
									'humidity'	=> $jsonData['current']['humidity'],
								),
			'forecast'			=> array(
									''
								),
			'lat'				=> $jsonData['lat'],
			'lon'				=> $jsonData['lon']
		));
		*/
	}
	catch(\GuzzleHttp\Exception\RequestException $e){
		// Return an error if request to API fails
		$response = json_encode(array(
			'status' 	=> 500
		));
	}

	// Check if fail to encode or error during encode
	if( $response == null ){
		$response = json_encode(array(
			'status' 	=> 500
		));
	}

	echo $response;
?>