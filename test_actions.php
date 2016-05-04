<?php
  include("script.php");
  //header("Content-Type: text/html; charset=utf-8");
  $connection = db_connect();
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/test_script.css">
</head>
<body>

  <h1>Вывод статей журнала</h1>
  <form action="script.php" method="post">
    <select id="choose-jour" size="1" name="journal">
      <option disabled selected="true">Журнал</option>
      <?php
        $result = select_from_db($connection,"journals");
        while( $row = $result->fetch_assoc() ){ 
        echo "<option>Серия ".$row['type']." №".$row['number']." ".$row['date']."</option>";
      }
      ?>
    </select>
  </form>
  <br>
  <table class="hidden" id="article-data">
    <tr>
      <th>Авторы</th>
      <th>Статья</th>
      <th>Блокировка</th>
      <th>Журнал</th>
      <th>Страницы</th>
    </tr>
  </table>
  <br> 
  <form id="redact-article-form" class="hidden" action="test_script.php?req_type=redact_article">
    Введите автора: <input type=text name="author"  required value=""><br><br>
    Введите название статьи: <input type=text name="art_name"  required value=""><br><br>
    Введите журнал для публикации статьи:<input type=text name="journal_name"  required value=""><br><br>
    Введите страницы публикации: <input type=text name="pages"  required value=""><br><br>
    Содержание статьи <br>
    <textarea id="last" cols="80" rows="10" name="art_text"  required></textarea><br><br>
    <button id="save-change-art">Сохранить изменения</button>
  </form>

  <button id="redact-article">Редактировать</button>
  
  <hr><hr>


    <script src="lib/jquery/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
    <script src="lib/jquery/jquery.json.js"></script>
    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

