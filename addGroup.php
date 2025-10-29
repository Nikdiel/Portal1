<?php
session_start();

require "connect.php";

$stmt = $connection->prepare("INSERT INTO `users_group` (`name`, `adminId`) VALUES(?, ?)");
$stmt->bind_param('si', $_POST['name'], $_SESSION['id']);
$stmt->execute();

if (isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg']);
}

$connection->close();
