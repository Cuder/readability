<?php
if (isset($session) && checkSession($session) == true) {

	// Selecting showing options
	$sth = $db_conn->prepare("SELECT showopt FROM sessions WHERE id='".$session."'");
	$sth->execute();
	$showopt = $sth->fetchColumn();

	if (substr($showopt,0,3) != "000") {

		// Connecting to the PHPRtfLite library
		require_once $rootdir."/libs/PHPRtfLite/PHPRtfLite.php";
		PHPRtfLite::registerAutoloader();

		// Creating a new RTF document instance
		$rtf = new PHPRtfLite();

		// Adding an RTF section
		$sect = $rtf->addSection();

		// Writing down RTF sections properties
		$parFormatTitle = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER);
		$parFormat = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT);
		$parFormatText = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_JUSTIFY);

		// Writing down RTF font properties
		$title_font = new PHPRtfLite_Font(25, 'Garamond');
		$font = new PHPRtfLite_Font(12, 'Garamond');
		$font_long = new PHPRtfLite_Font(12, 'Garamond', '#0000FF');
		$font_formal = new PHPRtfLite_Font(12, 'Garamond', '#078B07');
		$font_extr = new PHPRtfLite_Font(12, 'Garamond', '#FF0000');

		$font_small = new PHPRtfLite_Font(10, 'Garamond');
		$font_long_small = new PHPRtfLite_Font(10, 'Garamond', '#0000FF');
		$font_formal_small = new PHPRtfLite_Font(10, 'Garamond', '#078B07');
		$font_extr_small = new PHPRtfLite_Font(10, 'Garamond', '#FF0000');

		// Writing down the RTF doc logo
		$text = $textStrings->captions->caption[4];
		$sect->writeText($text.'<br>', $title_font, $parFormatTitle);

		// If the user wanted to show him readability scores
		if ($showopt['0'] == 1) {

			// Writing down RDB tests results in RTF
			$text = $textStrings->captions->caption[5];
			$sect->writeText('<b>'.$text.'</b><br>', $font, $parFormat);

			// Selecting readability showing options
			$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='".$session."'");
			$sth->execute();
			$showopt2 = $sth->fetchColumn();

			$sth = $db_conn->prepare("SELECT flesch1,flesch2,fog,coleman,smog,automated FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$result = $sth->fetch();

			for ($i = 0; $i < 6; $i++) {
				if ($showopt2[$i] == 1) {
					$rdbTitle[$i] = $textStrings->readability->test[$i];
					$percent = ($i==0)?"/100":"";
					$sect->writeText($rdbTitle[$i].': '.$result[$i].$percent.'<br>', $font_small, null);
				}
			}

		}

		// If the user wanted to show him text statistics
		if ($showopt['1'] == 1) {

			// Selecting text statistics from the DB
			$sth = $db_conn->prepare("SELECT sylaverage,syltotal,woraverage,wortotal,longwnumber,longwpercent,sentences FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$result = $sth->fetch();

			// Writing text statistics
			$text = $textStrings->captions->caption[7];
			$sect->writeText('<br><b>'.$text.'</b><br>', $font, null);

			$statisticsStrings = $textStrings->statistics->statistical;

			$sect->writeText($statisticsStrings[0].': '.$result[6].'<br>', $font_small, null);
			$sect->writeText($statisticsStrings[1].': '.$result[1].' ('.$statisticsStrings[4].'), '.$result[0].' ('.$statisticsStrings[5].')<br>', $font_small, null);
			$sect->writeText($statisticsStrings[2].': '.$result[3].' ('.$statisticsStrings[4].'), '.$result[2].' ('.$statisticsStrings[6].')<br>', $font_small, null);
			$sect->writeText($statisticsStrings[3].': '.$result[4].' ('.$statisticsStrings[4].'), '.$result[5].'% ('.$statisticsStrings[7].')<br>', $font_small, null);

			// 10 most used words
			$sect->writeText($statisticsStrings[8].': ', $font_small, null);
			require_once $rootdir."/libs/functionwords.php";
			$sect->writeText($popularWords, $font_small, null);

		}

		// If the user wanted to show him his text
		if ($showopt['2'] == 1) {

			// Checking marking options
			if ($showopt['3'] == 1 || $showopt['4'] == 1) {
				// Selecting marked text from the DB
				$sth = $db_conn->prepare("SELECT marked FROM tempt WHERE session='".$session."'");
				$rtfmark = true;
			} else {
				// Selecting raw text from the DB
				$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
				$rtfmark = false;
			}
			$sth->execute();
			$text = $sth->fetchColumn();

			// Printing text
			$textTitle = $textStrings->captions->caption[6];
			$sect->writeText('<br><b>'.$textTitle.'</b>', $font, $parFormat);

			if ($rtfmark) {
				require_once $rootdir."/model/textmark.rtf.php";
			} else {
				$sect->writeText($text, $font, $parFormatText);
				$sect->writeText(' ', $font, $parFormat);
			}
		}

		// Printing RTF page footer
		$now = date("F j, Y, g:i a");
		$date = date("Y-m-d H:i:s");
		$footer = $sect->addFooter();
		$text = $textStrings->commonStrings->commonString[6];
		$footer->writeText($text, $font_small, $parFormatTitle);
		$text = $textStrings->commonStrings->commonString[0];
		$footer->writeHyperLink('http://'.$_SERVER['HTTP_HOST'].'/', $text, $font_small);
		$text = $textStrings->commonStrings->commonString[7];
		$footer->writeText($text.$now, $font_small);

		// Saving RTF document to server
		//$rtf->save('rtf/results/readability_'.$session.'.rtf');

		// Download!
		$rtf->sendRtf('readability_'.$date.'.rtf');

	} else {
		// No showing option was selected
		fallback();
	}

} else {
	fallback();
}
