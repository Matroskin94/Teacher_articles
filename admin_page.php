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
                <h2 id="page-id">Публикации</h2>
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
                        <li><a href="#articles-tab">Публикации</a></li>
                        <li class="active-item"><a href="#authors-tab">Авторы</a></li>
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

                    <!-- Вкладка статей -->


                    <div class="content-tab" id="articles-tab">
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
                          

                          <!-- Редактирование статьи -->

                          <form id="redact-article-form" method="POST" class="hidden" action="test_script.php?req_type=redact_article">
                            <br>
                            <div class="col-sm-12">
                              <p>Введите название статьи: </p>
                            </div>
                            <div class="col-sm-12">
                              <p><input class="form-control redact-input" required type=text name="art_name"  required value=""></p>
                            </div>
                            <br>
                            <div class="col-sm-6">
                              <p>Введите страницы публикации:</p>
                            </div>

                            <div class="col-sm-offset-2 col-sm-4">
                              <input class="form-control redact-input" required type=text name="pages"  required value="">
                            </div>
                            <div class="col-sm-12">
                              <table class="table" id="auth-redact-data">
                                <tbody>
                                 <caption>Авторы статьи</caption>
                               </tbody>
                             </table>
                             <p><button onclick="return false" class="btn btn-primary" id="redact_add_auth">Добавить автора</button></p>
                           </div>

                           <div class="author_selectors hidden" id="redacting_auth_selector">
                            <div class="col-sm-5">
                              <p>Выберите автора публикации:</p>
                            </div> 
                            <div class="col-sm-7">
                              <select name="author0" class="avail_authors form-control">
                                <option disabled="true" selected="true">Автор</option>
                              </select>
                            </div>
                            <div class="col-sm-12"><p><button class="btn btn-primary" onclick="return false" id="add_author_red_art">Ещё автор</button></p></div>
                          </div>

                          <div class="col-sm-12"><p><button class="btn btn-primary" id="save-change-art" onclick="return false">Сохранить изменения</button></p></div>
                          
                        </form>

                        <!-- Добавление материала -->


                        <form hidden id="add-art-form" class="reg-form" method="post" action="admin_page.php?req_type=new_article">
                          <br>
                          <div class="col-sm-12">
                            <p>Введите название статьи: </p>
                          </div>
                          <div class="col-sm-12">
                            <p><input class="form-control redact-input" required type=text name="art_name"  required value=""></p>
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
                        <input class="form-control search_art" type=text name="pages"  required value="pages">
                      </div>
                      <div class="clearfix"></div>
                      <div class="author_selectors" id="adding_auth_selector">
                        <div class="clearfix"></div> 
                        <div class="col-sm-5">
                          <p>Выберите автора публикации:</p> 
                        </div>
                        <div class="col-sm-7">
                          <select disabled name="author0" id="add-art-avail-auth" class="form-control avail_authors">
                            <option disabled="true" selected="true">Автор</option>
                          </select>
                        </select>
                      </div>

                      <div class="col-md-12">
                        <p><button class="btn btn-primary" onclick="return false" id="add_author_new_art">Ещё автор</button></p>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <button class="btn btn-primary" id="send-article-data" name="send_article_data"> Добавить материал </button>
                    </div>
                  </form>

                  <br>

                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                 <button class="btn btn-default" id="redact-article" hidden>Редактировать</button>
                 <button class="btn btn-default" id="delete-article" hidden>Удалить</button>
               </div>
               <div class="col-md-offset-3 col-md-3">
                <button class="btn btn-default" id="add-art-but" name="new_article_but">Добавить статью</button>
              </div>
            </div>

          </div>

          <!-- Вкладка авторов -->

          <div class="content-tab  active-tab" id="authors-tab">
            <h2>Авторы статей</h1>
            <div class="row">
              <div class="col-xs-5 col-sm-5 col-md-5">
                <p>Выберите сурию журнала:</p>  
              </div>
              <div class="col-md-3 col-sm-3 col-xs-3">
                <select size="1" name="journal-class" class="form-control class_select" id="vew_author_by_class">
                <option disabled selected="true">Серия</option>
                <option>A</option>
                <option>B</option>
                <option>C</option>
                <option>D</option>
                <option>E</option>
              </select>
              </div>
              <div class="col-md-4 col-md-offset-0 col-sm-offset-0 col-sm-4 col-xs-4">
                <button class="btn btn-default" id="add-art-but" name="new_article_but">Добавить автора</button>
              </div>

            </div> 
              <table class="table table-hover hidden" id="authors-data">
                <tr class="table-head">
                  <th>Автор</th>
                  <th>Научная степень</th>
                  <th>Организация</th>
                </tr>
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

                </div> 


                <!-- Вкладка журналы -->
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