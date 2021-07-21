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
	Module: 		RSS
	Description: 	News stories and RSS feeds
	Response:		
					Response =>
						Status 	=> (int) 200 - Success, 500 - Fail
*/
?>

<?php
	require( '../userConfig.php' );
	require( '../vendor/autoload.php' );

	// Pull tokens from userconfig
	$config = new \Configuration();

	class RSS {
		private $config;

		function __construct(){
			$this->config = new \Configuration();
		}

		function getFeeds(){
			return $this->config->getRSSFeeds();
		}
	}

	$rss = new \RSS();
	$feedData = array(
		"Data"	=> array()
	);
	$response = null;

	try {
		foreach( $rss->getFeeds() as $feedUri ){
			$f = Feed::loadRss($feedUri);
			$feedItems = array();
			$unix = time() - (60 * 60 * 24 * 7);

			foreach ($f->item as $item) {
				if($item->timestamp < $unix){ continue; }
				array_push($feedItems, 
					array(
						"Title"		=> 	$item->title,
						"Source"	=>  $f->title,
						"Timestamp"	=>	$item->timestamp
					)
				);
			}

			array_push($feedData["Data"], array(
				"Title" => $f->title,
				"Items" => $feedItems
			));

			$response = $feedData;
			
			$response['status'] = 200;

			$response = json_encode($response);
		}
	}
	catch(Exception $e){
		// Return an error if something fails
		$response = null;
	}

	// Check if fail to encode or error during encode
	if( $response == null ){
		$response = json_encode(array(
			'status' 	=> 500
		));
	}

	echo $response;
?>