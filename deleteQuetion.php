<?php
session_start();

require "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

$q = "SELECT * FROM quetions WHERE id=" . $_GET['q'];
$resQ = $connection->query($q);
$qCount = $resQ->num_rows;
if ($qCount > 0) {
    while ($qRow = mysqli_fetch_array($resQ)) {
        $o = "DELETE FROM options WHERE quetionId =" . $qRow['id'];
        $connection->query($o);
    }
}

$quetions = "DELETE FROM quetions WHERE id =" . $_GET['q'];
$connection->query($quetions);

if (isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] - 1);
}

$connection->close();
