<?php
// Required PHP extensions
if (!function_exists('json_decode')) {
	throw new Exception('DetectLanguage needs the JSON PHP extension.');
}

// Library files
require_once $rootdir."/libs/DetectLanguage/Error.php";
require_once $rootdir."/libs/DetectLanguage/DetectLanguage.php";
require_once $rootdir."/libs/DetectLanguage/Client.php";

use \DetectLanguage\DetectLanguage;
DetectLanguage::setApiKey($langDetectAPI);

$enFound = false;
$langDetection = DetectLanguage::detect($text);

foreach ($langDetection as $language) {
	if ($language->isReliable == 1) {
		if ($language->language == "en") {
			$enFound = true;
			break;
		} else {
			$csv = array_map('str_getcsv', file($rootdir.'/libs/DetectLanguage/languages.csv'));
			$number = array_search($language->language,array_column($csv,0));
			$_SESSION['errorCode'] = "language";
			$_SESSION['language'] = $csv[$number][1];
			fallback("error");
		}
	}
}

if ($enFound == false) {
	$_SESSION['errorCode'] = "ldfailed";
	fallback("error");
}
