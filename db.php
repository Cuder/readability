<?php
if (preg_match("/db.php/i", $_SERVER['PHP_SELF'])) header("Location: helper.php");

// авторизационные данные
$user = 'root';
$pass = 443912;

// включаем постоянное соединение с БД
try {
	$db_conn = new PDO('mysql:host=localhost;dbname=test', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
	//$db_conn->exec('SET NAMES utf8');
} catch (PDOException $e) {
	print "<b>Error!</b> " . $e->getMessage() . "<br/>";
	die();
}


?>
