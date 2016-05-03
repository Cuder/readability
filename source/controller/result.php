<?php
if (isset($session) && checkSession($session) == true) {

	$smarty->assign('resultsTitle',$textStrings->captions->caption[4]);

	// Selecting showing options
	$sth = $db_conn->prepare("SELECT showopt FROM sessions WHERE id='" . $session . "'");
	$sth->execute();
	$showopt = $sth->fetchColumn();

	if (substr($showopt, 0, 3) != "000") {

		// If the user wanted to show him readability scores
		if ($showopt[0] == 1) {

			$smarty->assign('rdbCaption',$textStrings->captions->caption[5]);

			// Selecting readability showing options
			$sth = $db_conn->prepare("SELECT showopt2 FROM sessions WHERE id='" . $session . "'");
			$sth->execute();
			$showopt2 = $sth->fetchColumn();

			$smarty->assign('showopt2', $showopt2);

			$sth = $db_conn->prepare("SELECT flesch1,flesch2,fog,coleman,smog,automated FROM tempt WHERE session='" . $session . "'");
			$sth->execute();
			$result = $sth->fetch();

			for ($i = 0; $i < 6; $i++) {
				if ($showopt2[$i] == 1) {
					if ($i == 0) {
						$percent = $result[$i];
						$rdbLimit[$i] = 100;
						$rdbTooltip[$i] = $percent . "/100::".$textStrings->tooltips->tooltip[8];
					} else {
						switch (true) {
							case ($result[$i] > 20):
								$percent = 0;
								break;
							case ($result[$i] < 0):
								$percent = 100;
								break;
							default:
								$percent = 100-round(100*$result[$i]/20);
						}
						$usgrade = round($result[$i]);
						switch ($usgrade) {
							case 1:
								$numeral = $textStrings->numerals->numeral[0];
								$age = "6-7";
								break;
							case 2:
								$numeral = $textStrings->numerals->numeral[1];
								$age = "7-8";
								break;
							case 3:
								$numeral = $textStrings->numerals->numeral[2];
								$age = "8-9";
								break;
							case 4:
								$age = "9-10";
								break;
							case 5:
								$age = "10-11";
								break;
							case 6:
								$age = "11-12";
								break;
							case 7:
								$age = "12-13";
								break;
							case 8:
								$age = "13-14";
								break;
							case 9:
								$age = "14-15";
								break;
							case 10:
								$age = "15-16";
								break;
							case 11:
								$age = "16-17";
								break;
							case 12:
								$age = "17-18";
								break;
							default:
								if ($usgrade < 1) $byWhom = $textStrings->tooltips->tooltip[10];
								if ($usgrade > 12) $byWhom = $textStrings->tooltips->tooltip[11];
						}
						if ($usgrade > 3 && $usgrade < 13) $numeral = $usgrade . "th";
						if ($usgrade > 0 && $usgrade < 13) {
							$byWhom = preg_replace('/\$numeral/',$numeral,$textStrings->tooltips->tooltip[12]);
							$byWhom = preg_replace('/\$age/',$age,$byWhom);
						}
						$tooltip = preg_replace('/\$score/',$result[$i],$textStrings->tooltips->tooltip[9]);
						$rdbTooltip[$i] = $result[$i]."::".$tooltip.$byWhom;
						$rdbLimit[$i] = 20;
					}
					$rdbTitle[$i] = $textStrings->readability->test[$i];
					$rdbPercent[$i] = $percent;
					$rdbTd[$i] = 100 - $percent;

					switch (true) {
						case ($percent < 0 || ($percent >= 0 && $percent < 20)):
							$color = "red";
							break;
						case ($percent >= 20 && $percent < 50):
							$color = "ff7200";
							break;
						case ($percent >= 50 && $percent < 80):
							$color = "f7c21d";
							break;
						case ($percent >= 80 && $percent <= 100):
							$color = "green";
							break;
					}
					$rdbColor[$i] = $color;
				}
			}
			$smarty->assign('rdbTitle', $rdbTitle);
			$smarty->assign('rdbTooltip', $rdbTooltip);
			$smarty->assign('result', $result);
			$smarty->assign('percent', $rdbPercent);
			$smarty->assign('limit', $rdbLimit);
			$smarty->assign('td', $rdbTd);
			$smarty->assign('color', $rdbColor);
		}

		// if the user wanted to show him his text
		if ($showopt[2] == 1) {
			$smarty->assign('rdbText',$textStrings->captions->caption[6]);
			// check marking options
			if ($showopt['3'] == 1 || $showopt['4'] == 1) {
				// selecting marked text from the DB
				$sth = $db_conn->prepare("SELECT marked FROM tempt WHERE session='" . $session . "'");
			} else {
				// selecting raw text from the DB
				$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='" . $session . "'");
			}
			$sth->execute();
			$text = $sth->fetchColumn();
			$smarty->assign('text', $text);
		}

		// If the user wanted to show him text statistics
		if ($showopt[1] == 1) {
			$smarty->assign('rdbStatistics',$textStrings->captions->caption[7]);
			$smarty->assign('statisticsStrings',$textStrings->statistics->statistical);
			$smarty->assign('statisticsTooltip',$textStrings->tooltips->tooltip[13]);

			// Selecting text statistics from the DB
			$sth = $db_conn->prepare("SELECT sylaverage,syltotal,woraverage,wortotal,longwnumber,longwpercent,sentences FROM tempt WHERE session='" . $session . "'");
			$sth->execute();
			$statistics = $sth->fetch();
			$smarty->assign('statistics', $statistics);

			require_once $rootdir."/libs/functionwords.php";
			$smarty->assign('popularWords', $popularWords);
		}

		$smarty->assign('showopt', $showopt);
		$smarty->assign('saveResults',$textStrings->captions->caption[8]);

		$download = $textStrings->results->result[0];
		$download = preg_replace('/'.$download['linkw'].'/','<a href="'.$session.'/getrtf" target="_blank">'.$download['linkw'].'</a>',$download);
		$smarty->assign('download', $download);

		$smarty->assign('download2', $textStrings->results->result[1]);
		$smarty->assign('download3', $textStrings->results->result[2]);

	} else {
		$smarty->assign('nothingToShow',$textStrings->captions->caption[9]);
		$smarty->assign('nothingToShow2', $textStrings->results->result[3]);
	}

	// Buttons 'Back' & 'Check new text'
	$smarty->assign('buttons',array(
		$textStrings->buttons->button[4],
		$textStrings->buttons->button[5]
	));
	$smarty->assign('prompt', $textStrings->prompts->prompt[0]);

} else {
	fallback();
}
