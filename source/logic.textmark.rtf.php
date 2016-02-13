<?php
// forbid to open this file directly from the browser
if (preg_match("/logic.textmark.rtf.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// defining patterns for text highlighting
	$patterns = array();
	$patterns[0] = "!<font style=\"color:blue\" title=\"::The word is long\">(.*?)</font>!si";
	$patterns[1] = "!<font style=\"color:green\" title=\"::The word is formal\">(.*?)</font>!si";
	$patterns[2] = "!<font style=\"color:red\" title=\"::The word is long. More than that, it is formal\">(.*?)</font>!si";
   
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
			
				if (preg_match("!<font style=\"color:green\" title=\"::The word is formal\">(.*?)</font>!si", $text[$i])) {
				
				 $greenText = preg_split($patterns[1], $text[$i], -1, PREG_SPLIT_DELIM_CAPTURE);
				 $textArrayGreen = count($greenText);
				 for ($iG=0; $iG<$textArrayGreen; $iG++) {
					 if (!($iG % 2)) {
						 if (preg_match("!<font style=\"color:red\" title=\"::The word is long. More than that, it is formal\">(.*?)</font>!si", $greenText[$iG])) {
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
			
			} elseif (preg_match("!<font style=\"color:red\" title=\"::The word is long. More than that, it is formal\">(.*?)</font>!si", $text[$i])) {
				
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
   
    $sect->writeText('<br><i>Legend:</i>', $font_small, $parFormat);   
    if ($showopt['3'] == 1) $sect->writeText('<br>words with three or more syllables ', $font_long_small, null);
    if ($showopt['4'] == 1) $sect->writeText('<br>formal words ', $font_formal_small, null);
    if ($showopt['3'] == 1 && $showopt['4'] == 1) $sect->writeText('<br>formal words with three or more syllables ', $font_extr_small, null);