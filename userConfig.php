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
?>
<?php
	class Configuration {
		private static $conf = __DIR__ . '/config.json';

		private static $timeZone;
		private static $weatherAPI;
		private static $city;
		private static $country;
		private static $state;
		private static $lat;
		private static $lon;
		private static $stockAPI;
		private static $stocks;
		private static $rssFeeds;

		function initialize(){
			$base = array(
				'TimeZone' 			=> 'America/New_York',
				'OpenWeatherToken' 	=> '',
				'City'				=> '',
				'Country'			=> '',
				'State'				=> '',
				'Latitude'			=> '',
				'Longitude'			=> '',
				'StockToken'		=> '',
				'Stocks'			=> array(),
				'RSSFeeds'			=> array(),
			);
				
			return file_put_contents(self::$conf, json_encode($base));
		}

		public static function isNullified(){
			return !(		isset(self::$timeZone)
						&& 	isset(self::$weatherAPI)
						&& 	isset(self::$city)
						&& 	isset(self::$country)
						&& 	isset(self::$state)
						&& 	isset(self::$lat)
						&& 	isset(self::$lon)
						&& 	isset(self::$stockAPI)
						&& 	isset(self::$stocks)
						&& 	isset(self::$rssFeeds)
					);
		}

		public static function commit(){
			$changes = array(
				'TimeZone' 			=> self::$timeZone,
				'OpenWeatherToken' 	=> self::$weatherAPI,
				'City'				=> self::$city,
				'Country'			=> self::$country,
				'State'				=> self::$state,
				'Latitude'			=> self::$lat,
				'Longitude'			=> self::$lon,
				'StockToken'		=> self::$stockAPI,
				'Stocks'			=> self::$stocks,
				'RSSFeeds'			=> self::$rssFeeds,
			);

			if( self::isNullified($changes) ){ return; }
			return file_put_contents(self::$conf, json_encode($changes));
		}

		function __construct(){
			// Check if config exists
			if( !is_file( self::$conf ) ){
				$this->initialize();
			}

			// Attempt decode userdata
			$configData = json_decode( file_get_contents( self::$conf ), true );

			// If parse fails due to malformed json, re-create
			if( $configData == null ){
				$this->initialize();

				// Something is seriously fucked if decode fails here
				$configData = json_decode( file_get_contents( self::$conf ), true );
			}			

			// Store all custom data
			$this->setTimeZone( $configData['TimeZone'] );
			$this->setWeatherAPI( $configData['OpenWeatherToken'] );
			$this->setCity( $configData['City'] );
			$this->setCountry( $configData['Country'] );
			$this->setState( $configData['State'] );
			$this->setLatitude( $configData['Latitude'] );
			$this->setLongitude( $configData['Longitude'] );
			$this->setStockAPI( $configData['StockToken'] );
			$this->setTrackedStocks( $configData['Stocks'] );
			$this->setRSSFeeds( $configData['RSSFeeds'] );
		}

		public static function getRSSFeeds(){
			return self::$rssFeeds;
		}

		public static function setRSSFeeds( $uris ){
			self::$rssFeeds = $uris;
			Configuration::commit();
		}

		private static function addRSSFeed( $uri ){
			array_push(self::$rssFeeds, $uri);
			Configuration::commit();
		}

		private static function removeRSSFeed( $uri ){
			self::$rssFeeds = array_diff(self::$rssFeeds, array($uri));
			Configuration::commit();
		}

		public static function getTrackedStocks(){
			return self::$stocks;
		}

		public static function setTrackedStocks( $stocks ){
			self::$stocks = $stocks;
			Configuration::commit();
		}

		private static function addTrackedStock( $ticker ){
			array_push(self::$stocks, $ticker);
			Configuration::commit();
		}

		private static function removeTrackedStock( $ticker ){
			self::$stocks = array_diff(self::$stocks, array($ticker));
			Configuration::commit();
		}

		public static function getTimeZone(){
			return self::$timeZone;
		}

		public static function setTimeZone( $tz ){
			self::$timeZone = $tz;
			Configuration::commit();
		}

		public static function getStockAPI(){
			return self::$stockAPI;
		}

		public static function setStockAPI( $token ){
			self::$stockAPI = $token;
			Configuration::commit();
		}

		public static function getWeatherAPI(){
			return self::$weatherAPI;
		}

		public static function setWeatherAPI( $token ){
			self::$weatherAPI = $token;
			Configuration::commit();
		}

		public static function getCity(){
			return self::$city;
		}

		public static function setCity( $city ){
			self::$city = $city;
			Configuration::commit();
		}

		public static function getCountry(){
			return self::$country;
		}

		public static function setCountry( $country ){
			self::$country = $country;
			Configuration::commit();
		}

		public static function getState(){
			return self::$state;
		}

		public static function setState( $state ){
			self::$state = $state;
			Configuration::commit();
		}

		public static function getLatitude(){
			return self::$lat;
		}

		public static function setLatitude( $lat ){
			self::$lat = $lat;
			Configuration::commit();
		}

		public static function getLongitude(){
			return self::$lon;
		}

		public static function setLongitude( $lon ){
			self::$lon = $lon;
			Configuration::commit();
		}
	}
?>