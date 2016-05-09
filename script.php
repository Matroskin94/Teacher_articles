<?php
header("Content-Type: text/html; charset=utf-8");

define('HOST', 'localhost');
define('USER', 'admin');
define('PASSWORD', '1111');
define('DB', 'nastej_db');
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
function select_from_db($mysqli,$field,$table,$row,$value){
	$args_num = func_num_args();
	$args_list = func_get_args();
	if($args_num == 5){
		if ($result = $mysqli->query("SELECT ".$field." FROM ".$table." WHERE ".$row." = '".$value."'")) { 
			return $result;
		}else{
			return $args_num;
		}
	}
	if($args_num == 7){
		if ($result = $mysqli->query("SELECT ".$field." FROM `".$table."` WHERE `".$row."` = '".$value."' AND `".$args_list[5]."` = '".$args_list[6]."'")) { 
		//if($result = $mysqli->query("SELECT * FROM `journals` WHERE `class` = 'C' AND `blocked` = 0")){
			return $result;
		}else{
			return $args_num;
		}
	}
	if($args_num == 9){
		if($result = $mysqli->query("SELECT ".$field." FROM `".$table."` WHERE `".$row."` = '".$value."' AND `".$args_list[5]."` = '".$args_list[6]."' AND`".$args_list[7]."` = '".$args_list[8]."'")){
			return $result;
		}else{
			return "false";
		}
	}

}



/*Вставка записи в таблицу*/
function insert_to_db($mysqli,$form_type){ //($mysqli,$form_type,art_name, journal, pages)
	/*Подготовка шаблонного выражения*/
	$args_num = func_num_args();
	$arg_list = func_get_args();
	$added_id = "";
	$lit_curr = "literature_name0";
	$auth_curr = "";
	$pages_curr = "";
	$curr_id = NULL;
	$lit_num = 0;
	/*for($i = 1;$i<$args_num;$i++){
		echo "arg[".$i."]: ".$arg_list[$i]."<br>";
	}*/
	switch ($form_type) {
		case 'add_author':
			$stmt = $mysqli->prepare("INSERT INTO `authors` VALUES (?, ?, ?, ?, ?)"); 
			$stmt->bind_param("issss", $arg_list[2], $arg_list[3], $arg_list[4], $arg_list[5], $arg_list[6]);
		break;

		case 'add_journal':
			$stmt = $mysqli->prepare("INSERT INTO `journals` VALUES (?, ?, ?, ?, ?, ?, ?)"); 
			$stmt->bind_param("issiiii", $arg_list[2], $arg_list[3], $arg_list[4], $arg_list[5], $arg_list[6], $arg_list[7], $arg_list[8]);
			break;


		case 'new_article' :
			$qr = "INSERT INTO `articles` (`article_id` ,`art_name` ,`art_pages` ,`journal_id`) VALUES (NULL,
			'".$arg_list[2]."', '".$arg_list[3]."', '".$arg_list[4]."')";
			//echo "QUERY: ".$qr;
			$result = $mysqli->query($qr);
		
			$added_id = $mysqli->insert_id;
			return $added_id;
			/*while (isset($_POST[$lit_curr])) {
				$lit_num += 1;
				$lit_curr = "literature_name".$lit_num;
			}
			$stmt = $mysqli->prepare("INSERT INTO `lit_sources` VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("isssi", $curr_id, $lit_curr, $auth_curr, $pages_curr, $added_id);
			for($i = 0; $i < $lit_num; $i++){
				$lit_curr = $_POST["literature_name".$i.""];
				$auth_curr = $_POST["literature_authors".$i.""];
				$pages_curr = $_POST["literature_pages".$i.""];
				$stmt->execute();
			}	
			$stmt->close();*/
			//var_dump($result);
			return $added_id;
		break;
		case 'add_link':
			
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

/*формирование списка авторов статьи*/

function authors_link_art($mysqli,$article_id){
	$curr_auth = "author0";
	$new_link_id = NULL;
	$res_row = "";
	$auth_id = "";
	$auth_num = 0;
	$auth_name - "";
	$authors = array();
	$sel_res = "";
	//$stmt = $mysqli->prepare("SLECT author_id FROM `authors` WHERE name = ?");
	//$stmt->bind_param("s", $curr_auth);
	$stmt = $mysqli->prepare("INSERT INTO `article_author` VALUES (?, ?, ?)"); 
	$stmt->bind_param("iii", $new_link_id, $auth_id, $article_id);
	//var_dump($_POST);
	while (isset($_POST[$curr_auth])) {
		$auth_name = $_POST[$curr_auth];
		echo "curr_auth:".$auth_name."<br>";
		$sel_res = select_from_db($mysqli, "author_id","authors","name",$auth_name);
		//var_dump($sel_res);
		$res_row = $sel_res->fetch_assoc();
		$auth_id = $res_row['author_id'];
		echo "curr_auth_id:".$auth_id."<br>";
		insert_to_db($mysqli,"add_link",$res_row->author_id);
		if(is_object($stmt)){
			$stmt->execute();
		}
		$auth_num += 1;
		$curr_auth = "author".$auth_num; 
		echo "next_author:".$curr_auth."<br>";
	}

	$stmt->close();

	/*if(is_object($stmt)){
		$stmt->execute(); 
		$stmt->close();
	}*/
	//select_from_db($mysqli, "author_id","authors","name",$auth_name);
}


function update_data_in_db($mysqli, $update_table){
	/*Изменение записи в таблице*/
	//$stmt = $mysqli->prepare("UPDATE `users` SET nickname = ?, password = ? WHERE id=1");
	//$stmt->bind_param("ss", $nickname, $pass); 
	//$art_literature->author[0]
	$args_num = func_num_args();
	$arg_list = func_get_args();
	switch ($update_table) {
		case 'article_update':
			//var_dump($arg_list);
			$name = "";
			$author = "";
			$pages = "";
			$curr_id = (int)$arg_list[7];
			//$qr = "UPDATE `articles` SET `author` = '".$arg_list[2]."', `name` = '".$arg_list[3]."', `pages` = '".$arg_list[4]."', `article_text` = '".$arg_list[5]."' WHERE `article_id` = ".$arg_list[7]."";
			//$result = $mysqli->query($qr);
			//echo "qr:".$qr."\n";
			//echo "res:".$result."\n";
			//$qr = "UPDATE `lit_sources` SET name = ".$arg_list[8]->name[i]." authors = ".$arg_list[8]->author[i]." pages = ".$arg_list[8]->pages[i]."";
			$stmt = $mysqli->prepare("UPDATE `lit_sources` SET name = ? , authors = ? , pages = ? WHERE article_id = ?");
			$stmt->bind_param("sssi",$arg_list[6]->name[$i],$arg_list[6]->author[$i],$arg_list[6]->pages[$i],$curr_id);
			//echo "name:". $arg_list[6]->name[0]."";
			for($i = 0; i < count($arg_list[6]->author);$i++){
				/*$name = $arg_list[6]->name[$i];
				$author = $arg_list[6]->author[$i];
				$pages = $arg_list[6]->pages[$i];
				$curr_id = (int)$arg_list[7];*/
				$stmt->execute();
			}
			echo "ready";
		break;
		case 'user_update':
			
		break;
		default:
			
		break;
	}
	//$stmt->execute();
	/* END Изменение записи в таблице*/
}


/*Поиск статей*/

function find_article($mysqli,$s_table,$s_line,$s_word){
	$arg_list = func_get_args();
	$qr = "SELECT * FROM ".$s_table." WHERE ".$s_line." LIKE '%$s_word%'";
	$result = $mysqli->query($qr);
	$result->search_type = $s_line;
	if($result->num_rows != 0){
		return $result;
	}else{
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
			case 'add_author':
				insert_to_db($mysqli,"add_author", NULL, $_POST['author_name'],$_POST['dc_degree'],$_POST['type'],$_POST['organisation']);
				unset($_GET['req_type']);
				echo '<script>location.replace("test_script.php");</script>'; exit;
				//header ('Location: test_script.php');
				$script_result = "user_registred";
			break;

			case 'add_journal':
				$script_result = insert_to_db($mysqli, "add_journal", NULL, $_POST["journal_name"], $_POST["type"], $_POST["pub_year"], $_POST["journal_number"], $_POST["journal_pages"], (int)$_POST["art_blocked"]);
				echo '<script>location.replace("test_script.php");</script>'; exit;
			break;

			case 'new_article' :
				$parts = explode(" ", $_POST['journal']);
				$jour_class = $parts[1];
				$pattern = '/[\d]{1,2}/';
				$jour_numb = "";
				$authors_id  = array();
				preg_match($pattern, $parts[2],$jour_numb);
				$jour_numb = implode("", $jour_numb);
				$jour_year = $parts[3];
				$journal = select_from_db($mysqli,"journal_id", "journals", "class",$jour_class,"pub_year",$jour_year,"number",$jour_numb);
				$row = $journal->fetch_assoc();
				$journal_id = $row['journal_id'];
				//echo "j_id".$journal_id;
				$ins_art_id = insert_to_db($mysqli,"new_article",$_POST['art_name'],$_POST['pages'],$journal_id);
				authors_link_art($mysqli,$ins_art_id);
				//insert_to_db($mysqli, "add_link",$journal_id,$ins_art_id);
				//$script_result = insert_to_db($mysqli,"new_article", NULL, $_POST['art_name'],$_POST['journal'],$_POST['pages']);
				unset($_GET['req_type']);
				//echo '<script>location.replace("test_script.php");</script>';
				//header ('Location: test_script.php');
				//$script_result = "article_added";
			break;
			
			case 'search': 
				//$script_result->search_type
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

		case 'ajax_get_art':

			//session_name('upd_article');
			session_start();
			$art_name = '';
			$data = json_decode($_POST['jsonData']);
			$response = array();
			$lit = '';
			$i = 0;
			foreach ($data as $key=>$value) {
				if($key == "art_name"){
					$art_name = $value;
				}
			}
			$qr = "SELECT * FROM `articles` WHERE name = '".$art_name."'";
			$qr_res = $mysqli->query($qr); 
			$row = $qr_res->fetch_assoc();
			$_SESSION['ses_upd_art_id'] = $row['article_id'];
			$response['art_data'] = $row;
			$qr = "SELECT * FROM `lit_sources` WHERE article_id = ".$row['article_id'];
			$qr_res = $mysqli->query($qr);
			while ($row = $qr_res->fetch_assoc()) {
				$lit = 'lit' + $i;
				$lit_data[$lit] = $row;
				$i++;
			}
			$response['lit_data'] = $lit_data;
			$response['lit_count'] = $i;
			//$row = 
			$response = json_encode($response);
			echo $response;
		break;

		case 'ajax_update_art':
			session_start();
			$art_name = '';
			$art_author = '';
			$art_pages = '';
			$art_text = '';
			$art_literature = [];
			echo "string: ".$_POST['jsonData']."\n";
			$data = json_decode($_POST['jsonData']);
			foreach ($data as $key=>$value) {
				//$response .= 'Параметр: '.$key.'; Значение: '.$value.'';
				switch ($key) {
					case 'author':
						$art_author = $value;
					break;
					case 'art_name':
						$art_name = $value;
					break;
					case 'pages':
						$art_pages = $value;
					break;
					case 'article_text':
						$art_text = $value;
					break;
					case 'literature':
						$art_literature = $value;
					break;
					default:
						# code...
					break;
				}
			}

			if(isset($_SESSION['ses_upd_art_id'])){
				//echo "art_id: ".$_SESSION['ses_upd_art_id']."";
				$id = $_SESSION['ses_upd_art_id'];
				//echo "id".$id."";
				update_data_in_db($mysqli, "article_update",$art_author, $art_name, $art_pages, $art_text, $art_literature, $id);
			}else{
				echo "art ID undefined";
			}
			//$response = json_encode($art_literature->author[0]);
			//echo $response;
		break;
		case 'ajax_ch_art_class':
			$data = json_decode($_POST['jsonData']);
			$journal_class = "";
			$journals_arr = array();
			$authors_arr = array();
			$response_arr = array(
				"authors"=> array(),
				"journals"=> array(),
			);
			$i = 0;
			$block = "blocked";
			foreach ($data as $key=>$value) {
				if($key == "journal_class"){
					$journal_class = $value;
				}
			}
			$result = select_from_db($mysqli,"*","journals","class",$journal_class);
			//$result = $mysqli->query("SELECT * FROM `journals` WHERE `class` = 'C' AND `".$block."` = ".$i."");
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
          			//echo "<option>Серия ".$row['class']." №".$row['number']." ".$row['pub_year']."</option>";
					$journals_arr[$i] = $row;
					$i++;
				}
			}
			$response_arr['journals'] = $journals_arr;
			$result = select_from_db($mysqli, "*", "authors","class",$journal_class);
			$i = 0;
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
          			//echo "<option>Серия ".$row['class']." №".$row['number']." ".$row['pub_year']."</option>";
					$authors_arr[$i] = $row;
					$i++;
				}
			}else{
				//$res = json_encode($result);
				//echo $result;
			}
			$response_arr['authors'] = $authors_arr;

			$res = json_encode($response_arr);
			echo $res;
		break;

		default:
			//select_script($mysqli);
		break;
	}
}

//$mysqli->close();


?>
