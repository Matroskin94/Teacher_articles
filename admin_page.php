<?php
header("Content-Type: text/html; charset=utf-8");
include("script.php");
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

      <div class="container min-screen">
        <div class="row">
          <div class="col-md-8 col-md-offset-2 main-field">
            <!-- <h1>Авторы</h1> -->
            <div class="row">
              <div class="col-xs-1 col-sm-3 col-md-3 vert-menu">
                <h2 id="page-id">Авторы</h2>
                <div class="container container-menu open-sidebar">
                  <div class="row">
                    <div id="sidebar" class='col-md-12'>
                      <div class="menu-icon">
                        <a href="#" data-toggle=".container" id="sidebar-toggle">
                          <span class="bar"></span>
                          <span class="bar"></span>
                          <span class="bar"></span>
                        </a>
                      </div>
                      <ul id="main-menu">
                        <li class="active-item"><a href="#articles-tab">Публикации</a></li>
                        <li><a href="#authors-tab">Авторы</a></li>
                        <li><a href="#journals-tab">Журналы</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Горизонтальное меню -->
              <div class="col-xs-11 col-sm-9 col-md-9 info-field">
                <!-- Основное поле -->
                <div class="row">
                  <div class="col-md-12"> <!-- content field -->
                    <div class="content-tab active-tab" id="articles-tab">
                      <h1>Вестник ПГУ</h1>
                      <div class="row">
                        <div class="col-lg-12 col-md-12">
                          <form id="search-form" method="post" action="admin_page.php#articles-tab">
                            <input class="form-control search_art" type=text name="search_author" placeholder="Автор">
                            <input class="form-control search_art" type=text name="search_name" placeholder="Статья">
                            <button class="btn btn-default" id="search_but" name="search_but">Поиск</button>
                          </form>         
                        </div>

                        <div class="col-sm-12">
                          <select size="1" name="journal-class" class="form-control class_select" id="vew_journ_class">
                            <option disabled selected="true">Серия</option>
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                            <option>E</option>
                          </select>   

                          <select id="journals" class="choose-jour form-control jour_select" size="1" name="journal" disabled="true">
                            <option disabled selected="true">Журнал</option>
                          </select>     
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                        <br>
                          <p id="message_p">Выберите необходимый журнал либо воспользуйтесь поиском</p>
                          <div class="table-responsive result-table">
                            <table class="table table-hover" id="article-data" hidden style="opacity: 1">
                              <tr class="table-head">
                                <th>Авторы</th><th>Статья</th><th>Страницы</th><th>Журнал</th>
                              </tr>
                              <?php
                              if($query_result[count($query_result)-1] === "search"){
                                show_s_results($query_result);
                                ?>
                                <script type="text/javascript">
                                  $("#article-data").fadeIn();
                                  $("#message_p").text("Результат");
                                </script>
                                <?php
                              }
                              if($query_result === "not_found"){
                                echo "Поиск не дал результатов";
                              }
                              ?>
                            </table>
                          </div>
                          <!--<p><br><button id="show_art_data" hidden>Данные о статье</button></p>-->
                          <form id="redact-article-form" method="POST" class="hidden" action="test_script.php?req_type=redact_article">
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
                        <br>
                        <button id="redact-article" hidden>Редактировать</button>
                        <button id="delete-article" hidden>Удалить</button>
                        </div>
                      </div>

                    </div>


                    <div class="content-tab" id="authors-tab">
                      <h2>Авторы статей</h1>
                        <div class="table-responsive">
                          <table class="table table-hover">
                            <tr>
                              <th>Авторы</th>
                              <th>Организация</th>
                            </tr>
                            <tr>
                              <td>Богуш Рихард Петрович</td>
                              <td>ПГУ</td>
                            </tr>
                            <tr>
                              <td>Калинцев Сергей Викторович</td>
                              <td>ПГУ</td>
                            </tr>
                            <tr>
                              <td>Руголь Дмитрий Генадьевич</td>
                              <td>ПГУ</td>
                            </tr>
                            <tr>
                              <td>Травкин Олег Николаевич</td>
                              <td>ПГУ</td>
                            </tr>
                          </table>
                        </div>
                        <div class="row min-screen">
                          <div class="menu-frame">
                            <ul class="horizontal-menu">
                              <li id="redact-authors"><a href="#">Редактировать</a></li>
                              <li id="delete-author"><a href="#">Удалить</a></li>
                              <li><a href="#">Добавить</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div> 

                    <div class="content-tab" id="journals-tab">
                      <h2>Journals</h2>

                    </div>
                    

                  </div> <!-- content field -->
                </div>
              </div>
            </div>

          </div>


          <script src="lib/bootstrap/js/bootstrap.min.js"></script>
          <script src="js/app.js"></script>
        </body>
        </html>