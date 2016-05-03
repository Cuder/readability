<?php
if (isset($_SESSION['errorCode'])) {
	$error = $_SESSION['errorCode'];
} else {
	$error = http_response_code();
}
$errorText = $textStrings->xpath('//strings/errors/error[@code="'.$error.'"]')[0];
$smarty->assign('error',$error);
$errorTitle = $errorText->title;
$errorDescr = $errorText->desc;
switch ($error) {
	case "db":
		$errorDescr .= "<br>".$_SESSION['dbmessage'].".";
		break;
	case "smalltext":
		$errorDescr = preg_replace('/\$minwords/',$minwords,$errorDescr);
		break;
	case "rubbish":
		$errorDescr = preg_replace('/\$longestw/',$longestw,$errorDescr);
		break;
	case "toolarge":
		$errorDescr = preg_replace('/\$maxsymbols/',$maxsymbols,$errorDescr);
		break;
	case "language":
		$errorTitle = preg_replace('/\$language/',$_SESSION['language'],$errorTitle);
		break;
}
$smarty->assign('errorTitle',$errorTitle);
$smarty->assign('errorDescr',$errorDescr);
$smarty->assign('buttons',array(
	$textStrings->buttons->button[0],
	$textStrings->buttons->button[1]
));
