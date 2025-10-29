<?php
session_start();

require "connect.php";

$sql = "SELECT * FROM lecture WHERE id = " . $_GET['li'];
$res = $connection->query($sql);
$row = $res->fetch_assoc();

$oldname = $row['nameLecture'];
$oldcontent = $row['lectureContent'];
$oldgroup = $row['forGroup'];

$newname = !empty($_POST['name']) ? $_POST['name'] : $oldname;
$newcontent = !empty($_POST['content']) ? $_POST['content'] : $oldcontent;
$newgroup = !empty($_POST['group']) ? $_POST['group'] : $oldgroup;

$stmt = $connection->prepare("UPDATE lecture SET nameLecture = ?, lectureContent = ?, forGroup = ?, adminId = ? WHERE id = ?");
$stmt->bind_param('sssii', $newname, $newcontent, $newgroup, $_SESSION['id'], $_GET['li']);
$stmt->execute();

if (!isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (!isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg']);
}


$connection->close();
