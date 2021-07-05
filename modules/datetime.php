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
	Module: 		Date & Time
	Description: 	This module returns the current date and time given the users configured time zone and localization settings
	Response:		JSON encoded datetime data in the following pre-determined format
					Response =>
						Status 	=> (int) 200 - Success, 500 - Fail
						Date 	=> (str) FormattedDate
						Time 	=> (str) FormattedTime
*/
?>

<?php
	require( '../userConfig.php' );

	// Grab user defined time-zone from configuration
	$config = new \Configuration();

	$curDate = new \DateTime( $config->getTimeZone() );

	$response = json_encode(array(
		'status' 		=> 200,
		'textual'		=> $curDate->format('l'),
		'month'			=> $curDate->format('F'),
		'day'			=> $curDate->format('j'),
		'ordinal'		=> $curDate->format('S'),
		'time' 			=> $curDate->format('g:i A')
	));

	// Check if fail to encode or error during encode
	if( $response == null ){
		$response = json_encode(array(
			'status' 	=> 500
		));
	}

    echo $response;
?>