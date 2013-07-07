<?php
// forbid to open this file directly from the browser
if (preg_match("/prep.result.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// checking session
if (isset($session)) {
	$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$id = $sth->fetchColumn();
	if ($id != $session) fallback("index.php?error=session");

// selecting showin' options
$sth = $db_conn->prepare("SELECT showopt FROM sessions WHERE id='".$session."'");
$sth->execute();
$showopt = $sth->fetchColumn();

if (substr($showopt,0,3) != "000") {
	
// printing text
echo "<div class='step'>Look what we've gotten here</div>";	
	
// if the user wanted to show him readability scores
if ($showopt['0'] == 1) {
	
	require_once "readability.output.php";
	
	// selecting readability showin' options
	$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='".$session."'");
	$sth->execute();
	$showopt2 = $sth->fetchColumn();
	
	// printing readability scores
	echo "<div class='resultcaption'>Readability scores</div>";
	echo "<div class='result'>";
	echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
	
	$sth = $db_conn->prepare("SELECT flesch1,flesch2,fog,coleman,smog,automated FROM tempt WHERE session='".$session."'");
	$sth->execute();
	$result = $sth->fetch();
	
	// Flesch-Kincaid Reading Ease
	if ($showopt2['0'] == 1) {
		echo "<tr><td width='50%'>Flesch-Kincaid Reading Ease</td>";
		echo "<td width='50%'>";
			$td = 100-$result['0'];
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title=\"".$result[0]."/100::This test rates text on a 100-point scale. The higher the score, the easier it is to understand the document. For most standard files, you want the score to be between 60 and 70.\"><tr>";
			if ($result['0']>0 && $result['0']<100) {
				echo "<td style='background-color:".percent2color($result['0'])."; width:".$result['0']."%;'></td><td width='$td%'></td>";
			} elseif ($result['0']<=0) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'>".$result['0']."</td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";
	}
	
	// Flesch-Kincaid Grade Level
	if ($showopt2['1'] == 1) {
		echo "<tr><td width='50%'>Flesch-Kincaid Grade Level</td>";
		echo "<td width='50%'>";	
			$title = grade2title($result['1']);
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title='".$result['1']."::The result is a number that corresponds with a US grade level. Your score of ".$result['1']." indicates that the text is expected to be understandable $title.'><tr>";
			if ($result['1']>0 && $result['1']<20) {
				echo "<td style='background-color:".grade2color($result['1'])."; width:$grade_percent%;'></td>";
				$td = 100-$grade_percent;
				echo "<td width='$td%'></td>";
			} elseif ($result['1']>=20) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'></td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";
	}
	
	// Gunning Fog Score
	if ($showopt2['2'] == 1) {
		echo "<tr><td width='50%'>Gunning Fog Score</td>";
		echo "<td width='50%'>";
			$title = grade2title($result['2']);
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title='".$result['2']."::The result is a number that corresponds with a US grade level. Your score of ".$result['2']." indicates that the text is expected to be understandable $title.'><tr>";
			if ($result['2']>0 && $result['2']<20) {
				echo "<td style='background-color:".grade2color($result['2'])."; width:$grade_percent%;'></td>";
				$td = 100-$grade_percent;
				echo "<td width='$td%'></td>";
			} elseif ($result['2']>=20) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'></td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";
	}
	
	// Coleman-Liau index
	if ($showopt2['3'] == 1) {
		echo "<tr><td width='50%'>Coleman-Liau index</td>";
		echo "<td width='50%'>";
			$title = grade2title($result['3']);
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title='".$result['3']."::The result is a number that corresponds with a US grade level. Your score of ".$result['3']." indicates that the text is expected to be understandable $title.'><tr>";
			if ($result['3']>0 && $result['3']<20) {
				echo "<td style='background-color:".grade2color($result['3'])."; width:$grade_percent%;'></td>";
				$td = 100-$grade_percent;
				echo "<td width='$td%'></td>";
			} elseif ($result['3']>=20) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'></td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";	
	}
	
	// SMOG index
	if ($showopt2['4'] == 1) {
		echo "<tr><td width='50%'>SMOG index</td>";
		echo "<td width='50%'>";
			$title = grade2title($result['4']);
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title='".$result['4']."::The result is a number that corresponds with a US grade level. Your score of ".$result['4']." indicates that the text is expected to be understandable $title.'><tr>";
			if ($result['4']>0 && $result['4']<20) {
				echo "<td style='background-color:".grade2color($result['4'])."; width:$grade_percent%;'></td>";
				$td = 100-$grade_percent;
				echo "<td width='$td%'></td>";
			} elseif ($result['4']>=20) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'></td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";
	}
	
	// Automated readability index
	if ($showopt2['5'] == 1) {
		echo "<tr><td width='50%'>Automated readability index</td>";
		echo "<td width='50%'>";
			$title = grade2title($result['5']);
			echo "<table cellspacing='0' cellpadding='0' border='0' style='width:100%; height:10px;' title='".$result['5']."::The result is a number that corresponds with a US grade level. Your score of ".$result['5']." indicates that the text is expected to be understandable $title.'><tr>";
			if ($result['5']>0 && $result['5']<20) {
				echo "<td style='background-color:".grade2color($result['5'])."; width:$grade_percent%;'></td>";
				$td = 100-$grade_percent;
				echo "<td width='$td%'></td>";
			} elseif ($result['5']>=20) {
				echo "<td style='text-align:center; background-color:red; width:1%;'></td><td style='width:99%'></td>";
			} else {
				echo "<td style='text-align:center; background-color:rgb(0,255,0); width:100%;'></td>";
			}
			echo "</tr></table>";
		echo "</td></tr>";
	}
	echo "</tr></table>";
	echo "</div>";
}

// if the user wanted to show him his text
if ($showopt['2'] == 1) {
	
	// check marking options
	if ($showopt['3'] == 1 || $showopt['4'] == 1) {
		// selecting marked text from the DB
		$sth = $db_conn->prepare("SELECT marked FROM tempt WHERE session='".$session."'");	
	} else {
		// selecting raw text from the DB
		$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
	}
	$sth->execute();
	$text = $sth->fetchColumn();
	// printing marked text
	echo "<div class='resultcaption'>Your text</div>";
	echo "<div class='result'>";
	echo $text;
	echo "</div>";
}

// if the user wanted to show him text statistics
if ($showopt['1'] == 1) {	
	// selecting text statistics from the DB
	$sth = $db_conn->prepare("SELECT sylaverage,syltotal,woraverage,wortotal,longwnumber,longwpercent,sentences FROM tempt WHERE session='".$session."'");
	$sth->execute();
	$result = $sth->fetch();

	// printing statistics of the text
	echo "<div class='resultcaption'>Text statistics</div>";
	echo "<div class='result'>";
	echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
	echo "<tr><td width='40%'>Number of sentences:</td>";
	echo "<td width='30%'>".$result[6]."</td>";
	echo "<td width='30%'></td></tr>";
		
	echo "<tr style='background-color:#DADADA'><td width='40%'>Syllables:</td>";
	echo "<td width='30%'>".$result[1]." (total)</td>";
	echo "<td width='30%'>".$result[0]." (average, per word)</td></tr>";
	
	echo "<tr><td width='40%'>Words:</td>";
	echo "<td width='30%'>".$result[3]." (total)</td>";
	echo "<td width='30%'>".$result[2]." (average, per sentence)</td></tr>";
	
	echo "<tr style='background-color:#DADADA'><td width='40%'>Words with three or more syllables:</td>";
	echo "<td width='30%'>".$result[4]." (total)</td>";
	echo "<td width='30%'>".$result[5]."% (of all words)</td></tr>";
	echo "</table>";
	
	echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
	echo "<tr><td width='40%' style='vertical-align:top'><font title='::Function words do not count'>10 most used words:</font></td><td width='60%'>";
	
	require_once "functionwords.php";
	
	$stmt = $db_conn->prepare("SELECT word,count FROM tempw WHERE word NOT IN ($array) AND session='".$session."' ORDER BY count DESC LIMIT 10");
	$stmt -> execute();
	$i = 0;
	while ($word = $stmt->fetch()) {
			echo $word[0]." (".$word[1].")";
			if ($i==9) { echo "."; } else { echo ", "; }
			$i++;
	}
	echo "</td></tr></table>";
	echo "</div>";
}
	
	// save results
	echo "<div class='resultcaption'>Save results</div>";
	echo "<div class='result'>";
	echo "<a href='getrtf.php?session=$session' target='_blank'>Click here</a> to download your report in RTF.";
	
	$current_path = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	echo "<br>You can also save the link below to continue experimenting with your text later.<br>";
	echo "<input style='width: 100%;' value='$current_path' onclick='this.select();' onfocus='this.select();' readonly='readonly' />";
	echo "<br>Please remember that the link is accessible within approximately 24-48 hours and only from your computer.";
	echo "</div>";

} else {
	echo "<div class='step'>There's nothing to show</div>";	
	echo "It seems that you selected nothing. Go back and pick some options!";
}

// buttons
echo "<div class='buttonarea'>";
echo "<input type='button' onclick='location.href=\"index.php?stage=otheroptions&session=$session\"' class='button' value='Back'> ";
echo "<input type='button' onclick='
	if(confirm(\"Are you sure you do not want to work with this text any longer?\")) { 
		location.href=\"index.php?stage=input\";
	}' class='button' value='Check new text'></div>";

} else {
	fallback("index.php");
}
?>
