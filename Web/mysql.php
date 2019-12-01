<?php
/**** android 에서 확인하는 코드 ****/
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "root";
$mysql_db = "Hospital";

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

//charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

$pnum=$_GET['pnum'];
$sql="SELECT HT_attack from emergency where pnum=".$pnum;
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$HT_attack = $row['HT_attack'];

$arr = array();
array_push($arr, array('pnum'=>$pnum, 'HT_attack'=>$HT_attack));

echo json_encode(array("arr"=>$arr));

mysqli_close($conn);

$refresh_time="10";// 여기에 몇초마다 refresh 할지를 지정하세요^^*
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";

?>
