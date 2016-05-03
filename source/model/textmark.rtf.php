<?php
// Defining patterns for text highlighting
$patterns = array(
	"!<div style=\"color:blue;display:inline-block;\" title=\"::".$textStrings->tooltips->tooltip[15]."\">(.*?)</div>!si",
	"!<div style=\"color:green;display:inline-block;\" title=\"::".$textStrings->tooltips->tooltip[16]."\">(.*?)</div>!si",
	"!<div style=\"color:red;display:inline-block;\" title=\"::".$textStrings->tooltips->tooltip[14]."\">(.*?)</div>!si"
);

$text = preg_split($patterns[0], $text, -1, PREG_SPLIT_DELIM_CAPTURE);
$textArray = count($text);
$iStart = ($textArray == 1)?0:1;
if ($textArray > 1) {
	$sect->writeText($text[0], $font, $parFormatText);
} else {
	$sect->writeText("", $font, $parFormatText);
}
for ($i = $iStart; $i < $textArray; $i++) {
	if (!($i % 2)) {

		if (preg_match($patterns[1], $text[$i])) {

			$greenText = preg_split($patterns[1], $text[$i], -1, PREG_SPLIT_DELIM_CAPTURE);
			$textArrayGreen = count($greenText);
			for ($iG=0; $iG<$textArrayGreen; $iG++) {
				if (!($iG % 2)) {
					if (preg_match($patterns[2], $greenText[$iG])) {
						$greenRedText = preg_split($patterns[2], $greenText[$iG], -1, PREG_SPLIT_DELIM_CAPTURE);
						$textArrayGreenRed = count($greenRedText);
						if ($greenRedText[0] != "") $sect->writeText($greenRedText[0], $font, null);
						for ($iGR = 1; $iGR < $textArrayGreenRed; $iGR++) {
							if (!($iGR % 2)) {
								$sect->writeText($greenRedText[$iGR], $font, null);
							} else {
								$sect->writeText($greenRedText[$iGR], $font_extr, null);
							}
						}
					} else {
						$sect->writeText($greenText[$iG], $font, null);
					}

				} else {
					$sect->writeText($greenText[$iG], $font_formal, null);
				}

			}

		} elseif (preg_match($patterns[2], $text[$i])) {

			$redText = preg_split($patterns[2], $text[$i], -1, PREG_SPLIT_DELIM_CAPTURE);
			$textArrayRed = count($redText);
			if ($redText[0] != "") $sect->writeText($redText[0], $font, null);
			for ($iR = 1; $iR < $textArrayRed; $iR++) {
				if (!($iR % 2)) {
					$sect->writeText($redText[$iR], $font, null);
				} else {
					$sect->writeText($redText[$iR], $font_extr, null);
				}
			}

		} else {
			$sect->writeText($text[$i], $font, null);
		}

	} else {
		$sect->writeText($text[$i], $font_long, null);
	}
}

$sect->writeText('<br><i>'.$textStrings->captions->caption[10].':</i>', $font_small, $parFormat);
if ($showopt['3'] == 1) $sect->writeText('<br>'.strtolower($textStrings->statistics->statistical[3]).' ', $font_long_small, null);
if ($showopt['4'] == 1) $sect->writeText('<br>'.$textStrings->statistics->statistical[9], $font_formal_small, null);
if ($showopt['3'] == 1 && $showopt['4'] == 1) $sect->writeText('<br>'.$textStrings->statistics->statistical[10], $font_extr_small, null);
