<?php
// Starting to count script running time
$start_time = microtime(true);

session_start();
$rootdir = dirname(__FILE__);
$textStrings = simplexml_load_file('view/textStrings.xml');

require_once $rootdir."/config.php";
require_once $rootdir."/core.php";
require_once $rootdir."/libs/Smarty/setup.php";

// Main page
if (!$_GET) require_once $rootdir."/controller/about.php";

// Handling of errors
if (isset($error)) {
	if ((isset($_SESSION['errorCode']) || http_response_code() == 403 || http_response_code() == 404) && !isset($session) && !isset($stage)) {
		require_once $rootdir."/controller/errors.php";
	} else {
		fallback();
	}
}

// Text processing stages
if (isset($stage)) {
	$allowedStages = array("input","readability","otheroptions","result","getrtf");
	if (in_array($stage,$allowedStages)) {
		$db_conn = connectDB();
		require_once $rootdir."/controller/".$stage.".php";
	} else {
		fallback();
	}
} else {
	if (isset($session)) fallback();
}

require_once $rootdir."/controller/main.php";
session_destroy();
