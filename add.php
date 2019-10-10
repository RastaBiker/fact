<?
GLOBAL $title;
$title = "Результат добавления";
require('header.php');
?>
<?
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db = DataBase::getDB();
	$max_id_query = "SELECT MAX(`ID`) FROM `users`";
	$max_id = $db->selectCell($max_id_query);
	$current_id = $max_id+1;
	$errors = array();
	$LOGIN;
	$NAME;
	$LAST_NAME;
	$EMAIL;
	$PHONE;
	$ACTIVE;
	if(isset($_REQUEST["login"]) && !empty($_REQUEST["login"])) {
		$LOGIN = trim($_REQUEST["login"]);
	} else {
		$errors[] = "Введите логин";
	}
	if(isset($_REQUEST["email"]) && !empty($_REQUEST["email"])) {
		$EMAIL = trim($_REQUEST["email"]);
	} else {
		$errors[] = "Введите Email";
	}
	if(isset($_REQUEST["name"]) && !empty($_REQUEST["name"])) {
		$NAME = trim($_REQUEST["name"]);
	}
	if(isset($_REQUEST["last_name"]) && !empty($_REQUEST["last_name"])) {
		$LAST_NAME = trim($_REQUEST["last_name"]);
	}
	if(isset($_REQUEST["phone"]) && !empty($_REQUEST["phone"])) {
		$PHONE = trim($_REQUEST["phone"]);
	}
	if(isset($_REQUEST["active"]) && !empty($_REQUEST["active"])) {
		$ACTIVE = trim($_REQUEST["active"]);
	}
	$query_login_email = "SELECT `LOGIN`,`EMAIL` FROM `users`";
	$logins_emails = $db->select($query_login_email);
	// echo "<pre>";
	// print_r($logins_emails);
	// echo "</pre>";
	if(in_multarray($LOGIN,$logins_emails)) {
		$errors[] = "Такой логин уже существует";
	}
	if(in_multarray($EMAIL,$logins_emails)) {
		$errors[] = "Такой Email уже существует";
	}
	if(!empty($errors)) {
		foreach($errors as $error) {
			echo "<p style='color:red;'>".$error."</p>";
		}
	} else {
		$query = "INSERT INTO `users`(
						`ID`, `LOGIN`, `NAME`, `LAST_NAME`, 
						`EMAIL`, `PHONE`
					) 
					VALUES 
						(
							".$current_id.", '".$LOGIN."', '".$NAME."', '".$LAST_NAME."', '".$EMAIL."', 
							'".$PHONE."'
						)";
		$add = $db->query($query);
		$curr_day_query = "SELECT `DATE` FROM `stat` WHERE `DATE` = CURRENT_DATE()";
		$curr_day = $db->selectCell($curr_day_query);
		if(empty($curr_day)) {
			$query = "INSERT INTO `stat`(
						`DAY_ADD`
					) 
					VALUES 
						(
							1
						)";
		} else {
			$query = "UPDATE `stat` SET `DAY_ADD`=`DAY_ADD`+1 WHERE `DATE` = CURRENT_DATE()";
		}
		$day_add = $db->query($query);
		echo "<p>Пользователь добавлен в базу</p>";
	}
}
echo "
	<p><a href='/'>Вернуться</a></p>
";
function in_multarray($a,$b) {
	foreach($b as $sub) {
		if(is_array($sub)) {
			foreach($sub as $ssub) {
				if($ssub == $a) {
					return true;
				}
			}
		} else {
			if($sub == $a) {
				return true;
			}
		}
	}
	return false;
}
?>
<?
require('footer.php');
?>