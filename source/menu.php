<?php
// forbid to open this file directly from the browser
if (preg_match("/menu.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// the menu bar itself

// 1
if (!isset($session) && !isset($stage)) {
	echo "<a href='index.php?stage=input'>Input your text</a>";
} elseif ($stage != "input") {
	echo "<a href='index.php?stage=input&session=$session'>Input your text</a>";
} else {
	echo "Input your text";
}
echo "<br>";

// 2
if (isset($session) && isset($stage) && $stage != "readability") {
	echo "<a href='index.php?stage=readability&session=$session'>Select readability tests</a>";
} else {
	echo "Select readability tests";
}
echo "<br>";

// 3
if (isset($session) && isset($stage) && $stage != "otheroptions") {
	echo "<a href='index.php?stage=otheroptions&session=$session'>Define other options</a>";
} else {
	echo "Define other options";
}
echo "<br>";

// 4
if (isset($session) && isset($stage) && $stage != "result") {
	$sth3 = $db_conn->prepare("SELECT stage FROM sessions WHERE id='".$session."'");
	$sth3->execute();
	$complete = $sth3->fetchColumn();
	if ($complete == 1) { 
		echo "<a href='index.php?stage=result&session=$session'>Get your results</a>";
	} else {
		echo "Get your results";
	}
} else {
	echo "Get your results";
}
echo "<br><br>";

// 5
echo "<a href='index.php'";
if (isset($session)) echo " target='_blank'";
echo ">About</a><br>";