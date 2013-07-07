<?php
// forbid to open this file directly from the browser
if (preg_match("/head.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// HTML head begins in here
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html lang="en"><head>';

	// Forbid to use browsers with JS turned off
	if (!isset($error)) {
		echo '<noscript><meta http-equiv="refresh" content="0;url=index.php?error=nojs"/></noscript>';
	}
	
	// Title
	echo '<title>Readability checker</title>';
	// HTML encoding
	echo '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">';
	// General CSS styles
	echo '<link rel="stylesheet" type="text/css" href="css/main.css">';
	// CSS styles for tooltips
	echo '<link rel="stylesheet" type="text/css" href="css/tooltip.css">';
	// JS script for tooltips 
	if (isset($stage) && $stage != "input") {
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		  <script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
		  <script type="text/javascript" src="js/jquery.tooltip.js"></script>
		  <script type="text/javascript" src="js/tooltip.js"></script>';
	}
	
// HTML head ends in here
echo '</head><body>';
?>
