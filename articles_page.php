<?php
  include("script.php");
  //header("Content-Type: text/html; charset=utf-8");
  $connection = db_connect();
?>

<!DOCTYPE <!DOCTYPE html>
<html>
<head>
	 <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Вестник ПГУ</title>

  <!-- Bootstrap -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/test_script.css">
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


	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 main-field"><!-- Основное поле -->
				<h1>Вестник ПГУ</h1>
				<div class="row">
					<div class="col-lg-6 col-md-7">
						<form id="search-form" method="post" action="articles_page.php">
							<input class="form-control search_art" type=text name="search_author" placeholder="Автор">
							<input class="form-control search_art" type=text name="search_name" placeholder="Статья">
							<button class="btn btn-default" id="search_but" name="search_but">Поиск</button>
						</form>					
					</div>
					
					<div class="col-lg-offset-2 col-lg-4 col-md-offset-0 col-md-5">
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
					<!--<div class="col-md-3 col-xs-6">
										
					</div>-->
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
						<div class="row">
							<div class="col-md-12">
								<button hidden id="show_art_data" class="btn btn-default" >Данные о статье</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<p id="article_str"></p>
							</div>
						</div>
					</div>
				</div>


			</div>
			<div class="col-md-2"></div>
		</div>
	</div>

    <script src="js/app.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>