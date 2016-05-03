<?php
require_once "config.php";
$db_conn = new PDO('mysql:host='.$db_host.';dbname='.$db_name.'', $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));
$db_conn->exec("DELETE tempw,tempt,sessions FROM tempw INNER JOIN tempt INNER JOIN sessions WHERE tempw.session=tempt.session AND tempt.session=sessions.id AND sessions.time < (now() - INTERVAL 1 day)");
