<?php
// forbid to open this file directly from the browser
if (preg_match("/main.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

echo "<div class='step'>About the tool</div>";

// overview
echo "<div class='resultcaption'>Overview</div>";
echo "<div class='result'>";
echo "The tool checks your texts against the following <a href='http://en.wikipedia.org/wiki/Readability_test' target='_blank'>readability tests</a>:";
echo "<ul>
		<li>Flesch-Kincaid Reading Ease</li>
		<li>Flesch-Kincaid Grade Level</li>
		<li>Gunning Fog Score</li>
		<li>Coleman-Liau index</li>
		<li>SMOG index</li>
		<li>Automated readability index</li>
	  </ul>";
echo "It is also possible to display the statistics of your text. This includes the number of sentences, words and syllables. Using an experimental feature you can detect formal and most common English words.<br>";
echo "Currently, only the English language is supported.</div>";

// Credits
echo "<div class='resultcaption'>Credits</div>";
echo "<ul>";
echo "<li><a href='http://www.addedbytes.com/' target='_blank'>Dave Child</a> for <a href='https://github.com/DaveChild/Text-Statistics' target='_blank'>PHP Text Statistics</a></li>";
echo "<li><a href='http://abrdev.com/' target='_blank'>aleks_raiden</a> for <a href='https://code.google.com/p/phplangautodetect/' target='_blank'>PHPLangautodetect</a></li>";
echo "<li><a href='http://wortschatz.uni-leipzig.de/' target='_blank'>Universität Leipzig</a> for <a href='http://www.wortschatz.uni-leipzig.de/Papers/top10000en.txt' target='_blank'>the list of 10000 most common English words</a></li>";
echo "<li>English formal words are collected from <a href='http://www.harpercollins.com/' target='_blank'>Collins Cobuild Advanced Learner’s English Dictionary</a> (2008)</li>";
echo "<li><a href='http://bassistance.de/' target='_blank'>Jörn Zaefferer</a> for <a href='http://bassistance.de/jquery-plugins/jquery-plugin-tooltip/' target='_blank'>jQuery Tooltip plugin</a></li>";
echo "<li>CSS style for buttons are generated with the help of <a href='http://www.cssbuttongenerator.com/' target='_blank'>CSSButtonGenerator.com</a></li>";
echo "</ul>";

?>
