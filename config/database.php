<?php 
$host = "localhost";
$user = "root";
$password = "";
$db = "pms_db";
$mysqli = new mysqli(hostname: $host,
username: $user,
password: $password,
database:$db);
if($mysqli->connect_errno) {
    die("Connection error:" . $mysqli->connect_error);
}
return $mysqli;

?>