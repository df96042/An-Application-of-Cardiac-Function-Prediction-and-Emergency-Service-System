<!-- finish -->

<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />

<!DOCTYPE html>
<html>
	<head>
		<title>Emergency_Setting</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<div class="maindiv">
			<div class="divA">
				<div class="title"> <h2>사용자 정보</h2> </div>
			<div class="divB">
			<div class="divD">

<?php

	$mysql_host = "localhost";
	$mysql_user = "root";
	$mysql_password = "root";
	$mysql_db = "Hospital";

// 접속
	$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

	if (isset($_GET['submit'])) {
	//echo '<div class="form" id="form3"><br><br><br><br> <span> 설정변경 완료 </span></div>';
	$pnum = $_GET['pnum'];
	$set_hazard = $_GET['hazard'];
	$set_ht = $_GET['HT_attack'];
	$sql = "update emergency set hazard=".$set_hazard.", HT_attack=".$set_ht." where pnum=".$pnum;
	echo "$sql";
	$result = mysqli_query($conn, $sql);
	
}
//charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

	$pnum = $_GET['pnum'];
	echo "<b><a href='emergency_setting.php?pnum={$pnum}'> 고유번호 : {$pnum} </a></b>";
	echo "<br/></div>";

	$result = mysqli_query($conn, "select * from emergency where pnum=".$pnum);
	while ($row = mysqli_fetch_array($result)){
	//$result2 = mysqli_query($conn, "select * from emergency where pnum=".$pnum);
	//while ($row2 = mysqli_fetch_array($result2)) {
		echo "<form class='form' method='get'>";
		echo "<h2> 환자상태변경 </h2>";
		echo "<hr/><br /><br />";
		echo "현재 위험 상태 : {$row['hazard']} <br /> 심장마비여부 : {$row['HT_attack']} <br /><br /><br />";
		echo "<label>" . "고유번호" . "</label>" . "<br />";
		echo "<input class= 'input' type= 'text' name='pnum' value='{$pnum}' /><br><br>";
		echo "<label>" . "위험상태" . "</label>" . "<br />";
		echo"<input class='input' type='text' name='hazard' value='{$row2['hazard']}' />";
		echo "<br /><br>";
		echo "<label>" . "심정지" . "</label>" . "<br />";
		echo"<input class='input' type='text' name='HT_attack' value='{$row2['HT_attack']}' />";
		echo "<br />";
		echo "<input class='submit' type='submit' name='submit' value='setup' />";
		echo "</form>";
	}

if (isset($_GET['submit'])) {
echo '<div class="form" id="form3"><br><br><br><br> <span> 설정변경 완료 :></span></div>';
	$set_hazard = $_GET['hazard'];
	$set_ht = $_GET['HT_attack'];
	$sql = "update emergency set hazard=".$set_hazard.", HT_attack=".$set_ht." where pnum=".$pnum;
	$result = mysqli_query($conn, $sql);
}
?>

<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div><?
mysqli_close($conn);
?>
<br><br><p></p><br>
<br><br><br><p></p><br>
<div align=center>
<button class="button" type="button" onclick="location.href='/heart'"> 심판 메인화면 </button></div>

</body>
</html>

