<?php
    session_start();
    if(!(isset($_SESSION['logged-in']))){
        header('Location: login.php');
        exit();
    }
    
    require_once "connect.php";
    
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    

    if($connection->connect_errno!=0){
        echo "Ошибка: ".$connection->connect_errno . "<br>";
        echo "Описание: " . $connection->connect_error;
        exit();
    }

    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $userId = $_SESSION['id'];

    $sql = "SELECT * FROM users WHERE id='$userId'";

    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    $status = $row['status'];
    $group = $row['group_name'];

    $currentAnswer = 0;
    
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Учебный портал — Лекции и Тесты</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div id="app">
    <aside id="sidebar">
      <div class="sidebar-header">
        <h2>Лекции</h2>

        <!-- Админ-кнопки -->
         
        <div class="admin-controls" id="adminControls">
          <?php
            if($status == 'admin'){
              echo('<button id="btnAddLecture" class="btn primary">+</button>
              <a id="btnLogoutAdmin" class="btn danger" href="logout.php">Выйти</a>');
            }else{
              echo('<a id="btnEnableAdmin" class="btn" href="login.php">Включить админ</a>');
            }
          ?>
          
        </div>
      </div>

      <div id="lecturesList" class="lectures-list">
        <?php
          if($status == 'admin'){
            $lectures = "SELECT * FROM lecture WHERE adminId='$userId'";
          }
          else{
            $lectures = "SELECT * FROM lecture WHERE forGroup='$group'";
          }

          if($lecturesResult = $connection->query($lectures)){
            $lecturesCount = $lecturesResult->num_rows;
            // $lectureRow = $lectureResult->fetch_assoc();
            if($lecturesCount>0){
              
              while ($lecturesRow = mysqli_fetch_array($lecturesResult)) {
                $q = "SELECT * FROM quetions WHERE lectureId='".$lecturesRow['id']."'";
                $res = $connection->query($q);
                $qCount = $res->num_rows;
                if($status == 'admin'){
                  echo('<div class="lecture-item active"><a href="index.php?lect='.$lecturesRow['id'].'&pg=1"><div>'.$lecturesRow['nameLecture'].'</div><div class="small">'.$qCount.' вопрос(ов)</div></a><div class="lecture-controls"><button class="icon-btn">✎</button><button class="icon-btn">🗑</button></div></div>');
                }else{
                  echo('<div class="lecture-item active"><a href="index.php?lect='.$lecturesRow['id'].'&pg=1">'.$lecturesRow['nameLecture'].'</a></div>');
                }
              }
            }else{
              echo('count is 0');
            }
          }else{
            echo('conn error');
          }
        ?>
        
      </div>
    </aside>

    <main id="main">
      <header class="main-header">
        <h1 id="lectureTitle">Лекция:</h1>
        <div class="meta">
          <?php
          if($status == 'admin'){
          echo('<span id="lectureMeta"></span>
          <div id="lectureActions" class="lecture-actions"><button class="btn">Добавить вопрос</button></div>');
          }
          ?>
        </div>
      </header>
      
      <section id="lectureContent" class="lecture-content">

        <?php
          if(!empty($_GET['lect'])){
            $selectedLectureId = $_GET['lect'];
            $selectedLecture = "SELECT * FROM lecture WHERE id='$selectedLectureId'";
            $lectureResult = $connection->query($selectedLecture);
            $lectureRow = $lectureResult->fetch_assoc();

            $tasks="SELECT * FROM quetions WHERE lectureId='$selectedLectureId'";
            $quetionResult = $connection->query($tasks);
            $quetionCount = $quetionResult->num_rows;
            // $quetionRow = $quetionResult->fetch_assoc();

            $pageContent[0] = $lectureRow['lectureContent'];
            while($quetionRow = mysqli_fetch_array($quetionResult)){
              $pageContent[] = ['id'=> $quetionRow['id'],
                                'content'=> $quetionRow['quetionContent']];
            }
            // print_r($pageContent);
            
            
            
            if(!empty($_GET['pg'])){
              $total= 0;
              if($_GET['pg'] == 1){
                echo('<div id="lectureText" class="lecture-text">'.$lectureRow['lectureContent'].'</div>');
                echo('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'" style="visibility: hidden;">Предыдущая</a>
                    <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'">Следующая</a>
                  </div>');
                }
                else if($_GET['pg'] == count($pageContent)){
                  echo('<div id="lectureText" class="lecture-text">'.$pageContent[$_GET['pg']-1]['content'].'</div>');
                  
                  $options = "SELECT * FROM options WHERE quetionId='".$pageContent[$_GET['pg']-1]['id']."'";
                  $optResult = $connection->query($options);
                  $optCount = $optResult->num_rows;

                  $answers = "SELECT * FROM answers WHERE userId='".$_SESSION['id']."' AND quetionId='".$pageContent[$_GET['pg']-1]['id']."'";
                  $answersResult = $connection->query($answers);
                  $answersCount = $answersResult->num_rows;
                  $answersRow = $answersResult->fetch_assoc();

                  if($answersCount>0){
                    echo('<form id="check" method="post" action="test.php?lect='.$selectedLectureId.'&pg='.$_GET['pg'].'&q='. $pageContent[$_GET['pg']-1]['id'].'">');  
                    while($optRow = mysqli_fetch_array($optResult)){
                      echo('<label><input type="radio" class="quetion" name="q" value="'.$optRow['correctness'].'" required>'.$optRow['optionContent']."</label>");
                      if($optRow['correctness'] == 1){$total+=1;}   
                    }

                    if($answersRow['correct'] == 1){
                      echo('<h4>Вы ответили верно на этот вопрос</h4></form>');
                    }else{
                      echo('<h4>Вы ответили неверно на этот вопрос</h4></form>');
                    }

                    echo('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'">Предыдущая</a>
                    <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'" style="visibility:hidden;">Следующая</a>
                    </div>');
                  }else{
                    echo('<form id="check" method="post" action="test.php?lect='.$selectedLectureId.'&pg='.$_GET['pg'].'&q='. $pageContent[$_GET['pg']-1]['id'].'">');  
                    while($optRow = mysqli_fetch_array($optResult)){
                      echo('<label><input type="radio" class="quetion" name="q" value="'.$optRow['correctness'].'" required>'.$optRow['optionContent']."</label>");
                      if($optRow['correctness'] == 1){$total+=1;}   
                    }
                    echo('<button type="submit" class="btn primary" id="submitBtn">Проверить</button></form>');

                    echo('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'">Предыдущая</a>
                    <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'" style="visibility:hidden;">Следующая</a>
                    </div>');
                  }

                  
              }
              else{
                echo('<div id="lectureText" class="lecture-text">'.$pageContent[$_GET['pg']-1]['content'].'</div>');
                
                $options = "SELECT * FROM options WHERE quetionId='".$pageContent[$_GET['pg']-1]['id']."'";
                $optResult = $connection->query($options);
                $optCount = $optResult->num_rows;

                $answers = "SELECT * FROM answers WHERE userId='".$_SESSION['id']."' AND quetionId='".$pageContent[$_GET['pg']-1]['id']."'";
                $answersResult = $connection->query($answers);
                $answersCount = $answersResult->num_rows;
                $answersRow = $answersResult->fetch_assoc();

                if($answersCount>0){
                  echo('<form id="check" method="post" action="test.php?lect='.$selectedLectureId.'&pg='.$_GET['pg'].'&q='. $pageContent[$_GET['pg']-1]['id'].'">');
                  while($optRow = mysqli_fetch_array($optResult)){
                    echo('<label><input type="radio" class="quetion" name="q" value="'.$optRow['correctness'].'" required>'.$optRow['optionContent']."</label>");
                    if($optRow['correctness'] == 1){$total+=1;}
                  }
                  
                  if($answersRow['correct'] == 1){
                      echo('<h4>Вы ответили верно на этот вопрос</h4></form>');
                    }else{
                      echo('<h4>Вы ответили неверно на этот вопрос</h4></form>');
                    }
                  
                  echo('<div class="navigation">
                  <a id="btnPrev" class="btn" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'">Предыдущая</a>
                  <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'">Следующая</a>
                  </div>');
                }
                else{
                  echo('<form id="check" method="post" action="test.php?lect='.$selectedLectureId.'&pg='.$_GET['pg'].'&q='. $pageContent[$_GET['pg']-1]['id'].'">');
                  while($optRow = mysqli_fetch_array($optResult)){
                    echo('<label><input type="radio" class="quetion" name="q" value="'.$optRow['correctness'].'" required>'.$optRow['optionContent']."</label>");
                    if($optRow['correctness'] == 1){$total+=1;}
                  }
                  echo('<button type="submit" class="btn primary" id="submitBtn">Проверить</button></form>');
                  
                  echo('<div class="navigation">
                  <a id="btnPrev" class="btn" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'">Предыдущая</a>
                  <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'">Следующая</a>
                  </div>');
                }
                
              }
              if($_GET['pg'] > count($pageContent)){
                header('Location: index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg'] - 1);
              }
            }
            else{
              echo('<div id="lectureText" class="lecture-text">'.$lectureRow['lectureContent'].'</div>');
              echo('<div class="navigation">
                    <a id="btnPrev" class="btn"href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  - 1 .'">Предыдущая</a>
                    <a id="btnNext" class="btn primary" href="index.php?lect='.$selectedLectureId.'&pg='. $_GET['pg']  + 1 .'">Следующая</a>
                  </div>');
            }
          }
          else{
            echo('<div id="lectureText" class="lecture-text">Здесь будет текст лекции</div>');
          }
        ?>
        

        <!-- <div class="quetion"></div> -->
        

        
      </section>
    </main>
  </div>

  <!-- Модальные окна -->
  <div id="modalOverlay" class="modal-overlay hidden">
    <div class="modal">
      <h3 id="modalTitle"></h3>
      <div id="modalBody"></div>
      <div class="modal-actions">
        <button id="modalCancel" class="btn">Отмена</button>
        <button id="modalSave" class="btn primary">Сохранить</button>
      </div>
    </div>
  </div>

  <!-- <script src="js/scripts.js"></script> -->
</body>
</html>

<?php $connection->close(); ?>