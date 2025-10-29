<?php
session_start();

require "connect.php";

$lecture = "DELETE FROM lecture WHERE id='" . $_GET['lect'] . "'";
$connection->query($lecture);

$q = "SELECT * FROM quetions WHERE lectureId=" . $_GET['lect'];
$resQ = $connection->query($q);
$qCount = $resQ->num_rows;
if ($qCount > 0) {
    while ($qRow = mysqli_fetch_array($resQ)) {
        $o = "DELETE FROM options WHERE quetionId =" . $qRow['id'];
        $connection->query($o);
    }
}

$quetions = "DELETE FROM quetions WHERE lectureId =" . $_GET['lect'];
$connection->query($quetions);

if (isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg']);
}

$connection->close();
