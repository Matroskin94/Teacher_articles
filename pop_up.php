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
      <button class="btn btn-info btn-lg" type="button" data-toggle="modal" data-target="#myModal">Показать всплывающее окно</button>
      <div id="myModal" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
              <h4 class="modal-title">Заголовок окна</h4>
            </div>
            <div class="modal-body">Текст уведомления</div>
            <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
          </div>
        </div>
      </div>

      <script src="lib/bootstrap/js/bootstrap.min.js"></script>
      <script src="js/app.js"></script>
    </body>
    </html>