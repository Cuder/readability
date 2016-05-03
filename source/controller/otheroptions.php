<?php
if (isset($session) && checkSession($session) == true) {
	if (!isset($_POST['showtext'])) {
		// Selecting showing options to make the checkboxes selected
		$sth = $db_conn->prepare("SELECT showopt FROM sessions WHERE id='".$session."'");
		$sth->execute();
		$showoptions = $sth->fetchColumn();
		for ($i = 1; $i < 5; $i++) {
			$showopt[$i] = ($showoptions[$i] == 1)?" checked":"";
		}
		$smarty->assign('showopt',$showopt);
		$smarty->assign('options',array(
			$textStrings->options->option[3],
			$textStrings->options->option[4],
			$textStrings->options->option[5],
			$textStrings->options->option[6]
		));
		$smarty->assign('buttons',array(
			$textStrings->buttons->button[4],
			$textStrings->buttons->button[3]
		));
		$smarty->assign('tooltips',array(
			$textStrings->tooltips->tooltip[4],
			$textStrings->tooltips->tooltip[5],
			$textStrings->tooltips->tooltip[6],
			$textStrings->tooltips->tooltip[7]
		));
	} else {
		// Doing smth I don't remember what exactly
		require_once $rootdir."/model/readability.php";
		require_once $rootdir."/model/textmark.php";
		fallback($session."/result");
	}
} else {
	fallback();
}
