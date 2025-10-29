<?php
session_start();

if (!(isset($_SESSION['logged-in']))) {
  header('Location: login.php');
  exit();
}

require_once "connect.php";

if ($connection->connect_errno != 0) {
  echo "Ошибка: " . $connection->connect_errno . "<br>";
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
          if ($status == 'admin') {
            echo ('<button id="btnAddLecture" class="btn primary">+</button>
              <a id="btnLogoutAdmin" class="btn danger" href="logout.php">Выйти</a>');
          } else {
            echo ('<a id="btnEnableAdmin" class="btn" href="login.php">Включить админ</a>');
          }
          ?>

        </div>
      </div>

      <div id="lecturesList" class="lectures-list">
        <?php
        if ($status == 'admin') {
          $lectures = "SELECT * FROM lecture WHERE adminId='$userId'";
        } else {
          $lectures = "SELECT * FROM lecture WHERE forGroup='$group'";
        }

        if ($lecturesResult = $connection->query($lectures)) {
          $lecturesCount = $lecturesResult->num_rows;
          // $lectureRow = $lectureResult->fetch_assoc();
          if ($lecturesCount > 0) {
            $il = 1;
            echo ('<script>let lecture = []</script>');
            while ($lecturesRow = mysqli_fetch_array($lecturesResult)) {
              $q = "SELECT * FROM quetions WHERE lectureId='" . $lecturesRow['id'] . "'";
              $res = $connection->query($q);
              $qCount = $res->num_rows;
              $n = (!empty($_GET['pg'])) ? '&pg=' . $_GET['pg'] : '';
              if ($status == 'admin') {
                echo ('<div class="lecture-item"><a href="index.php?lect=' . $lecturesRow['id'] . '&pg=1"><div>' . $lecturesRow['nameLecture'] . '</div><div class="small">' . $qCount . ' вопрос(ов)</div></a><div class="lecture-controls"><button class="icon-btn" id="updateLecture-' . $il . '">✎</button><a class="icon-btn" href="deleteLecture.php?lect=' . $lecturesRow['id'] . $n . '">🗑</a></div></div>');
                echo ('<script>lecture.push({id: ' . intval($il) . ', idLecture: ' . intval($lecturesRow['id']) . '})</script>');
              } else {
                echo ('<div class="lecture-item"><a href="index.php?lect=' . $lecturesRow['id'] . '&pg=1">' . $lecturesRow['nameLecture'] . '</a></div>');
              }
              $il++;
            }
          } else {
            echo ('count is 0');
          }
        } else {
          echo ('conn error');
        }
        ?>

      </div>
    </aside>

    <main id="main">
      <header class="main-header">
        <h1 id="lectureTitle">Лекция:</h1>
        <div class="meta">
          <?php
          if ($status == 'admin' && !empty($_GET['lect'])) {
            echo ('<span id="lectureMeta"></span>
            <div id="lectureActions" class="lecture-actions"><button class="btn" id="addQuetion">Добавить вопрос</button>'); ?>
            <ul>Группы

              <?php
              $n = (!empty($_GET['pg'])) ? '&pg=' . $_GET['pg'] : '';
              echo ('<li><button id="addGroupBtn" class="btn primary">Добавить</button></li>');
              //<a href="addGroup.php?lect=' . $_GET['lect'] . $n . '">
              ?>

            </ul>
          <?php
            echo ('</div>');
          }          // <a class="btn primary" href="adminPanel.php">Панель</a>
          ?>
        </div>
      </header>

      <section id="lectureContent" class="lecture-content">

        <?php
        if (!empty($_GET['lect'])) {
          $selectedLectureId = $_GET['lect'];
          $selectedLecture = "SELECT * FROM lecture WHERE id='$selectedLectureId'";
          $lectureResult = $connection->query($selectedLecture);
          $lectureRow = $lectureResult->fetch_assoc();

          $tasks = "SELECT * FROM quetions WHERE lectureId='$selectedLectureId'";
          $quetionResult = $connection->query($tasks);
          $quetionCount = $quetionResult->num_rows;
          // $quetionRow = $quetionResult->fetch_assoc();

          $pageContent[0] = $lectureRow['lectureContent'];
          while ($quetionRow = mysqli_fetch_array($quetionResult)) {
            $pageContent[] = [
              'id' => $quetionRow['id'],
              'content' => $quetionRow['quetionContent']
            ];
          }
          // print_r($pageContent);



          if (!empty($_GET['pg'])) {
            if ($_GET['pg'] == 1) {
              echo ('<div id="lectureText" class="lecture-text">' . $lectureRow['lectureContent'] . '</div>');
              echo ('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '" style="visibility: hidden;">Предыдущая</a>');
              if ($quetionCount > 0) {
                echo ('<a id="btnNext" class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  + 1 . '">Следующая</a>
                      </div>');
              } else {
                echo ('</div');
              }
            } elseif ($_GET['pg'] == count($pageContent)) {
              if ($status == 'admin') {
                echo ('<div id="lectureText" class="lecture-text">' . $pageContent[$_GET['pg'] - 1]['content'] . '<a href="deleteQuetion.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '" class="btn primary" style="position:relative; float:right;">удалить вопрос</a></div>');
              } else {
                echo ('<div id="lectureText" class="lecture-text">' . $pageContent[$_GET['pg'] - 1]['content'] . '</div>');
              }


              $options = "SELECT * FROM options WHERE quetionId='" . $pageContent[$_GET['pg'] - 1]['id'] . "'";
              $optResult = $connection->query($options);
              $optCount = $optResult->num_rows;

              $answers = "SELECT * FROM answers WHERE userId='" . $_SESSION['id'] . "' AND quetionId='" . $pageContent[$_GET['pg'] - 1]['id'] . "'";
              $answersResult = $connection->query($answers);
              $answersCount = $answersResult->num_rows;
              $answersRow = $answersResult->fetch_assoc();

              if ($answersCount > 0) {
                echo ('<form id="check" method="post" action="test.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '">');
                while ($optRow = mysqli_fetch_array($optResult)) {
                  if ($status == 'admin') {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . '<a class="btn primary" href="deleteOption.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] . '&opt=' . $optRow['id'] . '" style="position:relative;float:right;height:20px;padding:0px 5px;">🗑</a></label>');
                  } else {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . "</label>");
                  }
                }

                if ($status == 'admin') {
                  echo ('<button id="addOptions" data-id="' . $pageContent[$_GET['pg'] - 1]['id']  . '" class="btn primary" type="button">Добавить варианты</button>');
                }

                if ($answersRow['correct'] == 1) {
                  echo ('<h4>Вы ответили верно на этот вопросы<span>✔️</span></h4></form>');
                } else {
                  echo ('<h4>Вы ответили неверно на этот вопрос<span>❌</span></h4></form>');
                }

                echo ('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '">Предыдущая</a>
                    <a class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=result">Показать результат</a>
                    </div>');
              } else {
                echo ('<form id="check" method="post" action="test.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '">');
                while ($optRow = mysqli_fetch_array($optResult)) {
                  if ($status == 'admin') {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . '<a class="btn primary" href="deleteOption.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] . '&opt=' . $optRow['id'] . '" style="position:relative;float:right;height:20px;padding:0px 5px;">🗑</a></label>');
                  } else {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . "</label>");
                  }
                }
                if ($status == 'admin') {
                  echo ('<button id="addOptions" data-id="' . $pageContent[$_GET['pg'] - 1]['id']  . '" class="btn primary" type="button">Добавить варианты</button>');
                }

                if ($optCount > 0) {
                  echo ('<button type="submit" class="btn primary" id="submitBtn">Проверить</button></form>');
                } else {
                  echo ('<h4>Нет вариантов ответа</h4></form>');
                }

                echo ('<div class="navigation">
                    <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '">Предыдущая</a>
                    <a class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=result">Показать результат</a>
                    </div>');
              }
            } elseif ($_GET['pg'] == 'result') {
              $allQ = count($pageContent) - 1;

              $sqlA = "SELECT * FROM answers WHERE userId = $userId AND lectureId =" . $_GET['lect'];
              $rA = $connection->query($sqlA);
              $aA = $rA->num_rows;

              $sqlQ = "SELECT * FROM quetions WHERE lectureId =" . $_GET['lect'];
              $rQ = $connection->query($sqlQ);
              $aQ = $rQ->num_rows;

              $sql1 = "SELECT * FROM answers WHERE userId = '$userId' AND correct = '1' AND lectureId = '" . $_GET['lect'] . "'";
              $r1 = $connection->query($sql1);
              $correctA = $r1->num_rows;

              $t = "SELECT * FROM total WHERE userId = $userId AND lectureId =" . $_GET['lect'];
              $resT = $connection->query($t);
              $tCount = $resT->num_rows;

              if ($tCount < 1) {
                if ($aQ == $aA) {
                  $now = date("Y-m-d H:i:s");
                  $mark = intdiv(($correctA / $allQ * 100), 1);
                  $total = $connection->prepare("INSERT INTO `total` (`userId`, `mark`, `datatime`, `lectureId`) VALUES(?, ?, ?, ?)");
                  $total->bind_param('iisi', $userId, $mark, $now, $_GET['lect']);
                  $total->execute();
                }
              }

              echo ('<div class="total">
                      <h2>Ваши результаты:</h2>
                      <h1>' . intdiv(($correctA / $allQ * 100), 1)  . '</h1>
                      <h3>' . $correctA . ' из ' . $allQ . ' верных ответов</h3>
                      <div>
                        <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' .  count($pageContent) . '">Предыдущая</a>
                        <a class="btn primary" href="index.php">На главную</a>
                      </div>
                    </div>
                  ');
            } else {
              if ($status == 'admin') {
                echo ('<div id="lectureText" class="lecture-text">' . $pageContent[$_GET['pg'] - 1]['content'] . '<a href="deleteQuetion.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '" class="btn primary" style="position:relative; float:right;">удалить вопрос</a></div>');
              } else {
                echo ('<div id="lectureText" class="lecture-text">' . $pageContent[$_GET['pg'] - 1]['content'] . '</div>');
              }

              $options = "SELECT * FROM options WHERE quetionId='" . $pageContent[$_GET['pg'] - 1]['id'] . "'";
              $optResult = $connection->query($options);
              $optCount = $optResult->num_rows;

              $answers = "SELECT * FROM answers WHERE userId='" . $_SESSION['id'] . "' AND quetionId='" . $pageContent[$_GET['pg'] - 1]['id'] . "'";
              $answersResult = $connection->query($answers);
              $answersCount = $answersResult->num_rows;
              $answersRow = $answersResult->fetch_assoc();

              if ($answersCount > 0) {
                echo ('<form id="check" method="post" action="test.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '">');
                while ($optRow = mysqli_fetch_array($optResult)) {
                  if ($status == 'admin') {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . '<a class="btn primary" href="deleteOption.php?lect=' . $_GET['lect'] . '&pg=' . $_GET['pg'] . '&opt=' . $optRow['id'] . '" style="position:relative;float:right;height:20px;padding:0px 5px;">🗑</a></label>');
                  } else {
                    echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . "</label>");
                  }
                }
                if ($status == 'admin') {
                  echo ('<button id="addOptions" data-id="' . $pageContent[$_GET['pg'] - 1]['id']  . '" class="btn primary" type="button">Добавить варианты</button>');
                }

                if ($answersRow['correct'] == 1) {
                  echo ('<h4>Вы ответили верно на этот вопрос<span>✔️</span></h4></form>');
                } else {
                  echo ('<h4>Вы ответили неверно на этот вопрос<span>❌</span></h4></form>');
                }

                echo ('<div class="navigation">
                  <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '">Предыдущая</a>
                  <a id="btnNext" class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  + 1 . '">Следующая</a>
                  </div>');
              } else {
                echo ('<form id="check" method="post" action="test.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg'] . '&q=' . $pageContent[$_GET['pg'] - 1]['id'] . '">');
                while ($optRow = mysqli_fetch_array($optResult)) {
                  echo ('<label><input type="radio" class="quetion" name="q" value="' . $optRow['correctness'] . '" required>' . $optRow['optionContent'] . '</label>');
                }
                if ($status == 'admin') {
                  echo ('<button id="addOptions" data-id="' . $pageContent[$_GET['pg'] - 1]['id']  . '" class="btn primary" type="button">Добавить варианты</button>');
                }
                if ($optCount > 0) {
                  echo ('<button type="submit" class="btn primary" id="submitBtn">Проверить</button></form>');
                } else {
                  echo ('<h4>Нет вариантов ответа</h4></form>');
                }

                echo ('<div class="navigation">
                  <a id="btnPrev" class="btn" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '">Предыдущая</a>
                  <a id="btnNext" class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  + 1 . '">Следующая</a>
                  </div>');
              }
            }
            if ($_GET['pg'] != 'result' && $_GET['pg'] > count($pageContent)) {
              header('Location: index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg'] - 1);
            }
          } else {
            echo ('<div id="lectureText" class="lecture-text">' . $lectureRow['lectureContent'] . '</div>');
            echo ('<div class="navigation">
                    <a id="btnPrev" class="btn"href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  - 1 . '">Предыдущая</a>
                    <a id="btnNext" class="btn primary" href="index.php?lect=' . $selectedLectureId . '&pg=' . $_GET['pg']  + 1 . '">Следующая</a>
                  </div>');
          }
        } else {
          echo ('<div id="lectureText" class="lecture-text">Выберите лекцию в панели лекций</div>');
        }
        ?>


        <!-- <div class="quetion"></div> -->



      </section>
    </main>
  </div>

  <!-- Модальные окна -->
  <div id="modalOverlay" class="modal-overlay hidden">
    <form id="modalForm" method="post">
      <div class="modal">
        <h3 id="modalTitle"></h3>
        <div id="modalBody"></div>
        <div class="modal-actions">
          <button id="modalCancel" class="btn" type="button">Отмена</button>
          <button id="modalSave" class="btn primary">Сохранить</button>
        </div>
      </div>
    </form>
  </div>

  <script src="js/scripts.js"></script>
</body>

</html>

<?php $connection->close(); ?>