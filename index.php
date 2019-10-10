<?
GLOBAL $title;
$title = "Тестовое задание";
require('header.php');

?>
<?
$arUsers = array();
$db = DataBase::getDB();
if(isset($_REQUEST["delete"]) && !empty($_REQUEST["delete"])) {
	$del_id = $_REQUEST["delete"];
	$query = "DELETE FROM `users` WHERE `ID` = ".$del_id;
	$bd_del = $db->query($query);
	$curr_day_query = "SELECT `DATE` FROM `stat` WHERE `DATE` = CURRENT_DATE()";
	$curr_day = $db->selectCell($curr_day_query);
	if(empty($curr_day)) {
		$query = "INSERT INTO `stat`(
					`DAY_DELETE`
				) 
				VALUES 
					(
						1
					)";
	} else {
		$query = "UPDATE `stat` SET `DAY_DELETE`=`DAY_DELETE`+1 WHERE `DATE` = CURRENT_DATE()";
	}
	$del_add = $db->query($query);
}
$query  = "SELECT * FROM `users`";
$arUsers = $db->select($query);
?>

	<table>
		<thead>
		<tr>
			<td>ID</td>
			<td>Логин</td>
			<td>Имя</td>
			<td>Фамилия</td>
			<td>Email</td>
			<td>Телефон</td>
			<td>Дата создания</td>
			<td>Удалить</td>
		</tr>
		</thead>
		<tbody>
			<?
			if(!empty($arUsers)) {
				foreach($arUsers as $User) {?>
					<tr>
						<td><?=$User["ID"]?></td>
						<td><?=$User["LOGIN"]?></td>
						<td><?=$User["NAME"]?></td>
						<td><?=$User["LAST_NAME"]?></td>
						<td><?=$User["EMAIL"]?></td>
						<td><?=$User["PHONE"]?></td>
						<td><?=$User["DATE_CREATE"]?></td>
						<td><form action="" method="post"><input type="checkbox" name="delete" class="delete" value="<?=$User["ID"]?>" /></form></td>
					</tr>
				<?}
			} else {
				?>
				<tr>
					<td colspan="8">Пользователи не найдены</td>
				</tr>
				<?
			}
			?>
		</tbody>
	</table>
	<?
	if($_REQUEST["cronsend"] == "send") {
		$query = "SELECT `DAY_ADD` FROM `stat` WHERE `DATE` = CURDATE()";
		$prev_day_add = $db->selectCell($query);
		$query = "SELECT `DAY_DELETE` FROM `stat` WHERE `DATE` = CURDATE()";
		$prev_day_delete = $db->selectCell($query);
		file_get_contents("https://script.google.com/macros/s/AKfycbwkHsO0Nx11CDJeP9xrse1-PjMtuthYCVjuL2PaoX7pNgCXXQAi/exec?p1=".$prev_day_add."&p2=".$prev_day_delete);
	}
	?>
	<p><a href="#popup" id="add">Добавить пользователя</a></p>
	
	<div style="display: none;">
		<div id="popup" style="width:400px;height:400px;overflow:auto;">
			<form method="post" action="add.php">
				<table>
					<tr>
						<td>Логин</td><td><input name="login" value="" type="text">*</td>
					</tr>
					<tr>
						<td>Имя</td><td><input name="name" value="" type="text"></td>
					</tr>
					<tr>
						<td>Фамилия</td><td><input name="last_name" value="" type="text"></td>
					</tr>
					<tr>
						<td>Email</td><td><input name="email" value="" type="text">*</td>
					</tr>
					<tr>
						<td>Телефон</td><td><input name="phone" value="" type="text"></td>
					</tr>

				</table>
				<p><button type="submit">Добавить</button></p>
			</form>
		</div>
	</div>
<?
require('footer.php');
?>