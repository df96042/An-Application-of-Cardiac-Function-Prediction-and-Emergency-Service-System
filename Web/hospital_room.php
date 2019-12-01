<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />


<!DOCTYPE html>
<html>
<head>
 <title>Hospital Room Setup</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="maindiv">
<div class="divA">
<div class="title">
<h2>병실 환경 관리</h2>
</div>
<div class="divB">
<div class="divD">
<p>입원 병동 호실</p>
<?php
$connection = mysql_connect("localhost", "root", "821n2757");
$connection = mysql_select_db("Hospital");
if (isset($_GET['submit'])) {
$number = 0;
$temperature = $_GET['dtemperature'];
$sleep_time = $_GET['dtime'];
$connection = mysql_query("update room set
temperature='$temperature', sleep_time='$sleep_time'
 where number='$number'") or die(mysql_error());
}
$connection = mysql_query("select * from room") or die(mysql_error());

while ($row = mysql_fetch_array($connection)) {
echo "<b><a href='updatephp.php?update={$row['number']}'>ROOM : {$row['number']}</a></b>";
echo "<br />";
}
?>
</div><?php
//if (isset($_GET['submit'])) {
//$setup = $_GET['submit'];
$connection = mysql_query("select * from room where number=1") or die(mysql_error());
while ($row2 = mysql_fetch_array($connection)){
$connection = mysql_query("select * from room where number=0");
while ($row1 = mysql_fetch_array($connection)) {
echo "<form class='form' method='get'>";
echo "<h2>병실 환경 목록</h2>";
echo "<hr/><br /><br />";
echo "현재 실내 온도 : {$row2['temperature']}도 <br />설정 수면 시간 : {$row1['sleep_time']}시 <br /><br /><br />";
echo "<label>" . "실내 온도" . "</label>" . "<br />";
echo"<input class='input' type='text' name='dtemperature' value='{$row1['temperature']}' />";
echo "<br />";
echo "<label>" . "수면 시간" . "</label>" . "<br />";
echo"<input class='input' type='text' name='dtime' value='{$row1['sleep_time']}' />";
echo "<br />";
echo "<input class='submit' type='submit' name='submit' value='setup' />";
echo "</form>";
}
}
if (isset($_GET['submit'])) {
echo '<div class="form" id="form3"><br><br><br><br>
<Span> 설정변경 완료 :></span></div>';
//$number = 0;
$temperature = $_GET['dtemperature'];
$sleep_time = $_GET['dtime'];
$connection = mysql_query("update room set
temperature='$dtemperature', sleep_time='$sleep_time',
 where number='$number'");
}
?>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div><?
mysql_close($connection);
?>
<br><br><p></p><br>
<br><br><br><p></p><br>
<div align=center>
<button class="button" type="button" onclick="location.href='/'">SMART HOSPITAL</button></div>

</body>
</html>

