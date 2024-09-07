<?php
if(empty($_POST["name"])){
    die("Name require");
}
if(empty($_POST["email"])){
    die("email require");
}
if(empty($_POST["password"])){
    die("password require");
    }
if(empty($_POST["confirm-password"])){
    die("confirm-password require");
    }
if(! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    die("Invalid email");
}
if(strlen($_POST["password"]) <6){
    die("Password should have atleast 6characters");
}
if(! preg_match("/[a-z]/i", $_POST["password"])){
    die("Password should contain atleast one letter");
}
if(! preg_match("/[0-9]/i", $_POST["password"])){
    die("Password should contain atleast one number");
}
if($_POST["password"] !== $_POST["confirm-password"]){
    
    die("Password should match");

}
$password_hash=password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/config/database.php";

$sql ="INSERT INTO patient (patientname, email, password_hash)
VALUES (?, ?, ?)";
$stmt =$mysqli->stmt_init();
if(! $stmt->prepare($sql)){
    die("SQL error:" . $mysqli->error);
}
$stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

if($stmt->execute()){

header ("Location: index.php"); 
exit();

}else{
    die($mysqli->error . "" . $mysqli->errno);
}
?>