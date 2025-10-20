<?php
    session_start();
    
    require_once "connect.php";
    
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    $answers = [];

    if(!empty($_POST['q'])){
        $answer = $_POST['q'];

        $a = "SELECT * FROM answers WHERE userId='".$_SESSION['id']."' AND quetionId='".$_GET['q']."'";
        $res = $connection->query($a);
        $aCount = $res->num_rows;

        if($aCount>0){
            echo('');
        }else{
            $stmt = $connection->prepare("INSERT INTO `answers` (`userId`, `quetionId`, `correct`, `lectureId`) 
            VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $_SESSION['id'], $_GET['q'], $_POST['q'], $_GET['lect']);
            $stmt->execute();
        }

        
            
        // if($answer == 1){
            
        // }else{
           
        // }
    }
    header('location:index.php?lect='.$_GET['lect'].'&pg='. $_GET['pg']);
?>