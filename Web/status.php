<?php

$refresh_time="10";// 여기에 몇초마다 refresh 할지를 지정하세요
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "root";
$mysql_db = "Hospital";

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

//charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysqli_query("set names utf8");

$pnum=$_GET['pnum'];
$total_diff="";
$total_reach="";

//10회반복
for($i=0; $i<10; $i++){
//최근20개 값 중에 최소,최대 심박수 가져오기
$sql="SELECT max(hr), min(hr), avg(hr) from patient where pnum=".$pnum." GROUP by Pnum HAVING pnum=1 ORDER BY RT_time DESC LIMIT 20";
$result = mysqli_query($conn, $sql);

$max_hr="";
$min_hr="";
$avg_hr="";

while($row = mysqli_fetch_array($result)){
	$max_hr = $row['max(hr)'];
	$min_hr = $row['min(hr)'];
    $avg_hr = $row['avg(hr)'];
	$diff = $max_hr - $min_hr;
//  echo "avg_hr : ".$avg_hr."max_temp : ".$max_temp;
//	echo "DIFF : max_heartrate - min_heartrate = ".$max_hr."-".$min_hr."=".$diff;
}

$total_diff = $total_diff + ($diff>82);
//echo "total_diff : ".$total_diff."percent: ".($total_diff*10);

//MPHR가져오기
$sql2="SELECT * FROM patient_data WHERE Pnum=".$pnum;
$result2=mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);
$hr_avg = $row2['HR_avg'];
$mphr = $row2['MPHR'];

$reach = $max_hr>$mphr;
$total_reach = $total_reach + $reach;
//echo"reach : ".$reach."total_reach percent: ".($total_reach*10)."%";

sleep(2);
}

echo " 분당 최대심박수 : ".$max_hr." 분당 최소심박수 : ".$min_hr;
?>

<!DOCTYPE HTML>
<html>
<title> USER STATUS </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<br><br>

DIFF : 1분당 최대심박수와 최소심박수의 차이가 82 이상인 경우 <br><br>
REACH : 1분당 최대심박수가 최대가능심박수(MPHR : (220-나이)*85%) 이상인 경우 <br><br>


<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src = "http://code.highcharts.com/highcharts.js"></script>
<div id="container" style="width: 80%; height: 100%; margin: 0 auto"></div>

<script>
$(function () {
	$('#container').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: "<?php echo 'USER'.$pnum?>님의 심장기능분석"
    },
    xAxis: {
        categories: [
            'DIFF','REACH'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'PERCENT (%)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{y}%'
            },
        }
    },
    series: [{
        name: '최근심장기능',
        color: 'rgba(165,170,217,1)',
        data: [<?php echo "$total_diff*10, $total_reach*10"; ?>],
        tooltip: {
            valueSuffix: ' %'
        },
        pointPadding: 0.35,
        pointPlacement: -0.1
    },{
        name: 'DANGER',
        color: 'rgba(255,0,0,0.6)',
        data: [70,40],
        tooltip: {
            valueSuffix: ' %'
        },
        pointPadding: 0.35,
        pointPlacement: 0.1,
    }]
	});
});  
  
</script>


</html>

