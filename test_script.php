<?php
  include("script.php");
  //header("Content-Type: text/html; charset=utf-8");
  $connection = db_connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Bootstrap 101 Template</title>

  <!-- Bootstrap -->
  <!--<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/test_script.css">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>

    <?php
      $query_result = select_script($connection);
    ?>
    <h1>Добавить автора</h1>
    <form id="reg-form" class="reg-form" method="post" action="test_script.php?req_type=add_author">
      Введите ФИО автора: <input type=text name="author_name" required placeholder="Автор" value="1"><br><br>
      Введите научную степень: <input type=text name="dc_degree" required placeholder="Научная степень" value="1"><br><br>
      Введите серию журнала для публикации статей:<select id="choose-type" size="1" name="type">
        <option disabled selected="true">Серия</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
      <select>
      <br><br>
      Введите организацию: <input type=text name="organisation" required placeholder="Организация" value="1">
      <br><br>
      <input id="send_author_data" type=submit value="Добавить автора" name="send_user_data">
    </form>
    <hr><hr>

    <h1>Добавление журнала</h1>
    
    <form id="journal-form" method="post" action="test_script.php?req_type=add_journal">
      Введите название журнала: <input type="text" name="journal_name" required placeholder="Журнал"><br><br>
      Выберите класс журнала: <select id="choose-type" size="1" name="type">
        <option disabled selected="true">Серия</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
      <select> <br><br>
      Введите год публикации: <input type="text" name="pub_year" required placeholder="Год"><br><br>
      Введите номер журнала: <input type="text" name="journal_number" required placeholder="Номер"><br><br>
      Введите количество страниц журнала: <input type="text" name="journal_pages" required placeholder="Страницы"><br><br>
      Заблокировать журнал:
      <p>
        <input name="art_blocked" checked type="radio" value=1> 
        Да 
        <input name="art_blocked" type="radio" value=0> 
        Нет</p>
      <input id="add_journal" type=submit value="Добавить журнал" name="send_journal_data">
    </form>
    <hr><hr>

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
  
  
  <h1>Поиск</h1>
    <p>Поиск новостей по автору:</p>
    <form id="search-form" method="post" action="test_script.php?req_type=search">
      <input type=text name="search_author" placeholder="Автор">
      <p>Поиск новостей по названию статьи:</p>
      <input type=text name="search_name" placeholder="Статья">
      <p><button name="search_but">Поиск</button></p>
    </form>
    <?php
        if($query_result[count($query_result)-1] === "search"){
            show_s_results($query_result);
        } else if($query_result === "not_found"){
            echo "Поиск не дал результатов";
        }
    ?>
    <button id="show_art_data" hidden>Данные о статье</button>
  
  <hr><hr>

  <h1>Вывод статей журнала</h1>
  
  <form action="script.php" method="post">
    <select size="1" name="journal-class" id="vew_journ_class">
      <option disabled selected="true">Серия</option>
      <option>A</option>
      <option>B</option>
      <option>C</option>
      <option>D</option>
      <option>E</option>
    </select>
    <select id="journals" class="choose-jour" size="1" name="journal" disabled="true">
      <option disabled selected="true">Журнал</option>
    </select>
  </form>
  <br>
  <table class="hidden" id="article-data">
    <tr class="table-head">
      <th>Авторы</th>
      <th>Статья</th>
      <th>Страницы</th>
      <th>Журнал</th>
    </tr>
  </table>
  <br> 
  <!--<form id="redact-article-form" method="POST" class="hidden" action="test_script.php?req_type=redact_article">
    <p>Введите название статьи: <input required type=text name="art_name"  required value=""></p>
    <p>Введите страницы публикации: <input required type=text name="pages"  required value=""></p>
    <table class="table" id="auth-redact-data">
      <tbody>
         <caption>Авторы статьи</caption>
      </tbody>
    </table>
    <p><button onclick="return false" id="redact_add_auth">Добавить автора</button></p>
    <div class="author_selectors hidden">
        <p>Выберите автора публикации: <select name="author0" class="avail_authors">
          <option disabled="true" selected="true">Автор</option>
        </select></p>
        <p><button onclick="return false" id="add_author">Ещё автор</button></p>
    </div>
    
    <p><button id="save-change-art" onclick="return false">Сохранить изменения</button></p>
  </form>

  <button id="redact-article">Редактировать</button>
  <button id="delete-article">Удалить</button>
  -->
  <hr><hr>

  <h1>Вывод авторов</h1>
  <p>Выберите сурию журнала <select size="1" name="journal-class" id="vew_author_by_class">
      <option disabled selected="true">Серия</option>
      <option>A</option>
      <option>B</option>
      <option>C</option>
      <option>D</option>
      <option>E</option>
    </select></p>
  <table class="table table-hover hidden" id="authors-data">
    <tr class="table-head">
      <th>Автор</th>
      <th>Научная степень</th>
      <th>Организация</th>
    </tr>
    <?php
      /*$result = select_from_db($connection,"*","authors");
      while( $row = $result->fetch_assoc() ){ 
        echo "<tr>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['dc_degree']."</td>";
        echo "<td>".$row['organisation']."</td>";
        echo "</tr>";
      }*/
    ?>
  </table>
  <br>
  <button id="redact-authors">Редактировать</button>
  <button id="delete-author">Удалить</button>
    <form id="redact-author-form" name="redact_author" class="hidden">
      <p>Фамилия имя отчество<input required type="text"></input></p>
      <p>Научная степень<input required type="text"></input></p>    
      <p>Серия журналов для публикации <select id="choose-type" size="1" name="type">
        <option disabled selected="true">Серия</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
      <select></p>
      <p>Организация<input required type="text"></input></p>
      <p><button onclick="return false" id="update-author">Сохранить изменения</button></p>
    </form>
  <hr><hr>

    <script src="lib/jquery/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
    <script src="lib/jquery/jquery.json.js"></script>
    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>