<?php
// forbid to open this file directly from the browser
if (preg_match("/readability.output.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// вводим функцию для преобразования процента в цвет
function percent2color ($percent) {
		if ($percent<0) $percent=0;
		if ($percent>=0 && $percent<20) $color = "red";
		if ($percent>=20 && $percent<50) $color = "ff7200";
		if ($percent>=50 && $percent<80) $color = "f7c21d";
		if ($percent>=80 && $percent<=100) $color = "green";
		return $color;
}
// вводим функцию для преобразования US grade level в цвет
function grade2color ($grade) {
		if ($grade>20) $grade=20;
		if ($grade<0) $grade=0;
		global $grade_percent;
		$grade_percent = 100-round(100*$grade/20);
		$color = percent2color($grade_percent);
		return $color;
}
// вводим функцию для вывода подсказки
function grade2title ($grade) {
	$usgrade = round($grade);
				if ($usgrade == 1) { 
					$usgrade = "1st";
					$age = "6-7";
					}
				if ($usgrade == 2) { 
					$usgrade = "2nd";
					$age = "7-8";
					}
				if ($usgrade == 3) { 
					$usgrade = "3rd";
					$age = "8-9";
					}
				if ($usgrade == 4) { 
					$age = "9-10";
				}
				if ($usgrade == 5) { 
					$age = "10-11";
				}
				if ($usgrade == 6) { 
					$age = "11-12";
				}
				if ($usgrade == 7) { 
					$age = "12-13";
				}
				if ($usgrade == 8) { 
					$age = "13-14";
				}
				if ($usgrade == 9) { 
					$age = "14-15";
				}
				if ($usgrade == 10) { 
					$age = "15-16";
				}
				if ($usgrade == 11) { 
					$age = "16-17";
				}
				if ($usgrade == 12) { 
					$age = "17-18";
				}
		if ($usgrade > 3 && $usgrade < 13) $usgrade = $usgrade."th";
		if (round($grade) < 1) $title = "even by pre-school children (from ages 3 to 6)";
		if (round($grade) > 0 && round($grade) < 13) $title = "by an average student in $usgrade grade (usually around ages $age in the United States of America)";
		if (round($grade) > 12) $title = "only by a student at college or university (ages may vary)";
	return $title;
}