<?php
// forbid to open this file directly from the browser
if (preg_match("/prep.input.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

if (isset($session)) {
	$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$id = $sth->fetchColumn();
	if ($id != $session) fallback("index.php?error=session");
}

if (isset($_POST['preview_text'])) {

	// processing the text you entered previosly
	require_once "logic.input.php";
	
} else {
	
	if (isset($session)) {
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
echo "<form name='submit_form' method='post' action='$link' onsubmit='return empty_form()'>";
echo "<div class='step'>Step 1. Enter your text</div>";
echo "<textarea name='text' id='text' maxlength='$maxsymbols' placeholder='Input your text in here...'>".$text."</textarea>";

// notes
echo "Maximum allowed symbols: $maxsymbols.<br>";
echo "Minimum symbols required: $minsymbols.<br>";
echo "Minimum words required: $minwords.";

// button
echo "<div class='buttonarea'><input type='submit' class='button' name='preview_text' value='Proceed'></div>";
echo "</form>";

// ява, кричащая о том, что ничего не введено или введен небольшой текст
echo "<script type='text/javascript'>
		function empty_form ()
		{
			var txt = document.getElementById('text').value;
			if(txt == '') {
				alert('You must\'ve forgotten to enter your text, buddy');
				return false;
			} else if (txt.length < $minsymbols) {
				alert('Your text is too short. Rack your brain!');
				return false;	
			}    
				return true;
		}
	  </script>\n";
}
?>
