<?php
// Passing variables and GUI text strings to Smarty
$overview = $textStrings->aboutStrings->about[0];
$overview = preg_replace('/'.$overview['linkw'].'/','<a href="http://en.wikipedia.org/wiki/Readability_test" target="_blank">'.$overview['linkw'].'</a>',$overview);
$mail = $textStrings->aboutStrings->about[2];
$mail = preg_replace('/'.$mail['linkw'].'/','<a href="mailto:me@nikitakovin.ru">'.$mail['linkw'].'</a>',$mail);
$gitHub = $textStrings->aboutStrings->about[3];
$gitHub = preg_replace('/'.$gitHub['linkw'].'/','<a href="https://github.com/Cuder/readability" target="_blank">'.$gitHub['linkw'].'</a>',$gitHub);
$smarty->assign('overview',array(
	$overview,
	$textStrings->aboutStrings->about[1],
	$mail,
	$gitHub
));
$smarty->assign('captions',$textStrings->captions->caption);
$smarty->assign('tests',$textStrings->readability->test);
// Passing text strings with credits
$credits = $textStrings->credits->credit;
for ($i = 0; $i < count($credits); $i++) {
	if ($credits[$i]->name["url"]) {
		$name = "<a href='".$credits[$i]->name["url"]."' target='_blank'>".$credits[$i]->name."</a>";
	} else {
		$name = $credits[$i]->name;
	}
	if ($credits[$i]->job["url"]) {
		$job = "<a href='".$credits[$i]->job["url"]."' target='_blank'>".$credits[$i]->job."</a>";
	} else {
		$job = $credits[$i]->job;
	}
	$creditsSm[$i] = $name." for ".$job;
}
$smarty->assign('credits',$creditsSm);
