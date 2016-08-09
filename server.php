<?php 

function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'; 
}

function sanitize($str, $quotes = ENT_NOQUOTES) {
	$str = trim($str);
	$str = stripcslashes($str);
	$str = htmlspecialchars($str, $quotes);
	return $str;
}

if(is_ajax()) {

	if(isset($_POST["action"]) && !empty($_POST["action"])) {

		$action = $_POST["action"];
		if($action == "logout") {
			session_start();
			session_unset();
			session_destroy();
			break;
		}

		require_once("database.php");
		$db = new Database("seve", "localhost", "root", "");
		$connection = $db->dbConnection();
		$json_object = sanitize($_POST['data']);
		$php_object = json_decode($json_object);

		switch ($action) {
			case "login":
				$returned = $db->userLogin($php_object->username, $php_object->password);
				if($returned) {
					session_start();
					$_SESSION["username"] = $php_object->username;
					$json_data = true;
					break;
				}
				$json_data = false;
				break;
			case "search":
				$json_data = $db->searchRecord($php_object->accountName, $php_object->username, $php_object->url);
				break;
			case "insert":
				$json_data = $db->insertRecord($php_object->accountName, $php_object->username, $php_object->password, $php_object->comment, $php_object->url);
				break;
			case "update":
				$json_data = $db->updateRecord($php_object->old_username, $php_object->accountName, $php_object->username,	$php_object->password, $php_object->comment, $php_object->url);
				break;
			default:
				break;
		}
		$db->dbDisconnection();
		echo json_encode($json_data);
	}
}

?>