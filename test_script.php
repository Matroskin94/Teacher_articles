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
    <h1>Регистрация</h1>
    <form id="reg-form" class="reg-form" method="post" action="test_script.php?req_type=register">
      Введите логин: <input type=text name="nickname"  required value="1"><br><br>
      Введите пароль: <input id='pass' type=password name="pass" required value="2">
      <div class="wrong-pass">
        <p></p>
      </div>
      <br><br>
      Повторите пароль: <input id='re_pass' type=password name="pass_rep" required value="1">
      <div class="wrong-pass">
        <p></p>
      </div>
      <br><br>
      Введите фамилию: <input type=text name="surname"  required placeholder="Ваша фамилия" value="1"><br><br>
      Введите имя: <input type=text name="name" required placeholder="Ваше имя" value="1"><br><br>
      Введите отчество: <input type=text name="lastname" required placeholder="Ваше отчество" value="1"><br><br>
      Введите научную степень: <input type=text name="dc_degree" required placeholder="Научная степень" value="1"><br><br>
      Введите Вашу организацию: <input type=text name="organisation" required placeholder="Организация" value="ПГУ"><br><br>
      <input id="send_user-data" type=submit value="Зарегистрироваться" name="send_user_data">
    </form>
    <hr><hr>

    <h1>Добавление материала</h1>
    <form id="art-form" class="reg-form" method="post" action="test_script.php?req_type=new_article">
      Введите автора: <input type=text name="author"  required value="author"><br><br>
      Введите название статьи: <input type=text name="art_name"  required value="art_name"><br><br>
      Введите журнал для публикации статьи:<input type=text name="journal_name"  required value="journal_name"><br><br>
      Введите страницы публикации: <input type=text name="pages"  required value="pages"><br><br>
      Содержание статьи <br>
      <textarea cols="80" rows="10" name="art_text"  required>Что-то очень умное</textarea><br><br>
      Заблокировать статью:
      <p>
        <input name="art_blocked" type="radio" value=1> 
        Да 
        <input name="art_blocked" type="radio" value=0> 
        Нет</p>
      <input id="send_article-data" type=submit value="Добавить материал" name="send_article_data">
    </form>
    <hr><hr>
  
  <h1>Поиск</h1>
    <p>Поиск новостей по автору:</p>
    <form id="search-form" method="post" action="test_script.php?req_type=search">
      <input type=text name="search_word" placeholder="Автор">
      <input type=hidden name="search_table" value="articles">
      <input type=hidden name="search_field" value="author">
      <input type=submit name=search_by_author value="Поиск">
    </form>
    <?php
        if($query_result->search_type === "author"){
          if($query_result->num_rows > 0){
            show_s_results($query_result);
          } 
        } else if($query_result->search_type === "author_not_found"){
            echo "Поиск не дал результатов";
        }
    ?>
  
  <p>Поиск новостей по названию статьи:</p>
    <form id="search-form" method="post" action="test_script.php?req_type=search">
      <input type=text name="search_word" placeholder="Статья">
      <input type=hidden name="search_table" value="articles">
      <input type=hidden name="search_field" value="name">
      <input type=submit name=search_by_art_name value="Поиск">
    </form>

  <?php
      if($query_result->search_type === "name"){
          if($query_result->num_rows > 0){
            show_s_results($query_result);
          } 
        } else if($query_result->search_type === "name_not_found"){
            echo "Поиск не дал результатов";
        }
    ?>
  <hr><hr>

  <h1>Вывод статей журнала</h1>
  
  <form action="script.php" method="post">
    <select id="choose-jour" size="1" name="journal">
      <option disabled selected="true">Журнал</option>
      <?php
        $result = select_from_db($connection,"journals");
        while( $row = $result->fetch_assoc() ){ 
        echo "<option>".$row['type']." №".$row['number']." ".$row['date']."</option>";
      }
      ?>
    </select>
    <input type="submit" value="Отправить">
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

  <hr><hr>

  <h1>Вывод авторов</h1>
  <table class="table table-hover">
    <tr>
      <th>Автор</th>
      <th>Организация</th>
    </tr>
    <?php
      $result = select_from_db($connection,"users");
      while( $row = $result->fetch_assoc() ){ 
        echo "<tr>";
        echo "<td>".$row['surname']." ".$row['name']." ".$row['lastname']."</td>";
        echo "<td>".$row['organisation']."</td>";
        echo "</tr>";
      }
    ?>
  </table>

  <hr><hr>

    <script src="lib/jquery/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
    <script src="lib/jquery/jquery.json.js"></script>
    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>