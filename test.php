<?php
session_start();

require_once "connect.php";

$answer = $_POST['q'];

$a = "SELECT * FROM answers WHERE userId='" . $_SESSION['id'] . "' AND quetionId='" . $_GET['q'] . "'";
$res = $connection->query($a);
$aCount = $res->num_rows;

if ($aCount == 0) {
    $stmt = $connection->prepare("INSERT INTO `answers` (`userId`, `quetionId`, `correct`, `lectureId`) 
            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $_SESSION['id'], $_GET['q'], $_POST['q'], $_GET['lect']);
    $stmt->execute();
}


if (
    !isset($_GET['lect']) ||
    !is_numeric($_GET['lect']) ||
    intval($_GET['lect']) <= 0
) {
    header('Location: index.php');
    exit;
}

if (
    !isset($_GET['pg']) ||
    !is_numeric($_GET['pg']) ||
    intval($_GET['pg']) <= 0
) {
    header('Location: index.php?lect=' . urlencode($_GET['lect']) . '&pg=1');
    exit;
}

header('Location: index.php?lect=' . urlencode($_GET['lect']) . '&pg=' . urlencode($_GET['pg']));
exit;


$connection->close();
