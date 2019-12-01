<?php

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "root";
$mysql_db = "Hospital";

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

//charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

$sql="SELECT pnum, HT_attack, hazard, GPS from emergency where HT_attack = 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

$pnum = $row['pnum'];
$hazard = $row['hazard'];
$HT_attack = $row['HT_attack'];
$GPS = $row['GPS'];

mysqli_close($conn);

$conn2 = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

$sql2="SELECT name,age from patient_data where pnum =".$pnum;
$result2 = mysqli_query($conn2, $sql2);
$row2 = mysqli_fetch_array($result2);
$name = $row2['name'];
$age = $row2['age'];
//$HT_avg = $row2['HT_avg'];

mysqli_close($conn2);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>응급환자상태</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.29" />
	<link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
	<section id="one" class="wrapper special">
				<header class="major">
					<h2> 실시간 응급환자 상태정보 </h2>
				</header>
				<div class="inner">
					<div class="features">
						<div class="feature">
							<img src="emergency_icon.png" width="100" height="100" ></img>
							<h3><?php echo "환자ID: ".$pnum;?></h3>
							<p>----실시간 환자의 심박상태----</p>
							<p><?php echo "이름: ".$name; ?><br>
							<?php echo "나이: ".$age; ?><br>
							현위치: <?php echo $GPS; ?><br> 
							평소 건강 상태 : <?php if($hazard==1){echo"위험수치";} else echo"정상수치";?><br>
							원인 : <?php if($HT_attack==1) echo"심장마비";?><?php"} else echo"확인불가"; ?></p>
						</div>
					</div>
				</div>
			</section>
</body>

</html>
