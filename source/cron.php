<?php 
// подсоединение к DB
require_once "db.php";
$db_conn->exec("DELETE tempw,tempt,sessions FROM tempw INNER JOIN tempt INNER JOIN sessions WHERE tempw.session=tempt.session AND tempt.session=sessions.id AND sessions.time < (now() - INTERVAL 1 day)");
?>
