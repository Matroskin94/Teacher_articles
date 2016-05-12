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
  <h1>Добавление материала</h1>
   <form id="art-form" class="reg-form" method="post" action="test_script.php?req_type=new_article">
      <p>Введите название статьи: <input type=text name="art_name"  required value="art_name"></p>
      <p id="choose_journ_p">Выберите серию журнала: <select id="choose-journal-class" name="jour_class">
        <option disabled selected="true"> Серия </option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
      </select></p>
      <p>Выберите журнал: <select disabled id="avail_journals" name="journal">
        <option disabled selected>Журнал</option>
      </select></p>
      <p>Введите страницы публикации: <input type=text name="pages"  required value="pages"></p>
      <hr>
      <div class="author_selectors">
        <p>Выберите автора публикации: <select disabled name="author0" class="avail_authors">
          <option disabled selected>Автор</option>
        </select></p>
        <p><button class="hidden" onclick="return false" id="add_author">Ещё автор</button></p>
      </div>
      
      <input id="send-article-data" type=submit value="Добавить материал" name="send_article_data">
    </form>
    <hr><hr>


    <script src="lib/jquery/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
    <script src="lib/jquery/jquery.json.js"></script>
    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

