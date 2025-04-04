<?php

//-------Define BASE_URL dynamically----------//

// Get the request scheme (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";

// Get the host (domain or localhost)
$host = $_SERVER['HTTP_HOST'];

/**----Returns the directory path of the currently executing 
       script relative to the root directory of the web server----*/
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);

define('BASE_URL', $protocol . "://" . $host . $scriptDir);
//echo BASE_URL ;

?>