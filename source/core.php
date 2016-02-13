<?php
// forbid to open this file directly from the browser
if (preg_match("/core.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// debug level
ini_set('display_errors', 1); 
error_reporting(E_ALL); 

// if the user is too stupid not to use IE
function maxsite_testIE() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browserIE = false;
    if ( stristr($user_agent, 'MSIE 13.0') ) $browserIE = true; // IE13
    if ( stristr($user_agent, 'MSIE 12.0') ) $browserIE = true; // IE12
    if ( stristr($user_agent, 'MSIE 11.0') ) $browserIE = true; // IE11
    if ( stristr($user_agent, 'MSIE 10.0') ) $browserIE = true; // IE10
    if ( stristr($user_agent, 'MSIE 9.0') ) $browserIE = true; // IE9
    if ( stristr($user_agent, 'MSIE 8.0') ) $browserIE = true; // IE8
    if ( stristr($user_agent, 'MSIE 7.0') ) $browserIE = true; // IE7
    if ( stristr($user_agent, 'MSIE 6.0') ) $browserIE = true; // IE6
    if ( stristr($user_agent, 'MSIE 5.0') ) $browserIE = true; // IE5
    return $browserIE;
}

// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	$supers = array("_REQUEST","_ENV","_SERVER","_POST","_GET","_COOKIE","_SESSION","_FILES","_GLOBALS");
	foreach ($supers as $__s) {
		if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
	}
	unset($supers);
}

// Prevent any possible XSS attacks via $_GET.
if (stripget($_GET)) {
	die("Prevented a XSS attack through a GET variable!");
}
function stripget($check_url) {
	$return = false;
	if (is_array($check_url)) {
		foreach ($check_url as $value) {
			if (stripget($value) == true) {
				return true;
			}
		}
	} else {
		$check_url = str_replace(array("\"", "\'"), array("", ""), urldecode($check_url));
		if (preg_match("/<[^<>]+>/i", $check_url)) {
			return true;
		}
	}
	return $return;
}

// Start Output Buffering
//ob_start();
//ob_start("ob_gzhandler");

// Fallback to safe area in event of unauthorised access
function fallback($location) {
	header("Location: ".$location);
	exit;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if ('QUOTES_GPC') $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
	$search = array("&", "\"", "'", "\\", "<", ">");
	$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Validate numeric input
function isNum($value) {
	return (preg_match("/^[0-9]+$/", $value));
}

// Format the date & time accordingly
function showdate($format, $val) {
	global $settings;
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate") {
		return strftime($settings[$format], $val+($settings['timeoffset']*3600));
	} else {
		return strftime($format, $val+($settings['timeoffset']*3600));
	}
}

function str_split_php4_utf8($str) { 
    // place each character of the string into and array 
    $split=1; 
    $array = array(); 
    for ( $i=0; $i < strlen( $str ); ){ 
        $value = ord($str[$i]); 
        if($value > 127){ 
            if($value >= 192 && $value <= 223) 
                $split=2; 
            elseif($value >= 224 && $value <= 239) 
                $split=3; 
            elseif($value >= 240 && $value <= 247) 
                $split=4; 
        }else{ 
            $split=1; 
        } 
            $key = NULL; 
        for ( $j = 0; $j < $split; $j++, $i++ ) { 
            $key .= $str[$i]; 
        } 
        array_push( $array, $key ); 
    } 
    return $array; 
}