<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />

<html>
<title>emergency</title>

<div align="center"><img src="/emergency.png" width="300px" height="200px">


<?php

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'root';
$mysql_db = 'Hospital';

// 접속
$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password);
$dbconn = mysql_select_db($mysql_db, $conn);

$sql="SELECT * from Alarm";
$result = mysql_query($sql) ;

echo "<table border = 2px style=\"width:80%\" \"height:80%\" \"text-align:center\">";
echo "<tr>";
	echo "<td><strong style=\"font-size: 25px\"> 환자 이름</strong> </td>";
	echo "<td><strong style=\"font-size: 25px\"> 체온 상태</strong> </td>";
	echo "<td><strong style=\"font-size: 25px\"> 심박수 상태</strong> </td>";
echo "</tr>";	
while($arr = mysql_fetch_array($result)){
echo "<tr>";
	echo "<td style=\"padding-left: 5px;padding-bottom:3px; font-size: 25px;\">$arr[0]</td>";
	if($arr[1]=='1')
		echo "<td style=\"padding-left: 5px;padding-bottom:3px; font-size: 25px;\"> 정상 상태 <img src=/green.png width=\"10\"> </td>";
	else if($arr[1]=='2')
		echo "<td style=\"padding-left: 5px;padding-bottom:3px; font-size: 25px;\"> 치료중 <img src=/pu.png width=\"10\"></td>";
	else
		echo "<td><strong style=\"font-size: 25px\"> 위험 상황</string> <img src=/ambu.png width=\"10\"> </td>";

	if($arr[2]==3)
		echo "<td><strong style=\"font-size: 25px\"> 위험 상황</string> <img src=/ambu.png width=\"10\"> </td>";
	else if($arr[2]==2)
		echo "<td style=\"padding-left: 5px;padding-bottom:3px; font-size: 35px;\"> 치료중 <img src=/pu.png width=\"10\"></td>";
	else
		echo "<td style=\"padding-left: 5px;padding-bottom:3px; font-size: 25px;\"> 정상 상태 <img src=/green.png width=\"10\"> </td>";
echo "</tr>";

}
echo "</table>";
?>

</div>

</html>

<?
$refresh_time="2";// 여기에 몇초마다 refresh 할지를 지정하세요^^*
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";
?>

