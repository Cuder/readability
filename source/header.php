<?php
// forbid to open this file directly from the browser
if (preg_match("/header.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

echo "Readability checker<sup> beta</sup>";