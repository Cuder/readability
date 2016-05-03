<?php
if (!isset($_POST['preview_text'])) {
	if (isset($session) && checkSession($session) == true) {
		// Taking your text from the DB
		$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
		$sth->execute();
		$text = $sth->fetchColumn();
	} else {
		$text = "";
		$smarty->assign('ifSessionComplete',0);
	}
	// Passing variables and GUI text strings to Smarty
	$smarty->assign('text',$text);
	$smarty->assign('maxsymbols',$maxsymbols);
	$smarty->assign('minsymbols',$minsymbols);
	$smarty->assign('inputStrings',$textStrings->inputStrings->inputString);
	$smarty->assign('buttons',array(
		$textStrings->buttons->button[2],
		$textStrings->buttons->button[3]
	));
} else {
	// Cleaning the text and recording it into the DB
	require_once $rootdir."/model/input.php";
	fallback($session."/readability");
}
