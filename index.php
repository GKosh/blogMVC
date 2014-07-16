<?php


$timer = microtime(true);

// Define derictories

function CurPageURL() {
 $pageURL = 'http';
if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) { $pageURL .= "s";} 
  $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/';
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].'/';
 }
 return $pageURL;
}

define ('PAGE_URL', CurPageURL());



define ('BASE_PATH',dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR);
define ('APP_PATH', BASE_PATH . 'app'.DIRECTORY_SEPARATOR);
define ('CNTRL_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR .'controller' . DIRECTORY_SEPARATOR);
define ('MDL_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR .'model' . DIRECTORY_SEPARATOR);
define ('view_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR .'view' . DIRECTORY_SEPARATOR);
define ('LOG_PATH', BASE_PATH . 'tmp' . DIRECTORY_SEPARATOR);
define ('PAGE_PATH', APP_PATH . 'data' . DIRECTORY_SEPARATOR .'page' . DIRECTORY_SEPARATOR);
// require core files

require_once APP_PATH . 'config.php';
require_once CNTRL_PATH . 'controller.php';
require_once CNTRL_PATH . 'corecontroller.php';


// log
//file_put_contents( LOG_PATH . "log.txt","\n\r".date(DATE_RFC822) . "\n\r",FILE_APPEND);
function logMessage($message){
//file_put_contents( LOG_PATH . "log.txt", $message. "\n",FILE_APPEND);
}


// StartUp
call_user_func(array(new corecontroller,'run'));

// Script timer
$timer = microtime(true) - $timer;
echo  '<br> Script execution time:    ' . $timer*1000 . " milliseconds";
echo '</body>';
