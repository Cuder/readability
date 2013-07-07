<?php
// forbid to open this file directly from the browser
if (preg_match("/error.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

if ($error == 404) {
	$descr = "The page was not found, sorry."; 
} elseif ($error == 500)  {
	$descr = "Internal server error, sorry."; 
} elseif ($error == 'lang1')  {
	$error = "Language detection failed";
	$descr = "Some error occurred in the language detector class. Probably, the text is too small or the language is not supported.<div class='buttonarea'><input type='button' onclick='history.go(-1);' class='button' value='Try again'></div>"; 
} elseif ($error == 'lang2')  {
	$error = "Russian is not supported";
	$descr = "Sorry! As unpatriotic as it may seem, the script does not support Russian yet.<div class='buttonarea'><input type='button' onclick='location.href=\"index.php?stage=input\"' class='button' value='Enter new text'></div>"; 
} elseif ($error == 'text1')  {
	$error = "Small text";
	$descr = "The resulting text is still too small (less than ".$minwords." words). Rack your brain!<div class='buttonarea'><input type='button' onclick='location.href=\"index.php?stage=input\"' class='button' value='Try again'></div>"; 
} elseif ($error == 'text2')  {
	$error = "Rubbish detected";
	$descr = "The longest English word has ".$longestw." letters. Are you insane?<div class='buttonarea'><input type='button' onclick='history.go(-1);' class='button' value='Try again'></div>"; 
} elseif ($error == 'text3')  {
	$error = "Too large";
	$descr = "Your text has more than ".$maxsymbols." symbols.<div class='buttonarea'><input type='button' onclick='history.go(-1);' class='button' value='Try again'></div>"; 
} elseif ($error == 'session')  {
	$error = "Session error";
	$descr = "Something is wrong with the session. Please, try again or stop jerking around."; 
} elseif ($error == 'nojs')  {
	$error = "JavaScript disabled or not supported";
	$descr = "Please enable JavaScript in your browser and then reopen <a href='index.php'>this page</a>.";
} elseif ($error == 'noie')  {
	$error = "IE is forbidden";
	$descr = "I have discovered that you are using Internet Explorer. We do not collaborate with such people.";
} else {
	fallback("index.php");
}

echo "<div class='error'>".$error."</div>".$descr;
?>
