<?php
// forbid to open this file directly from the browser
if (preg_match("/logic.readability.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// вводим класс для подсчета статистики удобочитаемости
require_once "readability.php";
$statistics = new TextStatistics;

if (isset($_POST['gunning_fog']) || isset($_POST['smog']) || isset($_POST['showstats']) || isset($_POST['longwords']) || isset($_POST['fwords'])) {

	// проверяем чекбокс собственных имен существительных (флаг отмечен - исключить их из статистики, false)
	$blnCountProperNouns = (isset($_POST['proper']))?true:false;
	
	// обновляем информацию о каждом слове
	$stmt = $db_conn->prepare("SELECT word FROM tempw WHERE session='".$session."'");
	$stmt -> execute();
	while ($word = $stmt->fetch()) {
		// if the word has more than 3 syllables, mark it as long
		$long = $statistics->word_with_three_syllables($word[0], $blnCountProperNouns);

		// check if the word in the list of the most common words
		$common = false;
		if ($long == true && isset($_POST['common'])) {
			$sth = $db_conn->prepare("SELECT id FROM commonw WHERE word='".$word[0]."'");
			$sth->execute();
			$comid = $sth->fetchColumn();
			if ($comid) {
				$long = false;
				$common = true;
			}
		}
		
		$formal = false; 
		// check if the word is formal
		$sth = $db_conn->prepare("SELECT id FROM formalw WHERE word='".$word[0]."'");
		$sth->execute();
		$formalid = $sth->fetchColumn();
		if ($formalid) $formal = true;
		
		// Updating DB records, nigga...
		$stmt2 = $db_conn->prepare("UPDATE tempw SET longw=:long, formalw=:formalw, commonw=:commonw WHERE word=:word AND session=:session");
		$stmt2->bindParam(':long', $long);
		$stmt2->bindParam(':formalw', $formal);
		$stmt2->bindParam(':commonw', $common);
		$stmt2->bindParam(':word', $word[0]);
		$stmt2->bindParam(':session', $session);
		$stmt2->execute();
	}
	
}

// updating statistics if it doesn't exist and the user wanted to show it
if (isset($_POST['showstats'])) {
		// let's check if the statistics exists
		$sth = $db_conn->prepare("SELECT fog,smog,sylaverage,syltotal,woraverage,longwnumber,longwpercent FROM tempt WHERE session='".$session."'");
		$sth->execute();
		$result = $sth->fetch();
			if ($result[2] == 0) $result[2] = $statistics->average_syllables_per_word($session);
			if ($result[3] == 0) $result[3] = $statistics->total_syllables($session);
			if ($result[4] == 0) $result[4] = $statistics->average_words_per_sentence($session);
			if ($result[5] == 0) $result[5] = $statistics->words_with_three_syllables($session);
			if ($result[6] == 0) $result[6] = $statistics->percentage_words_with_three_syllables($session);
		// update DB text stats (tempt)
		$sql1 = "UPDATE tempt SET sylaverage=?,syltotal=?,woraverage=?,longwnumber=?,longwpercent=? WHERE session=?";
		$db_upd1 = $db_conn->prepare($sql1);
		$db_upd1->execute(array($result[2],$result[3],$result[4],$result[5],$result[6],$session));
}

// If at least one readability test is chosen
if (isset($_POST['reading_ease']) || isset($_POST['grade_level']) || isset($_POST['gunning_fog']) || isset($_POST['coleman']) || isset($_POST['smog']) || isset($_POST['automated_readability'])) {
	
	// updating viewing options (in general)
	$db_conn->exec("UPDATE sessions SET showopt=INSERT(showopt,1,1,'1') WHERE id='".$session."'");
	
	// Define variables as empty
	$flesch1 = 0;
	$flesch2 = 0;
	$fog = 0;
	$coleman = 0;
	$smog = 0;
	$automated = 0;
	
	// if checked count formal words as long ones
	$formalcount = (isset($_POST['formal']))?true:false;

	// Flesch-Kincaid Reading Ease
	if (isset($_POST['reading_ease'])) {
		$flesch1 = $statistics->flesch_kincaid_reading_ease($session);
		$ease = 1;
	} else {
		$ease = 0;
	}
				
	// Flesch-Kincaid Grade Level
	if (isset($_POST['grade_level'])) {
		$flesch2 = $statistics->flesch_kincaid_grade_level($session);
		$level = 1;
	} else {
		$level = 0;
	}

	// Gunning Fog Score
	if (isset($_POST['gunning_fog'])) {
		$fog = $statistics->gunning_fog_score($session,$formalcount);
		$fog2 = 1;
	} else {
		$fog2 = 0;
	}
				
	// Coleman-Liau index
	if (isset($_POST['coleman'])) {
		$coleman = $statistics->coleman_liau_index($session);
		$coleman2 = 1;
	} else {
		$coleman2 = 0;
	}
				
	// SMOG index
	if (isset($_POST['smog'])) {
		$smog = $statistics->smog_index($session,$formalcount);
		$smog2 = 1;
	} else {
		$smog2 = 0;
	}
				
	// Automated readability index
	if (isset($_POST['automated_readability'])) {
		$automated = $statistics->automated_readability_index($session);
		$automated2 = 1;
	} else {
		$automated2 = 0;
	}
	
	// updating readability viewing options
	$db_conn->exec("UPDATE sessions SET showopt2='".$ease.$level.$fog2.$coleman2.$smog2.$automated2."' WHERE id='".$session."'");

	// update DB text stats (tempt)
	$sql = "UPDATE tempt SET flesch1=?, flesch2=?, fog=?, coleman=?, smog=?, automated=? WHERE session=?";
	$db_upd = $db_conn->prepare($sql);
	$db_upd->execute(array($flesch1,$flesch2,$fog,$coleman,$smog,$automated,$session));
	
} elseif (!isset($_POST['showstats']) && !isset($_POST['textshow'])) {
	// updating viewing options (in general)
	$db_conn->exec("UPDATE sessions SET showopt=INSERT(showopt,1,1,'0') WHERE id='".$session."'");
	$db_conn->exec("UPDATE sessions SET showopt2='000000' WHERE id='".$session."'");
}

// Stage now 1
//$db_conn->exec("UPDATE sessions SET stage='1' WHERE id='".$session."'");