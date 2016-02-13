<?php
// forbid to open this file directly from the browser
if (preg_match("/logic.textmark.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// if the user wanted long or formal words to be underlined
if (isset($_POST['longwords']) || isset($_POST['fwords'])) {
	// select the raw text from the DB. We shall make it marked!
	$sth = $db_conn->prepare("SELECT raw FROM tempt WHERE session='".$session."'");
	$sth->execute();
	$text = $sth->fetchColumn();
		$stmt = $db_conn->prepare("SELECT word FROM tempw WHERE (longw=1 OR formalw=1) AND session='".$session."'");
		$stmt -> execute();
		while ($row = $stmt->fetch()) {
				// может, попробовать оптимизировать, а может и нет		
				$stmt2 = $db_conn->prepare("SELECT word,longw,formalw FROM tempw WHERE (longw=1 OR formalw=1) AND session='".$session."' AND word LIKE '%".$row[0]."%' ORDER BY CHAR_LENGTH(word) DESC");
				$stmt2 -> execute();
				while ($row2 = $stmt2->fetch()) {
					if ($row2[1] == 1 && $row2[2] == 1 && isset($_POST['longwords']) && isset($_POST['fwords'])) {
						$text = preg_replace(".$row2[0].", '<font style="color:red" title="::The word is long. More than that, it is formal">'.$row2[0].'</font>', $text);
					} elseif (($row2[1] == 1) && isset($_POST['longwords'])) {
						$text = preg_replace(".$row2[0].", '<font style="color:blue" title="::The word is long">'.$row2[0].'</font>', $text);
					} elseif (($row2[2] == 1) && isset($_POST['fwords'])) {
						$text = preg_replace(".$row2[0].", '<font style="color:green" title="::The word is formal">'.$row2[0].'</font>', $text);
					}
				}
		}	
		$sql = "UPDATE tempt SET marked=? WHERE session=?";
		$db_upd = $db_conn->prepare($sql);
		$db_upd->execute(array($text,$session));
} 

// updating showing options
$showstats = (isset($_POST['showstats']))?1:0;
$textshow = (isset($_POST['textshow']))?1:0;
$longwords = (isset($_POST['longwords']))?1:0;
$fwords = (isset($_POST['fwords']))?1:0;
//$showopt = $showstats.$textshow.$longwords.$fwords;
$db_conn->exec("UPDATE sessions SET showopt=INSERT(showopt,2,5,'".$showstats.$textshow.$longwords.$fwords."'),stage='1' WHERE id='".$session."'");

fallback("index.php?stage=result&session=$session");