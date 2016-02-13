<?php
require_once "core.php";

// подсоединение к бд
require_once "db.php";

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

	// including RTF class
	require_once "rtf/lib/PHPRtfLite.php";
	PHPRtfLite::registerAutoloader();

	// RTF new document instance
	$rtf = new PHPRtfLite();

	// add RTF section
	$sect = $rtf->addSection();

	//write down RTF sections properties
	$parFormatTitle = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER);
	$parFormat = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT);
	$parFormatText = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_JUSTIFY);

	// write down RTF font properties
	$title_font = new PHPRtfLite_Font(25, 'Garamond');
	$font = new PHPRtfLite_Font(12, 'Garamond');
	$font_long = new PHPRtfLite_Font(12, 'Garamond', '#0000FF');
	$font_formal = new PHPRtfLite_Font(12, 'Garamond', '#078B07');
	$font_extr = new PHPRtfLite_Font(12, 'Garamond', '#FF0000');

	$font_small = new PHPRtfLite_Font(10, 'Garamond');
	$font_long_small = new PHPRtfLite_Font(10, 'Garamond', '#0000FF');
	$font_formal_small = new PHPRtfLite_Font(10, 'Garamond', '#078B07');
	$font_extr_small = new PHPRtfLite_Font(10, 'Garamond', '#FF0000');

	// RTF doc logo
	$sect->writeText('Readability results<br>', $title_font, $parFormatTitle);

// if the user wanted to show him readability scores
if ($showopt['0'] == 1) {

	// write RDB tests results in RTF
	$sect->writeText('<b>Readability tests</b><br>', $font, $parFormat);

	// selecting readability showin' options
	$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='".$session."'");
	$sth->execute();
	$showopt2 = $sth->fetchColumn();

	$sth = $db_conn->prepare("SELECT flesch1,flesch2,fog,coleman,smog,automated FROM tempt WHERE session='".$session."'");
	$sth->execute();
	$result = $sth->fetch();

	// Flesch-Kincaid Reading Ease
	if ($showopt2['0'] == 1) {
		$sect->writeText('Flesch-Kincaid Reading Ease: '.$result[0].'/100<br>', $font_small, null);
	}

	// Flesch-Kincaid Grade Level
	if ($showopt2['1'] == 1) {
		$sect->writeText('Flesch-Kincaid Grade Level: '.$result[1].'<br>', $font_small, null);
	}

	// Gunning Fog Score
	if ($showopt2['2'] == 1) {
		$sect->writeText('Gunning Fog Score: '.$result[2].'<br>', $font_small, null);
	}

	// Coleman-Liau index
	if ($showopt2['3'] == 1) {
		$sect->writeText('Coleman-Liau index: '.$result[3].'<br>', $font_small, null);
	}

	// SMOG index
	if ($showopt2['4'] == 1) {
		$sect->writeText('SMOG index: '.$result[4].'<br>', $font_small, null);
	}

	// Automated readability index
	if ($showopt2['5'] == 1) {
		$sect->writeText('Automated readability index: '.$result[5].'<br>', $font_small, null);
	}
}

// if the user wanted to show him text statistics
if ($showopt['1'] == 1) {
	// selecting text statistics from the DB
	$sth = $db_conn->prepare("SELECT sylaverage,syltotal,woraverage,wortotal,longwnumber,longwpercent,sentences FROM tempt WHERE session='".$session."'");
	$sth->execute();
	$result = $sth->fetch();

	// write text statistics
	$sect->writeText('<br><b>Text statistics</b><br>', $font, null);

	$sect->writeText('Number of sentences: '.$result[6].'<br>', $font_small, null);
	$sect->writeText('Syllables: '.$result[1].' (total), '.$result[0].' (average, per word)<br>', $font_small, null);
	$sect->writeText('Words: '.$result[3].' (total), '.$result[2].' (average, per sentence)<br>', $font_small, null);
	$sect->writeText('Words with three or more syllables: '.$result[4].' (total), '.$result[5].'% (of all words)<br>', $font_small, null);

	// 10 most used words
	$sect->writeText('10 most used words: ', $font_small, null);

	require_once "functionwords.php";

	$stmt = $db_conn->prepare("SELECT word,count FROM tempw WHERE word NOT IN ($array) AND session='".$session."' ORDER BY count DESC LIMIT 10");
	$stmt -> execute();
	$i = 0;
	while ($word = $stmt->fetch()) {
			$sect->writeText('<i>'.$word[0].'</i> ('.$word[1].')', $font_small, null);
			if ($i==9) { $sect->writeText('.', $font_small, null); } else { $sect->writeText(', ', $font_small, null); }
			$i++;
	}
}

// if the user wanted to show him his text
if ($showopt['2'] == 1) {

	// check marking options
	if ($showopt['3'] == 1 || $showopt['4'] == 1) {
		// selecting marked text from the DB
		$sth = $db_conn->prepare("SELECT marked FROM tempt WHERE session='".$session."'");
		$rtfmark = true;
	} else {
		// selecting raw text from the DB
		$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
		$rtfmark = false;
	}
	$sth->execute();
	$text = $sth->fetchColumn();

	// printing text
	$sect->writeText('<br><b>Your text</b>', $font, $parFormat);

	if ($rtfmark) {
		require_once "logic.textmark.rtf.php";
	} else {
		$sect->writeText($text, $font, $parFormatText);
		$sect->writeText(' ', $font, $parFormat);
	}
}

    // printing RTF page footer
	$now = date("F j, Y, g:i a");
	$date = date("Y-m-d H:i:s");
    $footer = $sect->addFooter();
    $footer->writeText('Generated by ', $font_small, $parFormatTitle);
    $footer->writeHyperLink('http://nikitakovin.ru/helper/', 'Readability Checker', $font_small);
    $footer->writeText(" on ".$now, $font_small);

    // save rtf document to server
	//$rtf->save('rtf/results/readability_'.$session.'.rtf');

	// download!
	$rtf->sendRtf('readability_'.$date.'.rtf');

} else {
	// no showing option is selected
	fallback("index.php");
}

} else {
	fallback("index.php");
}