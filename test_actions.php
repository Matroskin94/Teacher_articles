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
  
  <?php
      $query_result = select_script($connection);
    ?>

   <h1>Поиск</h1>
      <p>Поиск новостей по автору:</p>
      <form id="search-form" method="post" action="test_actions.php">
        <input class="search_art" type=text name="search_author" placeholder="Автор">
        <p>Поиск новостей по названию статьи:</p>
        <input class="search_art" type=text name="search_name" placeholder="Статья">
        <p><button id="search_but" name="search_but">Поиск</button></p>
      </form>
      <?php
      if($query_result[count($query_result)-1] === "search"){
        show_s_results($query_result);
      }
    if($query_result === "not_found"){
        echo "Поиск не дал результатов";
      }
      ?>
    <!-- <p><button id="show_art_data" hidden>Данные о статье</button></p> -->
    <hr><hr>


    <script src="lib/jquery/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
    <script src="lib/jquery/jquery.json.js"></script>
    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

