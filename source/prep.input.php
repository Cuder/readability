<?php
// forbid to open this file directly from the browser
if (preg_match("/prep.input.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

if (isset($_POST['preview_text'])) {

	// processing the text you entered previosly
	require_once "logic.input.php";
	
} else {
	
	if (isset($session)) {
		// checking current session
		$sth2 = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
		$sth2->execute();
		$id = $sth2->fetchColumn();
		if ($id != $session) fallback("index.php?error=session");
			
		// taking your text from the DB
		$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
		$sth->execute();
		$text = $sth->fetchColumn();
		$link = "index.php?stage=input&session=$session";
	} else {
		$link = "index.php?stage=input";
		$text = "";
	}

// input text form
echo "<div class='step'>Step 1. Enter your text</div>";
echo "<div class='inputarea'>";
echo "<form name='submit_form' method='post' action='$link' onsubmit='return empty_form()'>";
echo "<textarea name='text' id='text' maxlength='$maxsymbols' placeholder='Input your text in here...'>".$text."</textarea>";

// counters
echo "<table cellspacing='0' cellpadding='0' class='countertable'><tr>";
echo "<td>";
echo "Characters: <span id='syllablesNumber'>0/7000</span> (min. $minsymbols) <span id='okSpan'></span>";
echo "<br>Words: <span id='wordsNumber'>0<span>";
echo "</td>";

// buttons
echo "<td>";
echo "<div class='inputbuttonarea'>";
echo "<input type='submit' value='' class=hbutton>"; // hidden button for Opera border fix
echo "<input id='clear' type='reset' class='button' name='clear_text' value='Clear'>";
echo "<input id='submit' type='submit' class='button' name='preview_text' value='Proceed'>";
echo "</div>";
echo "</td>";

echo "</tr>";
echo "</table>";

echo "</form>";
echo "</div>";

// JS for counting words/charachters
echo "<script type='text/javascript' src='js/textareaCountNew.js'></script>";
}
?>
