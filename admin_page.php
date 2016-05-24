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
                        <li><a href="#authors-tab">Авторы</a></li>
                        <li class="active-item"><a href="#journals-tab">Журналы</a></li>
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
                          <h4 id="message_p">Выберите необходимый журнал либо воспользуйтесь поиском</h4>
                          <div class="table-responsive result-table">
                            <table class="table table-hover hidden" id="article-data" style="opacity: 1">
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
                                ?>
                                <script type="text/javascript">
                                  $("#article-data").fadeIn();
                                  $("#message_p").text("Поиск не дал результатов");
                                </script>
                                <?php
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
                            <table class="table" hidden id="auth-redact-data">
                                <tbody>
                                 <caption>Авторы статьи</caption>
                               </tbody>
                             </table>
                           </div>

                           <div class="author_selectors" id="adding_auth_name">
                            <div class="clearfix"></div>
                            
                            <div class="clearfix"></div> 
                            <div class="col-sm-5">
                              <p>Выберите автора публикации:</p> 
                            </div>
                            <div class="col-sm-7">
                              <p><img id="search_auth_icon" src="img/search_icon.png" data-toggle="modal" data-target="#authors_modal"></p>
                            </div>
                          </div>

                          <div class="col-sm-12"><p><button class="btn btn-primary" id="save-change-art" onclick="return false">Сохранить изменения</button></p></div>
                          
                        </form>

                        <!-- Добавление материала -->


                        <form id="add-art-form" hidden class="reg-form" method="post">
                          <br>
                          <div class="col-sm-12">
                            <p>Введите название статьи: </p>
                          </div>
                          <div class="col-sm-12">
                            <p><input class="form-control redact-input" required type=text name="art_name"  required placeholder="Название" value=""></p>
                          </div>
                          <div class="col-sm-5">
                            <p id="choose_journ_p">Выберите серию журнала:</p>
                          </div>
                          <div class="col-md-3">
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
                      <div class="col-sm-12">
                            <table class="table" hidden id="auth-redact-data">
                                <tbody>
                                 <caption>Авторы статьи</caption>
                               </tbody>
                             </table>
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

                    <br>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-7">
                   <button class="btn btn-default" id="redact-article" hidden>Редактировать</button>
                   <button class="btn btn-default" id="delete-article" hidden>Удалить</button>
                 </div>
                 <div class="col-md-offset-2 col-md-3 col-sm-offset-3 col-sm-2 col-lg-offset-3 col-lg-3 col-xs-offset-1 col-xs-4">
                  <button class="btn btn-default" id="add-art-but" name="new_article_but">Добавить статью</button>
                </div>
              </div>

            </div>

            <!-- Вкладка авторов -->

            <div class="content-tab" id="authors-tab">
              <h2>Авторы статей</h1>
                <div class="row">
                  <div class="col-xs-5 col-sm-5 col-md-5">
                    <p>Выберите сtрию журнала:</p>  
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
                    <button class="btn btn-default" id="add-aut-but" name="new_article_but">Добавить автора</button>
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
                <button class="btn btn-default" id="redact-authors">Редактировать</button>
                <button class="btn btn-default" id="delete-author">Удалить</button>
                <form id="redact-author-form" hidden name="redact_author">

                  <div class="col-md-4">
                    <p>Фамилия имя отчество:</p>
                  </div>
                  <div class="col-md-7 col-md-offset-1">
                    <input required placeholder="ФИО" class="form-control redact-input" type="text">
                  </div>
                  <div class="clearfix"></div>

                  <div class="col-md-4">
                    <p>Научная степень</p>
                  </div>
                  <div class="col-md-3 col-md-offset-1">
                    <input required placeholder="Степень" class="form-control redact-input" type="text">
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4">
                    <p>Организация</p>
                  </div>
                  <div class="col-md-3 col-md-offset-1">
                    <input placeholder="Организация" class="form-control redact-input" required type="text">
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4">
                    <p>Серия журналов для публикации</p>
                  </div>    
                  <div class="col-md-4 col-md-offset-1">
                    <select class="class_select form-control" id="choose-type" size="1" name="type">
                      <option disabled selected="true">Серия</option>
                      <option>A</option>
                      <option>B</option>
                      <option>C</option>
                      <option>D</option>
                      <option>E</option>
                      <select>
                      </div>
                      <div class="col-md-12">
                        <p hidden ><button class="btn btn-default" onclick="return false" id="update-author">Сохранить изменения</button></p>
                        <p hidden><button class="btn btn-primary" onclick="return false" id="new-author">Добавить автора</button></p>
                      </div>
                    </form>

                  </div> 


                  <!-- Вкладка журналы -->
                  <div class="content-tab active-tab" id="journals-tab">
                    <h2>Журналы</h2>
                    <div class="row">
                      <div class="col-md-4 col-sm-4 col-xs-4">
                        <p id="journals-res">Выберите серию журнала:</p>  
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-3">
                        <select size="1" name="journal-class" class="form-control class_select" id="vew_journal_by_class">
                          <option disabled selected="true">Серия</option>
                          <option>A</option>
                          <option>B</option>
                          <option>C</option>
                          <option>D</option>
                          <option>E</option>
                        </select>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-3">
                        <select disabled size="1" name="journal-class" class="form-control class_select" id="vew_journal_year">
                          <option disabled selected="true">Год</option>
                        </select>
                      </div>
                      <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-0 col-sm-offset-0 col-sm-offset-1">
                        <button class="btn btn-default" id="add-jour-but" name="new_journal_but">Добавить журнал</button>
                      </div>
                    </div>

                    <table class="table table-hover hidden" id="journals-data">
                      <tr class="table-head">
                        <th>Название</th>
                        <th>Серия</th>
                        <th>Номер</th>
                        <th>Год</th>
                        <th>Страниц</th>
                      </tr>
                    </table>

                    <form id="add-journal-form" hidden method="post">
                      <div class="col-sm-12">
                        <p>Введите название журнала: </p>
                      </div>
                      <div class="col-sm-12">
                        <p><input class="form-control redact-input" type="text" name="journal_name" required placeholder="Журнал"></p>
                      </div>
                      <div class="col-md-4">
                        <p>Выберите класс журнала:</p>
                      </div> 
                      <div class="col-md-3 col-md-offset-1">
                       <select class="class_select form-control" id="choose-type" size="1" name="type">
                        <option disabled selected="true">Серия</option>
                        <option>A</option>
                        <option>B</option>
                        <option>C</option>
                        <option>D</option>
                        <option>E</option>
                        <select> 
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                         <p>Введите год публикации:</p>
                       </div>
                       <div class="col-md-3 col-md-offset-1">
                         <input class="form-control redact-input" type="text" name="pub_year" required placeholder="Год">
                       </div>
                       <div class="clearfix"></div>
                       <div class="col-md-4">
                        <p>Введите номер журнала:</p>
                      </div> 
                      <div class="col-md-3 col-md-offset-1">
                        <input class="form-control redact-input" type="text" name="journal_number" required placeholder="Номер">
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-4">
                        <p>Введите количество страниц:</p>
                      </div>  
                      <div class="col-md-3 col-md-offset-1">
                        <input class="form-control redact-input" type="text" name="journal_pages" required placeholder="Страницы">
                      </div>
                      <div class="col-md-12">
                        <p hidden ><button onclick="return false" class="btn btn-primary" id="new_journal_but" name="send_article_data"> Добавить журнал </button></p>
                        <p hidden ><button class="btn btn-primary" onclick="return false" id="update_jour_but">Сохранить изменения</button></p>
                      </div>
                    </form>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-7">
                       <button class="btn btn-default" id="redact-journal" hidden>Редактировать</button>
                       <button class="btn btn-default" id="delete-journal" hidden>Удалить</button>
                     </div>




                   </div>
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