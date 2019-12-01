<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />

<?php

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "root";
$mysql_db = "Hospital";

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

//charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

$pnum=$_GET['pnum'];
echo"$pnum";
$sql="SELECT * from (SELECT * FROM patient where pnum=".$pnum." ORDER BY RT_time DESC LIMIT 15) as a order by RT_time ASC";
$result = mysqli_query($conn, $sql);

$str_time="";
$str_atemper="";
$str_heart="";

while ($row = mysqli_fetch_array($result)) {
 $str_time .="'".$row['RT_time']."',";
 $str_atemper .="".$row['Temperature'].",";
 $str_heart .="".$row['HR'].",";
}

$str_time= substr($str_time,0,-1);
$str_atemper= substr($str_atemper,0,-1);
$str_heart= substr($str_heart,0,-1);

$sql2="SELECT * FROM patient_data WHERE Pnum=".$pnum;
$result2=mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);
$hr_avg= $row2['HR_avg'];
?>

<!DOCTYPE HTML>
<html>
<title> Patient2 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style=font-weight:bold;> 
<div align="center"><a href="/heart"><img src = "/theme/hospital/img/logo.png" width="100px" height="100px" align="center"></a></div>
<hr size="3px" color="black"><br>
<font size="4" align="center"><p><p><p><p><p><p> 

&nbsp;&nbsp;&nbsp;&nbsp; 이름: 핸섬가이<br>
&nbsp;&nbsp;&nbsp;&nbsp; 나이: 50<br>
&nbsp;&nbsp;&nbsp;&nbsp; 혈액형: A <br>
<div align="center"><img src="man.png" width="80px" height="120px" align="center"></div>
<hr size="3px" color="black"><br>
<pre size="10px"><span style=font-weight:bold;> 실시간 환자상태<br>


BODY TEMPERATURE <br>

<div id="container2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

 <style type="text/css">
        ${highcharts.css}
  </style>

  <link rel="stylesheet" type="text/css" href="./highchart/code/css/highcharts.css"/>
  <script type="text/javascript">
$(function () {
    $('#container2').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Body Temperature'
        },
        subtitle: {
            text: '심판.com'
        },
        xAxis: {
            categories: [<?php echo $str_time?>]
        },
        yAxis: {
            title: {
                text: ' 실시간 Temperature (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '환자2',
            data: [<?php echo $str_atemper?>]
        }
  ]
    });
});
  </script>


<script src="./highchart/code/js/highcharts.js"></script>
<script src="./highchart/code/js/modules/exporting.js"></script>

 HEART RATE<br>
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <style type="text/css">
	${highcharts.css}
  </style>

  <link rel="stylesheet" type="text/css" href="/highchart/code/css/highcharts.css"/>
  <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Heart Rate'
        },
        subtitle: {
            text: '심판.com'
        },
        xAxis: {
            categories: [<?php echo $str_time?>]
        },
        yAxis: {
            title: {
                text: ' 실시간 심박수 '
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '환자2',
            data: [<?php echo $str_heart?>]
            },{
            name: '정상상태 평균 값',
            data: [<?php echo str_repeat($hr_avg.",", 15) ?> ]
            }
  ]
    });
});
  </script>

<?php $_GET['pnum']=1; include('hb_status.php');?>

</html>

<?php
$refresh_time="10";// 여기에 몇초마다 refresh 할지를 지정하세요^^*
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";
?>"
