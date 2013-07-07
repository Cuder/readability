<?php
// forbid to open this file directly from the browser
if (preg_match("/prep.otheroptions.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

if (isset($session)) {
	$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$id = $sth->fetchColumn();
	if ($id != $session) fallback("index.php?error=session");
	
if (isset($_POST['showtext'])) {
	
	// processing the text you entered previosly
	require_once "logic.readability.php";
	require_once "logic.textmark.php";
	
} else {
	
	// selecting showing options to make the checkboxes selected
	$sth = $db_conn->prepare("SELECT showopt FROM sessions WHERE id='".$session."'");
	$sth->execute();
	$showopt = $sth->fetchColumn();
		$showstats = ($showopt['1'] == 1)?" checked":"";
		$showtext = ($showopt['2'] == 1)?" checked":"";
		$longw = ($showopt['3'] == 1)?" checked":"";
		$fwords = ($showopt['4'] == 1)?" checked":"";

echo "<form name='submit_form' method='post' action='index.php?stage=otheroptions&session=$session' onsubmit='return empty_form()'>";
echo "<div class='step'>Step 3. Select other options</div>";

// other options check boxes
echo "<font title='::Show the statistics of your text in the output (the number of sentences, words, syllables, percent of long words, etc.)'><input type='checkbox' name='showstats'$showstats> Show text statistics</font><br>";
echo "<font title=\"::Show your text in the output\"><input type='checkbox' class='group1' name='textshow'$showtext> Show your text</font><br>";
echo "<font title=\"::Underline words with three or more syllables in the output\"><input type='checkbox' class='group2' name='longwords'$longw> Mark long words (experimental)</font><br>";
echo "<font title=\"::Mark words people consider sophisticated, written or formal\"><input type='checkbox' class='group2' name='fwords'$fwords> Mark formal words (experimental)</font>";

// notes

// buttons
echo "<div class='buttonarea'><input type='button' onclick='location.href=\"index.php?stage=readability&session=$session\"' class='button' value='Back'> <input type='submit' class='button' name='showtext' value='Proceed'></div>";
echo "</form>";

// ява
echo "<script type='text/javascript'>
	function updateButton() {
		if ($('.group1:checked').length == 0) {
			$('.group2')
				.prop('disabled', true)
				.prop('checked', false);
		} else {
			$('.group2').prop('disabled', false);
		}
	}
	$('.group1').change(function () {
		updateButton();
	});
	updateButton();
	</script>";

}

} else {
	fallback("index.php");
}
?>
