<?php 
session_start();

require "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

$stmt = $connection->prepare("INSERT INTO `lecture` (`nameLecture`, `lectureContent`, `forGroup`, `adminId`) VALUES(?, ?, ?, ?)");
$stmt->bind_param('sssi', $_POST['name'], $_POST['content'], $_POST['group'], $_SESSION['id']);
$stmt->execute();

header('Location: index.php?lect='.$_GET['lect'].'&pg='.$_GET['pg']);

$connection->close();
?>