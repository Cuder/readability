<?php
// forbid to open this file directly from the browser
if (preg_match("/prep.readability.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

if (isset($session)) {

	$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$id = $sth->fetchColumn();
	if ($id != $session) fallback("index.php?error=session");
	
if (isset($_POST['proceed'])) {
	
	// processing the text you entered previosly
	require_once "logic.readability.php";
	fallback("index.php?stage=otheroptions&session=$session");
	
} else {
	
echo "<form name='submit_form' id='wrappingForm' method='post' action='index.php?stage=readability&session=$session' onsubmit='return empty_form()'>";
echo "<div class='step'>Step 2. Select readability tests</div>";

// readability checkboxes

// let's check what checkboxes should we select
	$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='".$session."'");
	$sth->execute();
	$showopt = $sth->fetchColumn();
	for ($i=0; $i<6; $i++) {
		$checked[$i] = ($showopt[$i] == 1)?" checked":"";
	}

echo "<input type='checkbox' name='reading_ease'".$checked['0']."> Flesch-Kincaid Reading Ease<br>";
echo "<input type='checkbox' name='grade_level'".$checked['1']."> Flesch-Kincaid Grade Level<br>";
echo "<input type='checkbox' class='group1' name='gunning_fog'".$checked['2']."> Gunning Fog Score<br>";
echo "<input type='checkbox' name='coleman'".$checked['3']."> Coleman liau index<br>";
echo "<input type='checkbox' class='group1' name='smog'".$checked['4']."> SMOG index<br>";
echo "<input type='checkbox'  name='automated_readability'".$checked['5']."> Automated readability index<br>";

// options which influence readability
echo "<div class='options' title='::For Gunning Fog Score and SMOG index tests only'>Options</div>";
echo "<font title=\"::Don't consider words like 'Mozerov' or 'Uzbekistan' long.\"><input type='checkbox' class='group2' name='proper'> Proper nouns aren't long</font><br>";
echo "<font title=\"::Don't consider a word long, if it is in the list of the most common English words (like 'difficult').\"><input type='checkbox' class='group2' name='common'> Common words aren't long (experimental)</font><br>";
echo "<font title=\"::Words with less than 3 syllables will be considered long if they are formal (like 'abstain').\"><input type='checkbox' class='group2' name='formal'> Formal words are long (experimental)</font>";

// notes

// button
echo "<div class='buttonarea'><input type='button' onclick='location.href=\"index.php?stage=input&session=$session\"' class='button' value='Back'> <input type='submit' class='button' name='proceed' value='Proceed'></div>";
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