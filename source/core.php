<?php
// Setting debug level
if ($debugging == true) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

// Forbid the user to enter random variables in the address bar of the browser
if ($_GET) {
	$allowedVar = array("stage","error","session");
	foreach ($_GET as $key => $value) {
		if (!in_array($key,$allowedVar) || ($value == "" && $key != "error") || ($value != "" && $key == "error")) {
			fallback();
		}
	}
}

// Throwing an error to an IE user
if (!isset($error) && maxsite_testIE()) {
	$_SESSION['errorCode'] = "noie";
	fallback("error");
}
function maxsite_testIE() {
    $browserIE = false;
	for ($i=5; $i<12; $i++) {
		if (stristr($_SERVER['HTTP_USER_AGENT'],'MSIE '.$i.'.0')) $browserIE = true;
	}
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
	$_SESSION['errorCode'] = "xss";
	fallback("error");
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

// Fallback to safe area in event of unauthorised access
function fallback($location = "") {
	header("Location: http://".$_SERVER['SERVER_NAME']."/".$location);
	exit;
}

// DB connection
function connectDB() {
	global $db_host,$db_name,$db_user,$db_pass;
	try {
		$db_conn = new PDO('mysql:host='.$db_host.';dbname='.$db_name.'', $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));
		$db_conn->exec('SET NAMES utf8');
		return $db_conn;
	} catch (PDOException $e) {
		$_SESSION['errorCode'] = "db";
		$_SESSION['dbmessage'] = $e->getMessage();
		fallback("error");
	}
}

// Check if a session exists and correct
function checkSession($session) {
	global $db_conn,$smarty;
	$sth = $db_conn->prepare("SELECT stage FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$ifSessionComplete = $sth->fetchColumn();
	if ($ifSessionComplete == "") {
		$_SESSION['errorCode'] = "session";
		fallback("error");
	} else {
		$smarty->assign('session',$session);
		$smarty->assign('ifSessionComplete',$ifSessionComplete);
		return true;
	}
}
