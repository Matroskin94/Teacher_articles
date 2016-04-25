<?php
header("Content-Type: text/html; charset=utf-8");
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
    </head>
    <body>

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
                      <ul>
                        <li><a href="#">Публикации</a></li>
                        <li class="active-item"><a href="#">Авторы</a></li>
                        <li><a href="#">Журналы</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Горизонтальное меню -->
              <div class="col-xs-11 col-sm-9 col-md-9 info-field">
                <!-- Основное поле -->
                  <div class="row">
                    <div class="col-md-12">
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

                    </div>
                  </div>
                  <div class="containder">
                  <div class="row min-screen">
                    <div class="menu-frame">
                      <ul class="horizontal-menu">
                        <li id="redact-authors"><a href="#">Редактировать</a></li>
                        <li><a href="#">Добавить</a></li>
                        <li><a href="#">Удалить</a></li>

                      </ul>
                    </div>

                  </div>
                </div>



              </div>
            </div>
          </div>

        </div>



        <script src="lib/jquery/jquery-1.12.0.min.js"></script>
        <script src="lib/jquery/jquery-ui.min.js"></script>
        <script src="lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="js/app.js"></script>
      </body>
      </html>