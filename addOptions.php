<?php
session_start();

require "connect.php";

$correct = (!empty($_POST['radio'])) ? $_POST['radio'] : '';

$options = $connection->prepare("INSERT INTO `options` (`optionContent`, `quetionId`, `correctness`) VALUES (?, ?, ?)");

for ($i = 1; $i < 5; $i++) {
    $isCorrect = ($i == $correct) ? 1 : 0;
    if (!empty($_POST['option-' . $i])) {
        $options->bind_param('sii', $_POST['option-' . $i], $_GET['q'], $isCorrect);
        $options->execute();
    }
}

if (!isset($_GET['lect'])) {
    header('Location: index.php');
} elseif (!isset($_GET['pg'])) {
    header('Location: index.php?lect=' . $_GET['lect']);
} else {
    header('Location: index.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg']);
}

$connection->close();
