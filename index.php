<?php
// начало выполнения скрипта
$start_time = microtime(true);

// вводим файл глобальных настроек и переменных
require_once "core.php";

// подсоединение к бд
require_once "db.php";

// вводим файл настроек скрипта
require_once "config.php";

// здесь пользователь с IE будет вытурен на страницу с ошибкой
if ( maxsite_testIE() ) {
	die("I have discovered that you are using Internet Explorer. We do not collaborate with such people.");
}

// HTML <head> and other beginning
require_once "head.php";

// the main layout table begins in here
echo "<table cellpadding='0' cellspacing='0' class='maintable'>";

	// header
	echo "<tr><td class='header'>";
	require_once "header.php";
	echo "</td></tr>";

	// the small table layout (for menu + main window)
	echo "<tr><td>";
		echo "<table cellpadding='0' cellspacing='0' class='smalltable'><tr>";

			// script stages menu
			echo "<td class='menu'>";
			require_once "menu.php";
			echo "</td>";

			// beginning of the main window of the script
			echo "<td class='mainwindow'>";
			
			// errors output (all the possible errors)
			if (isset($error)) {
				if ($error) {
					require_once "error.php";
				} else {
					fallback("index.php");
				}
				
			// stages of the script
			} elseif (isset($stage)) {
				
				// first stage. Input your text
				if ($stage=='input') {
					require_once "prep.input.php";
				} elseif ($stage=='readability') {
					require_once "prep.readability.php";
				} elseif ($stage=='otheroptions') {
					require_once "prep.otheroptions.php";
				} elseif ($stage=='result') {
					require_once "prep.result.php";
				} else {
					fallback("index.php");
				}
			
			// main window about the script blah-blah
			} else {
				require_once "main.php";
			}
			
			// the end of the main window
			echo "</td>";

		// the end of the small table layout
		echo "</tr></table>";
	echo "</td></tr>";

	// подвал
	echo "<tr><td class='footer'>";
	require_once "footer.php";
	echo "</td></tr>";

// the end of the main table layout
echo "</table>";

// HTML ends in here
echo "</body></html>";
?>
