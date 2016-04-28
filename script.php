<?php
header("Content-Type: text/html; charset=utf-8");

define('HOST', 'localhost');
define('USER', 'admin');
define('PASSWORD', '1111');
define('DB', 'teacher_articles');
/*Подключение к БД*/

function db_connect()
{
	$mysqli = new mysqli(HOST, USER, PASSWORD, DB);
	if(!$mysqli){
		return false;
	}else{
		return $mysqli;
	}
}

/*Выборка из базы данных*/
function select_from_db($mysqli,$table){
	if ($result = $mysqli->query('SELECT * FROM '.$table)) { 
		return $result;
		/* Выборка результатов запроса */ 
		/*while( $row = $result->fetch_assoc() ){ 
			echo "<hr>";
			echo "Фамилия:".$row['surname']."<br>";
			echo "Имя:".$row['name']."<br>";
			echo "Отчество:".$row['lastname']."<br>";
		} */
		/* Освобождаем используемую память */ 
		//$result->close();
	}
}
/*Вставка записи в таблицу*/
function insert_to_db($mysqli,$form_type){
	/*Подготовка шаблонного выражения*/
	$args_num = func_num_args();
	$arg_list = func_get_args();
	switch ($form_type) {
		case 'register':
		$stmt = $mysqli->prepare("INSERT INTO `users` VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
		$stmt->bind_param("isssssss", $arg_list[2], $arg_list[3], $arg_list[4], $arg_list[5], $arg_list[6], $arg_list[7], $arg_list[8],$arg_list[9]);
		break;
		case 'new_article' :
		$qr = "INSERT INTO `articles` (`article_id` ,`author` ,`name` ,`pages` ,`article_text` ,`date` ,`author_id` ,`journal_id`,`blocked`) VALUES (NULL, '".$arg_list[2]."', '".$arg_list[3]."', '".$arg_list[4]."', '".$arg_list[5]."', CURRENT_TIMESTAMP , NULL , NULL, ".$arg_list[8].")";
		$result = $mysqli->query($qr);
		break;
		default:

		break;
	}
	/* выполнение подготовленного выражения  */ 
	if(is_object($stmt)){
		$stmt->execute(); 
		$stmt->close();
	}
	
}

function update_data_in_db($mysqli){
	/*Изменение записи в таблице*/
	$stmt = $mysqli->prepare("UPDATE `users` SET nickname = ?, password = ? WHERE id=1");
	$stmt->bind_param("ss", $nickname, $pass); 
	$nickname = 'Like_a_BIG_BOSS';
	$pass = 'microcontroller';
	$stmt->execute();
	/* END Изменение записи в таблице*/
}


/*Поиск статей*/

function find_article($mysqli,$s_table,$s_line,$s_word){
	$arg_list = func_get_args();
	$qr = "SELECT * FROM ".$s_table." WHERE ".$s_line." LIKE '%$s_word%'";
	$result = $mysqli->query($qr);
	$result->search_type = $s_line;
	if($result->num_rows != 0){
		/*while( $row = $result->fetch_assoc() ){ 
			echo "<hr>";
			echo "Название:".$row['name']."<br>";
			echo "Автор:".$row['author']."<br>";
			echo "Текст:".$row['article_text']."<br>";
			echo "<hr>";
		}*/
		return $result;
	}else{
		/*echo "<hr>";
		echo "Поиск не дал результатов!";
		echo "res_type:".$result->test;*/
		if($s_line == "author"){
			$result->search_type = "author_not_found";
		} else if($s_line == "name"){
			$result->search_type = "name_not_found";
		}
		return $result;
	}

}

/* Вывод статей по авторам*/
function show_s_results($arr)
{
	while( $row = $arr->fetch_assoc() ){ 
		echo "<hr>";
		echo "Название:".$row['name']."<br>";
		echo "Автор:".$row['author']."<br>";
		echo "Текст:".$row['article_text']."<br>";
		echo "<hr>";
	}
	$arr->free();
}


/*Выбор сценария обработки запроса*/
function select_script($mysqli)
{	
	$script_result = NULL;
	if(isset($_GET['req_type'])){
		switch ($_GET['req_type']) {
			case 'register':
			insert_to_db($mysqli,"register", NULL, $_POST['nickname'],$_POST['pass'],$_POST['surname'],$_POST['name'],$_POST['lastname'],$_POST['dc_degree'],$_POST['organisation']);
			unset($_GET['req_type']);
					//echo '<script>location.replace("script.php");</script>'; exit;
			header ('Location: test_script.php');
			$script_result = "user_registred";
			break;
			case 'new_article' :
			$script_result = insert_to_db($mysqli,"new_article",$_POST['author'],$_POST['art_name'],$_POST['pages'],$_POST['art_text'],NULL,NULL, (int)$_POST['art_blocked']);
			unset($_GET['req_type']);
			echo '<script>location.replace("test_script.php");</script>'; exit;
			//header ('Location: test_script.php');
			//$script_result = "article_added";
			break;
			case 'search': 
			$script_result = find_article($mysqli, $_POST['search_table'],$_POST['search_field'],$_POST['search_word']);
			unset($_GET['req_type']);
			//echo '<script>location.replace("test_script.php");</script>';
			//header ('Location: test_script.php');
			break;
			default:
				# code...
			break;
		}
		return $script_result;
	}

}

if(isset($_GET['req_type'])){
	$mysqli = db_connect();
	switch ($_GET['req_type']) {
		case 'ajax_ch_jour':
			$data = json_decode($_POST['jsonData']);
			$curr_batch = '';
			$curr_numb = '';
			$jour_articles = ''; 
			foreach ($data as $key=>$value) {
				//$response .= 'Параметр: '.$key.'; Значение: '.$value.'';
				if($key == 'jour_batch'){
					$curr_batch = $value;
				}
				if($key == 'jour_numb'){
					$curr_numb = $value;
				}
			}
			$jour_id = $mysqli->query("SELECT `journal_id` FROM `journals` WHERE type = '".$curr_batch."' AND number = ".$curr_numb."");
			$row = $jour_id->fetch_assoc();
			$jour_id = $row['journal_id'];
			$jour_articles = $mysqli->query("SELECT * FROM `articles` WHERE journal_id = '".$jour_id."'");
			$i = 0;
			//echo var_dump($jour_articles);
			$json_data = array();
			$single_article = array();
			while ($row = $jour_articles->fetch_assoc()) {
				$json_data[$i] = $row;
				$i++;
			}
			$response = json_encode($json_data);
			echo $response;
			$mysqli->close();
		break;
		case 'ajax_bl_art':
			$art_name = '';
			$art_stat = '';
			$data = json_decode($_POST['jsonData']);
			foreach ($data as $key=>$value) {
				//$response .= 'Параметр: '.$key.'; Значение: '.$value.'';
				if($key == 'art_name'){
					$art_name = $value;
					//$result .= "art_name".$art_name;
				}
				if($key == 'art_stat'){
					$art_stat = $value;
				}
			}

			$qr = "UPDATE `articles` SET blocked = ".(int)$art_stat." WHERE name = '".$art_name."'";
			$result = $mysqli->query($qr);
			//$response = json_encode($result);
			$mysqli->close();
			echo $result;

		break;
		default:
			select_script($mysqli);
		break;
	}
}

//$mysqli->close();


?>

