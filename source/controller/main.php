<?php
if (isset($stage)) {
	$smarty->assign('stepText',$textStrings->commonStrings->commonString[4]);
	$smarty->assign('stage',$stage);
}
$smarty->assign('title',$textStrings->commonStrings->commonString[0]);
$smarty->assign('menuItems',$textStrings->menu->item);
$smarty->assign('author',$textStrings->commonStrings->commonString[1]);
$smarty->assign('runtimeText',$textStrings->commonStrings->commonString[2]);
$smarty->assign('msec',$textStrings->commonStrings->commonString[3]);
$smarty->assign('swVersion',$swVersion);

// Finishing to count script running time
$smarty->assign('runtime',round((microtime(true)-$start_time), 5)*1000);

// Displaying the page
$smarty->display('main.tpl');
$smarty->clearAllCache();
