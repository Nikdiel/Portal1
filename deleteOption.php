<?php
session_start();

require "connect.php";

$opt = "DELETE FROM options WHERE id =" . $_GET['opt'];
$connection->query($opt);

if (!isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (!isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg']);
}

$connection->close();
