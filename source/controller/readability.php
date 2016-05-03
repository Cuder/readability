<?php
if (isset($session) && checkSession($session) == true) {
	if (!isset($_POST['proceed'])) {
		$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='".$session."'");
		$sth->execute();
		$showopt = $sth->fetchColumn();
		for ($i=0; $i<6; $i++) {
			$checked[$i] = ($showopt[$i] == 1)?" checked":"";
		}
		$smarty->assign('checked',$checked);
		// Passing variables and GUI text strings to Smarty
		$smarty->assign('tests',$textStrings->readability->test);
		$smarty->assign('tooltips',array(
			$textStrings->tooltips->tooltip[0],
			$textStrings->tooltips->tooltip[1],
			$textStrings->tooltips->tooltip[2],
			$textStrings->tooltips->tooltip[3]
		));
		$smarty->assign('optionsTitle',$textStrings->commonStrings->commonString[5]);
		$smarty->assign('options',array(
			$textStrings->options->option[0],
			$textStrings->options->option[1],
			$textStrings->options->option[2]
		));
		$smarty->assign('buttons',array(
			$textStrings->buttons->button[4],
			$textStrings->buttons->button[3]
		));
	} else {
		// Calculating readability scores
		require_once $rootdir."/model/readability.php";
		fallback($session."/otheroptions");
	}
} else {
	fallback();
}
