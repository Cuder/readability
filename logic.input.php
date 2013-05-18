<?php
// forbid to open this file directly from the browser
if (preg_match("/logic.input.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// now let's check whether the text is already in the DB (prevent overflood)
$sth = $db_conn->prepare("SELECT session FROM tempt WHERE raw='".trim($_POST['text'])."'");
$sth->execute();
$session2 = $sth->fetchColumn();

if ($session2)	{
	if (isset($session) && $session2 == $session) fallback("index.php?stage=readability&session=$session");
	
	$sth = $db_conn->prepare("SELECT INET_NTOA(ip),stage FROM sessions WHERE id='".$session2."'");
	$sth->execute();
	$result = $sth->fetch();
	
	// let's check if this session (i.e. text) belongs to the same guy
	if ($result['0'] == $_SERVER['REMOTE_ADDR']) {
			// the text is complete? (are there all the results?)
			//if ($result['1'] > 0) {
				//// тут будет перекидывать на нужную ступень...
				//echo "Choose the stage...<br>";
				//$proceed = false;
			//} else {
				// Присвоить сессию того же текста, не создавать новую
				// Finish the script, go to readability (skip all below)
				fallback("index.php?stage=readability&session=$session2");
			//}
	} else {
		// there's such text, but evidently someone else has typed it into the DB.
		// New session should be created
		// Proceed as usual...
		$proceed = true;
	}
} else {
	// there's no such text in the DB, everything's fine.
	// Proceed as usual...
	$proceed = true;
}

if ($proceed) {
	
// вводим переменную для обработанного текста
$output_text = "";

if (isset($session)) {
	$db_conn->exec("DELETE FROM tempw WHERE session='".$session."'");
	$db_conn->exec("DELETE FROM tempt WHERE session='".$session."'");
	// здесь, возможно, сброс stage на нуль
	//
}

// переводим введенный текст в переменную "сырого" текста
$text = trim($_POST['text']);	
				
// вводим класс для автоматического определения введенного языка
require_once "langdetect.php";
$detect_lang = new Lang_Auto_Detect();

// проверим, какой язык был введен пользователем
$language = $detect_lang->lang_detect($text);
if (($language == false) || ($language == null)) fallback("index.php?error=lang1");
if ($language[1][0] == "Russian") fallback("index.php?error=lang2");
	
// очистка текста от скверны
require_once "classes.php";
$output_text = clean_text($text);

// вводим класс для подсчета статистики удобочитаемости
require_once "readability.php";
$statistics = new TextStatistics;

// проверка на количество введеных слов
$intWordCount = $statistics->word_count_raw($output_text);
if ($intWordCount < $minwords) fallback("index.php?error=text1");

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
}

// now i check each word of the input text array
$arrWords = explode(' ', $output_text);
for ($i = 0; $i < $intWordCount; $i++) {     
	
	// слово жуткое длинное!
	if (strlen($arrWords[$i]) > $longestw) {
		// очищение темповой таблицы
		$db_conn->exec("DELETE FROM tempw WHERE session='".$session."'");
		$db_conn->exec("DELETE FROM sessions WHERE id='".$session."'");
		fallback("index.php?error=text2");
		break;
	}
	
	// if the word is a space - ignore it
	if ($arrWords[$i] != "") {
											
		// если слово обрывается точкой, убрать ее
		if (substr($arrWords[$i], -1) == ".") $arrWords[$i] = substr($arrWords[$i],0,-1);
								
		$sth = $db_conn->prepare("SELECT count FROM tempw WHERE word='".$arrWords[$i]."' AND session='".$session."'");
		$sth->execute();
		$tcount = $sth->fetchColumn();	
			// if the word already exists in the temp table, update the counter plus one
			if ($tcount > 0) {
					$tcount = $tcount+1;		
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

fallback("index.php?stage=readability&session=$session");
}
?>
