<?php
// Checking if the text does not exceed max allowed characters (just in case JS didn't work)
if (strlen($_POST['text']) > $maxsymbols) {
	$_SESSION['errorCode'] = "toolarge";
	fallback("error");
}

// Checking if the text is already in the DB (preventing overflood)
$sth = $db_conn->prepare("SELECT session FROM tempt WHERE raw='".trim($_POST['text'])."'");
$sth->execute();
$session2 = $sth->fetchColumn();
if ($session2)	{
	if (isset($session) && $session2 == $session) fallback($session."/readability");
	// Let's check if this session (i.e. text) belongs to the same guy
	$sth = $db_conn->prepare("SELECT INET_NTOA(ip) FROM sessions WHERE id='".$session2."'");
	$sth->execute();
	$ip = $sth->fetchColumn();
	if ($ip == $_SERVER['REMOTE_ADDR']) fallback($session2."/readability");
}

// вводим переменную для обработанного текста
$output_text = "";

if (isset($session)) {
	$db_conn->exec("DELETE tempw,tempt FROM tempw INNER JOIN tempt WHERE tempw.session=tempt.session AND tempt.session='".$session."'");
	// здесь, возможно, сброс stage на нуль
	//
}

// переводим введенный текст в переменную "сырого" текста
$text = trim($_POST['text']);	
				
// Detecting language
require_once $rootdir."/model/languageDetection.php";

// очистка текста от скверны
require_once $rootdir."/libs/cleantext.php";
$output_text = clean_text($text);

// вводим класс для подсчета статистики удобочитаемости
require_once $rootdir."/libs/TextStatistics.php";
$statistics = new TextStatistics;

// проверка на количество введеных слов
$intWordCount = $statistics->word_count_raw($output_text);
if ($intWordCount < $minwords) {
	if (isset($session)) $db_conn->exec("DELETE FROM sessions WHERE id='".$session."'");
	$_SESSION['errorCode'] = "smalltext";
	fallback("error");
}

if (!isset($session)) {
	// creating temp db session id
	$session = "1";
	while ($session) {
		$uid = rand(10000,99999);
		$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$uid."'");
		$sth->execute();
		$session = $sth->fetchColumn();
	}
	$session = $uid;
	$newsession = true;
} else {
	$sth = $db_conn->prepare("SELECT id FROM sessions WHERE id='".$session."' AND ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')");
	$sth->execute();
	$id = $sth->fetchColumn();
	if ($id != $session) $newsession = true;
}

// now i check each word of the input text array
$arrWords = explode(' ', $output_text);
for ($i = 0; $i < $intWordCount; $i++) {     
	
	// слово жуткое длинное!
	if (strlen($arrWords[$i]) > $longestw) {
		// очищение темповой таблицы
		$db_conn->exec("DELETE tempw,tempt FROM tempw INNER JOIN tempt WHERE tempw.session=tempt.session AND tempt.session='".$session."'");
		if (isset($session)) $db_conn->exec("DELETE FROM sessions WHERE id='".$session."'");
		$_SESSION['errorCode'] = "rubbish";
		fallback("error");
		break;
	}
	
	// if the word is a space - ignore it
	if ($arrWords[$i] != "" && $arrWords[$i] != "--") {
											
		// если слово обрывается точкой, убрать ее
		if (substr($arrWords[$i], -1) == ".") $arrWords[$i] = substr($arrWords[$i],0,-1);
								
		$sth = $db_conn->prepare("SELECT count FROM tempw WHERE word='".$arrWords[$i]."' AND session='".$session."'");
		$sth->execute();
		$tcount = $sth->fetchColumn();	
			// if the word already exists in the temp table, update the counter plus one
			if ($tcount > 0) {
					$tcount++;
					$stmt = $db_conn->prepare("UPDATE tempw SET count =:count WHERE word=:word AND session=:session");
					$stmt->bindParam(':count', $tcount);
					$stmt->bindParam(':word', $arrWords[$i]);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
			// else insert a new record			
			} else {
					$stmt = $db_conn->prepare("INSERT INTO tempw (word,session) VALUES (:word,:session)");
					$stmt->bindParam(':word', $arrWords[$i]);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
			}
	}
}

if ($newsession) {
	// insert new record into the table sessions
	$ip = $_SERVER['REMOTE_ADDR'];
	$stmt = $db_conn->prepare("INSERT INTO sessions (id,ip) VALUES (:id,INET_ATON(:ip))");
	$stmt->bindParam(':id', $session);
	$stmt->bindParam(':ip', $ip);
	$stmt->execute();
} else {
	// the result is not ready!
	$db_conn->exec("UPDATE sessions SET showopt=INSERT(showopt,1,1,'0'), showopt2='000000', stage='0' WHERE id='".$session."'");
}

// insert into the table tempt raw and cleaned texts and the total number of words
$stmt = $db_conn->prepare("INSERT INTO tempt (raw,cleaned,wortotal,session) VALUES (:raw,:cleaned,:wortotal,:session)");
$stmt->bindParam(':raw', $text);
$stmt->bindParam(':cleaned', $output_text);
$stmt->bindParam(':wortotal', $intWordCount);
$stmt->bindParam(':session', $session);
$stmt->execute();
