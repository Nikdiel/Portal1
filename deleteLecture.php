<?php 
session_start();

require "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

$lecture = "DELETE FROM lecture WHERE id='".$_GET['lect']."'";
$connection->query($lecture);

header('Location: index.php');

$connection->close();
?>