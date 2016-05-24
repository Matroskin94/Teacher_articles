<?php
include("script.php");
  //header("Content-Type: text/html; charset=utf-8");
$connection = db_connect();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Bootstrap 101 Template</title>

  <!-- Bootstrap -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/sweapeable_menu.css">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script src="lib/jquery/jquery-1.12.0.min.js"></script>
      <script src="lib/jquery/jquery-ui.min.js"></script>
      <script src="lib/jquery/jquery.json.js"></script>
    </head>
    <body>

      <?php
      $query_result = select_script($connection);
      ?>

      <form id="add-art-form" class="reg-form" method="post">
        <br>
        <div class="col-sm-12">
          <p>Введите название статьи: </p>
        </div>
        <div class="col-sm-12">
          <p><input class="form-control redact-input" required type=text name="art_name"  required placeholder="Название" value=""></p>
        </div>
        <div class="col-md-4">
          <p id="choose_journ_p">Выберите серию журнала:</p>
        </div>
        <div class="col-md-3 col-md-offset-1">
          <select size="1" class="class_select form-control" id="choose-journal-class" name="jour_class">
            <option disabled selected="true"> Серия </option>
            <option>A</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
            <option>E</option>
          </select>
        </div>
        <div class="col-md-4">
          <p id="art-not-exist"></p>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-4">
          <p>Выберите журнал: </p>
        </div>
        <div class="col-md-3 col-md-offset-1">
         <select disabled class="choose-jour form-control jour_select" id="avail_journals" name="journal">
          <option disabled selected>Журнал</option>
        </select>
      </div>
      <div class="clearfix"></div>
      <div class="col-sm-5">
       <p>Введите страницы публикации:</p> 
     </div> 

     <div class="col-sm-7">
      <input class="form-control search_art" type=text name="pages"  required placeholder="Страницы">
    </div>
    <div class="clearfix"></div>
    <div class="author_selectors" id="adding_auth_name">
      <div class="clearfix"></div>
      <div class="col-sm-12">
        <div id="add_author_new_art"></div>
      </div>
      <div class="clearfix"></div> 
      <div class="col-sm-5">
        <p>Выберите автора публикации:</p> 
      </div>
      <div class="col-sm-7">
        <p><img id="search_auth_icon" src="img/search_icon.png" data-toggle="modal" data-target="#authors_modal"></p>
      </div>
    </div>

    <div class="col-md-12">
      <button onclick="return false" class="btn btn-primary" id="send-article-data" name="send_article_data"> Добавить материал </button>
    </div>
  </form>

  <div id="authors_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
          <h4 class="modal-title">Выберите автора</h4>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-xs-5">
              <input id="n_auth_s" class="form-control redact-input" type=text name="pages"  required placeholder="Автор">
            </div>
            <button id="new_auth_search" class="btn btn-primary">Поиск</button>
          </div>
          <div class="row">
            <div class="col-md-12">
              <p id="n_auth_s_p"></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 table-responsive">
              <table class="table table-hover" hidden id="authors-search">
                <tr class="table-head">
                  <th>Автор</th>
                  <th>Научная степень</th>
                  <th>Организация</th>
                </tr>
              </table>
            </div>
          </div>

          <button class="btn btn-primary" id="add_new_auth">Добавить автора</button>

          

        </div>
        <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
      </div>
    </div>
  </div>


  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/app.js"></script>
</body>
</html>

