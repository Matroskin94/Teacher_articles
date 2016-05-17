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
function select_from_db($mysqli,$field,$table){
	$args_num = func_num_args();
	$args_list = func_get_args();
	if($args_num == 3){
		if($result = $mysqli->query("SELECT ".$field." FROM ".$table."")){
			return $result;
		}
	}
	if($args_num == 5){
		if ($result = $mysqli->query("SELECT ".$field." FROM ".$table." WHERE ".$args_list[3]." = '".$args_list[4]."'")) { 
			//echo "SELECT ".$field." FROM ".$table." WHERE ".$args_list[3]." = '".$args_list[4]."' <br>";
			$result->qr = "SELECT ".$field." FROM ".$table." WHERE ".$args_list[3]." = '".$args_list[4]."'";
			return $result;
		}else{
			return "not_found";
		}
	}
	if($args_num == 7){
		if ($result = $mysqli->query("SELECT ".$field." FROM `".$table."` WHERE `".$args_list[3]."` = '".$args_list[4]."' AND `".$args_list[5]."` = '".$args_list[6]."'")) { 
			//echo "SELECT ".$field." FROM `".$table."` WHERE `".$args_list[3]."` = '".$args_list[4]."' AND `".$args_list[5]."` = '".$args_list[6]."' <br>";
			return $result;
		}else{
			return "not_found";
		}
	}
	if($args_num == 9){
		if($result = $mysqli->query("SELECT ".$field." FROM `".$table."` WHERE `".$args_list[3]."` = '".$args_list[4]."' AND `".$args_list[5]."` = '".$args_list[6]."' AND`".$args_list[7]."` = '".$args_list[8]."'")){
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
			$qr = "INSERT INTO `articles` (`article_id` ,`art_name` ,`art_pages`, `class`, `journal_id`) VALUES (NULL,
			'".$arg_list[2]."', '".$arg_list[3]."', '".$arg_list[4]."', '".$arg_list[5]."')";
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
		//echo "curr_auth:".$auth_name."<br>";
		$sel_res = select_from_db($mysqli, "author_id","authors","name",$auth_name);
		//var_dump($sel_res);
		$res_row = $sel_res->fetch_assoc();
		$auth_id = $res_row['author_id'];
		//echo "curr_auth_id:".$auth_id."<br>";
		insert_to_db($mysqli,"add_link",$res_row->author_id);
		if(is_object($stmt)){
			$stmt->execute();
		}
		$auth_num += 1;
		$curr_auth = "author".$auth_num; 
		//echo "next_author:".$curr_auth."<br>";
	}

	$stmt->close();

	/*if(is_object($stmt)){
		$stmt->execute(); 
		$stmt->close();
	}*/
	//select_from_db($mysqli, "author_id","authors","name",$auth_name);
}

/*Поиск статей в связующей таблице*/
function select_link($mysqli,$author_id,$art_id_arr){
	$articles_str = implode(",",$art_id_arr);
	$qr = "SELECT `article_id` FROM `article_author` WHERE `author_id` = '".$author_id."' AND `article_id` IN (".$articles_str.")";
	$res_articles = $mysqli->query($qr);
	if(is_object($res_articles)){
		return $res_articles;
	}else{
		return "not_found";
	}
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
			$curr_auth = "";
			$article_id = (int)$arg_list[2];
			$dell_authors = $arg_list[5];
			$new_authors = $arg_list[6];
			$pages = "";
			$curr_id = (int)$arg_list[2];
			$qr = "UPDATE `articles` SET `art_name` = '".$arg_list[3]."', `art_pages` = '".$arg_list[4]."' WHERE `article_id` = ".$article_id."";
			$result = $mysqli->query($qr);
			$dell = $mysqli->prepare("DELETE FROM `article_author` WHERE article_id = ? AND author_id = ?");
			$dell->bind_param("ii",$article_id,$curr_auth);
			$add = $mysqli->prepare("INSERT INTO `article_author` (author_id, article_id) VALUES (?, ?)");
			$add->bind_param("ii", $curr_auth, $article_id);
			if(count($dell_authors) != 0){
				for($i = 0; $i < count($dell_authors);$i++){
					$result = select_from_db($mysqli,"author_id","authors","name",$dell_authors[$i]);
					$auth_row = $result->fetch_assoc();
					$curr_auth = (int)$auth_row['author_id'];
					$dell->execute();
				}
			}
			$dell->close();
			if(count($new_authors)!=0){
				for($i = 0; $i < count($new_authors);$i++){
					$result = select_from_db($mysqli,"author_id","authors","name",$new_authors[$i]);
					$auth_row = $result->fetch_assoc();
					$curr_auth = (int)$auth_row['author_id'];
					$add->execute();
				}
			}
			$add->close();
			
		break;
		case 'author_update':
			$qr = "UPDATE `authors` SET `name` = '".$arg_list[3]."', `dc_degree` = '".$arg_list[4]."', `class` = '".$arg_list[5]."', `organisation` = '".$arg_list[6]."' WHERE `author_id` = ".$arg_list[2]."";
			$result = $mysqli->query($qr);
			//return $result;
		break;
		default:
			
		break;
	}
	//$stmt->execute();
	/* END Изменение записи в таблице*/
}


/*Поиск статей*/

function find_article($mysqli,$field,$s_table,$s_line,$s_word){
	$arg_list = func_get_args();
	$result = "";
	$qr = "SELECT ".$field." FROM `".$s_table."` WHERE ".$s_line." LIKE '%$s_word%'";
	$result = $mysqli->query($qr);
	//echo "<br>";
	//var_dump($result);
	if($result->num_rows != 0){
		return $result;
	}else{
		$result = "not_found";
		return $result;		
	}
	//return $result;

}

/* Вывод статей по авторам*/
function show_s_results($arr)
{	
	$curr_authors = "";
	$curr_journal = "";
	$authors_str = "";
	for($i = 0;$i<count($arr);$i++){
		if(is_object($arr[$i]) == 1){
			//while( $row = $arr[$i]->fetch_assoc() ){
			echo '<tr>';
			$curr_authors = $arr[$i]->authors;
			$curr_journal = $arr[$i]->art_journal;
			$row = $arr[$i]->fetch_assoc(); 
			//echo "authors".$row['authors'];
			//echo "<hr>";
			$authors_str = "";
			for($j = 0;$j < count($curr_authors);$j++){
				$authors_str = $authors_str.$curr_authors[$j]." <br>";
			}
			echo '<td>'.$authors_str.'</td>';
			echo '<td>'.$row['art_name'].'</td>';
			echo '<td>'.$row['art_pages'].'</td>';
			echo '<td>'.$curr_journal.'</td>';
			echo '</tr>';
			
		}
	}
	//$arr->free();
}


/*Выборка собдержимого статьи*/

function select_article($mysqli,$jour_articles){
	$i = 0;
	$article_authors = array();
	while ($row = $jour_articles->fetch_assoc()) {
		$j = 0;
		$authors_id = select_from_db($mysqli,"author_id","article_author","article_id",$row['article_id']);
		while($art_auth_row = $authors_id->fetch_assoc()){
			$authors_of_art = select_from_db($mysqli,"*","authors","author_id",$art_auth_row['author_id']);
			while ($auth_row = $authors_of_art->fetch_assoc()) {
				$article_authors[$j] = $auth_row;
				$j++;
			}
		}
		$row['authors'] = $article_authors;
		$json_data[$i] = $row;
		$i++;
	}
	return $json_data;
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
				$ins_art_id = insert_to_db($mysqli,"new_article",$_POST['art_name'],$_POST['pages'],$_POST['jour_class'],$journal_id);
				authors_link_art($mysqli,$ins_art_id);
				unset($_GET['req_type']);
				echo '<script>location.replace("test_script.php");</script>';
				//header ('Location: test_script.php');
			break;

			default:
				# code...
			break;
		}	
		return $script_result;
	}

	if(isset($_POST['search_but'])){
		$arr_articles = array();
		if(($_POST['search_name'] == "")&&($_POST['search_author'] == "")){
			$arr_articles = "not_found";
		}else if(($_POST['search_name'] != "")&&($_POST['search_author'] === "")){
			$search_by_name = find_article($mysqli,"*","articles","art_name",$_POST['search_name']);
			$arr_articles[0] = $search_by_name;
			$arr_articles[1] = "search";
		}else{
			$search_by_author = find_article($mysqli,"author_id", "authors","name",$_POST['search_author']);
			$search_by_name = find_article($mysqli,"article_id","articles","art_name",$_POST['search_name']);
			$i = 0;
			if(($_POST['search_name'] == "")&&($_POST['search_author'] != "")&&($search_by_author != "not_found")){
				while ($auth_row = $search_by_author->fetch_assoc()){
					$auth_id = $auth_row['author_id'];
					$sel_by_auth = select_from_db($mysqli,"article_id","article_author","author_id",$auth_id);
					if(is_object($sel_by_auth)){
						while($row = $sel_by_auth->fetch_assoc()){
							$sel_by_art_id = select_from_db($mysqli,"*","articles","article_id",$row['article_id']);
							$arr_articles[$i] = $sel_by_art_id;
							$i++;
						}
					}
				}
				$arr_articles[count($arr_articles)] = "search";
			}else if(($search_by_name != "not_found") && ($search_by_author != "not_found")){
				$i = 0;
				while ($name_row = $search_by_name->fetch_assoc()){
					while($auth_row = $search_by_author->fetch_assoc()){
						$sel_aut_name = select_from_db($mysqli,"article_id","article_author","article_id",$name_row['article_id'],"author_id",$auth_row['author_id']);
						if($sel_aut_name->num_rows != NULL){
							$aut_name_row = $sel_aut_name->fetch_assoc();
							$arr_articles[$i] = select_from_db($mysqli,"*","articles","article_id",$aut_name_row['article_id']);
							$i++;
						}
					}
					$search_by_author->data_seek(0);
				}
				if(count($arr_articles) == 0){
					$arr_articles = "not_found";
				}else{
					$arr_articles[count($arr_articles)] = "search";
							//return $arr_articles;
				}
			}else {
				$arr_articles = "not_found";
			}

		}
		$journal = "";
		$authors = [];
		$journal_name = "";
		$j = 0;
		for($i = 0;$i < count($arr_articles) - 1;$i++){
			$art = $arr_articles[$i]->fetch_assoc();
			//var_dump($art);
			$art_journal = select_from_db($mysqli,'*','journals','journal_id',$art['journal_id']);
			$journal = $art_journal->fetch_assoc();
			$art_authors = select_from_db($mysqli,'author_id','article_author','article_id',$art['article_id']);
			$journal_name = "Серия ".$journal['class']." №".$journal['number']." ".$journal['pub_year']."";
			$j = 0;
			while ($row = $art_authors->fetch_assoc()) {
				$author = select_from_db($mysqli,"name","authors","author_id",$row['author_id']);
				$author_row = $author->fetch_assoc();
				$authors[$j] = $author_row['name'];
				$j++;
			}
			//echo "auth".$author_str;
			$arr_articles[$i]->authors = $authors;
			$arr_articles[$i]->art_journal = $journal_name;
			$arr_articles[$i]->data_seek(0);
			$authors = [];

		}
		/*while ($row = $auth_of_class->fetch_assoc()) {
				$art_data['auth_class'][$i] = $row;
				$i++;
		}*/

		unset($_POST['search_but']);
		unset($_POST['search_author']);
		unset($_POST['search_name']);
		return $arr_articles;
		//echo '<script>location.replace("test_script.php");</script>';
	}

}

if(isset($_GET['req_type'])){
	$mysqli = db_connect();
	switch ($_GET['req_type']) {
		case 'ajax_ch_jour':
			$data = json_decode($_POST['jsonData']);
			$curr_batch = '';
			$curr_numb = '';
			$curr_year = '';
			$jour_articles = ''; 
			foreach ($data as $key=>$value) {
				//$response .= 'Параметр: '.$key.'; Значение: '.$value.'';
				if($key == 'jour_batch'){
					$curr_batch = $value;
				}
				if($key == 'jour_numb'){
					$curr_numb = $value;
				}
				if($key == 'jour_year'){
					$curr_year = $value;
				}
			}
			$jour_id = $mysqli->query("SELECT `journal_id` FROM `journals` WHERE class = '".$curr_batch."' AND number = ".$curr_numb." AND pub_year = ".$curr_year."");
			$row = $jour_id->fetch_assoc();
			$jour_id = $row['journal_id'];
			$jour_articles = $mysqli->query("SELECT * FROM `articles` WHERE journal_id = '".$jour_id."'");
			$i = 0;
			//echo var_dump($jour_articles);
			$json_data = array();
			$single_article = array();
			$json_data = select_article($mysqli, $jour_articles);
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
			$art_data = "";
			foreach ($data as $key=>$value) {
				if($key == "art_name"){
					$art_name = $value;
				}
			}
			$qr = "SELECT * FROM `articles` WHERE art_name = '".$art_name."'";
			$qr_res = $mysqli->query($qr); 
			$art_data = select_article($mysqli,$qr_res);
			$qr_res->data_seek(0);
			$art_row = $qr_res->fetch_assoc();
			$auth_of_class = select_from_db($mysqli,"name","authors","class",$art_row['class']);
			$_SESSION['ses_upd_art_id'] = $art_data[0]['article_id'];
			while ($row = $auth_of_class->fetch_assoc()) {
				$art_data['auth_class'][$i] = $row;
				$i++;
			}
			$response = json_encode($art_data);
			echo $response;
		break;

		case 'ajax_update_art':
			session_start();
			$art_name = '';
			$art_author = '';
			$art_pages = '';
			$dell_aut = array();
			$new_aut = array();
			print_r($_SESSION);
			if(isset($_SESSION['ses_upd_art_id'])){
				$curr_art_id = $_SESSION['ses_upd_art_id'];
			}
			$data = json_decode($_POST['jsonData']);
			foreach ($data as $key=>$value) {
				switch ($key) {
					case 'art_name':
						$art_name = $value;
					break;
					case 'pages':
						$art_pages = $value;
					break;
					case 'dell_authors':
						for($i = 0; $i < count($value); $i++){
							$dell_aut[$i] = $value[$i];
						}
					break;
					case 'new_authors':
						for($i = 0; $i < count($value); $i++){
							$new_aut[$i] = $value[$i];
						}
					break;
					case 'literature':
						$art_literature = $value;
					break;
					default:
						# code...
					break;
				}
			}
			if($curr_art_id){
				echo update_data_in_db($mysqli,"article_update",$curr_art_id,$art_name,$art_pages,$dell_aut,$new_aut);
			}
			unset($_SESSION['ses_upd_art_id']);
			session_destroy();
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
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
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

		case 'ajax_vew_jour_class':
			$data = json_decode($_POST['jsonData']);
			$journal_class = "";
			$i = 0;
			foreach ($data as $key=>$value) {
				if($key == "journal_class"){
					$journal_class = $value;
				}
			}
			$result = select_from_db($mysqli,"*","journals","class",$journal_class);
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
					$journals_arr[$i] = $row;
					$i++;
				}
			}
			$res = json_encode($journals_arr);
			echo $res;
		break;

		case 'ajax_get_aut':
			session_start();
			$aut_name = '';
			$data = json_decode($_POST['jsonData']);
			$response = array();
			$i = 0;
			foreach ($data as $key=>$value) {
				if($key == "aut_name"){
					$aut_name = $value;
				}
			}
			$qr = "SELECT * FROM `authors` WHERE name = '".$aut_name."'";
			$qr_res = $mysqli->query($qr); 
			$response = $qr_res->fetch_assoc();
			//$auth_of_class = select_from_db($mysqli,"name","authors","class",$art_row['class']);
			$_SESSION['ses_upd_aut_id'] = $response['author_id'];
			$response = json_encode($response);
			echo $response;
		break;

		case 'ajax_update_aut':
			$response = array();
			$i = 0;
			session_start();
			if(isset($_SESSION['ses_upd_aut_id'])){
				$aut_id = (int)$_SESSION['ses_upd_aut_id'];
				//echo "aut_id".$aut_id;
			}
			$data = json_decode($_POST['jsonData']);
			foreach ($data as $key => $value) {
				if($key == "aut_name"){
					$aut_name = $value;
				}
				if($key == "dc_degree"){
					$aut_degree = $value;
				}
				if($key == "organisation"){
					$aut_org = $value;
				}
				if($key == "auth_class"){
					$aut_class = $value;
				}
			}

			//$qr = "UPDATE ";
			update_data_in_db($mysqli,"author_update",$aut_id,$aut_name,$aut_degree,$aut_class,$aut_org);
			$result = select_from_db($mysqli,"*","authors","class",$aut_class);
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
					$response[$i] = $row;
					$i++;
				}
			}
			$response = json_encode($response);
			echo $response;
		break;

		case 'ajax_ch_aut_class':
			$data = json_decode($_POST['jsonData']);
			$class = "";
			$authors_arr = array();
			$i = 0;
			foreach ($data as $key=>$value) {
				if($key == "class"){
					$class = $value;
				}
			}
			$result = select_from_db($mysqli,"*","authors","class",$class);
			if(is_object($result)){
				while( $row = $result->fetch_assoc() ){ 
					$authors_arr[$i] = $row;
					$i++;
				}
			}

			$res = json_encode($authors_arr);
			echo $res;
		break;

		case 'ajax_del_art':
			$data = json_decode($_POST['jsonData']);
			$art_name = "";
			$art_class = "";
			foreach ($data as $key => $value) {
				if($key == "art_name"){
					$art_name = $value;
				}
				/*if($key == "art_class"){
					$art_class = $value;
				}*/			
			}	

			$qr = "DELETE FROM `articles` WHERE art_name = '".$art_name."'";
			$result = $mysqli->query($qr);
			//$qr = select_from_db($mysqli,"*");
		break;

		case 'ajax_del_aut':
			$data = json_decode($_POST['jsonData']);
			$auth_name = "";
			$resp = array();
			$i = 0;
			foreach ($data as $key => $value) {
				if($key == "author_name"){
					$auth_name = $value;
				}
				if($key == "author_class"){
					$auth_class = $value;
				}
			}
			$qr = "DELETE FROM `authors` WHERE name = '".$auth_name."'";
			$result = $mysqli->query($qr);
			$result = select_from_db($mysqli,"*","authors","class",$auth_class);
			while($row = $result->fetch_assoc()){
				$resp[$i] = $row;
				$i++;
			}
			$resp = json_encode($resp);
			echo $resp;
		break;

		default:
			//select_script($mysqli);
		break;
	}
}

//$mysqli->close();


?>
