<?php 
session_start();

require "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

$correct = $_POST['radio'];
$content = $_POST['content'];

$quetions = $connection->prepare("INSERT INTO `quetions` (`quetionContent`, `lectureId`) VALUES (?, ?)");
$quetions->bind_param('si', $content, $_GET['lect']);
$quetions->execute();

$quetionId = $connection->insert_id;

$options = $connection->prepare("INSERT INTO `options` (`optionContent`, `quetionId`, `correctness`) VALUES (?, ?, ?)");

for ($i=1; $i < 5; $i++) { 
    $isCorrect = ($i == $correct) ? 1 : 0;
    $options->bind_param('sii', $_POST['option-'.$i], $quetionId, $isCorrect);
    $options->execute();
}



header('Location: index.php?lect='.$_GET['lect'].'&pg='.$_GET['pg']);

$connection->close();
?>