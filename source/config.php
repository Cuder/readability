<?php
// forbid to open this file directly from the browser
if (preg_match("/config.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

$maxsymbols = 7000; // maximum symbols allowed in the textarea
$minsymbols = 400; // minimum symbols required in the textarea
$minwords = 20; // minimum words required in the textarea (not including digits and other stupid numbers)
$longestw = 45; // the longest English word possible

?>
