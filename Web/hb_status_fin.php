<?php
/******* FINISH ******/ 
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

//10회반복
for($i=0; $i<10; $i++){
    
//최근20개 값 중에 최소,최대,평균 심박수 가져오기
$sql="SELECT max(hr), min(hr), avg(hr) from patient where pnum=".$pnum." GROUP by Pnum HAVING pnum=1 ORDER BY RT_time DESC LIMIT 20";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $max_hr = $row['max(hr)'];
    $min_hr = $row['min(hr)'];
    $avg_hr = $row['avg(hr)'];
}

$sql2="SELECT * FROM patient_data WHERE Pnum=".$pnum;
$result2=mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);
$MPHR = $row2['MPHR'];
$hr_avg = $row2['HR_avg'];

sleep(1);
}


?>

<!DOCTYPE HTML>
<html>
<title> HEARTRATE STATUS </title>

<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src = "http://code.highcharts.com/highcharts.js"></script>
<div id="container3" style="width: 80%; height: 80%; margin: 0 auto"></div>

<script>
$(function () {
	$('#container3').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: "<?php echo 'USER'.$pnum?> 님의 평균심박상태"
    },
    xAxis: {
        categories: [
            '평균심박상태','상태판별'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'HEARTRATE'
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
                format: '{y}회'
            },
        }
    },
    series: [{
        name: '나의최근심박수',
        color: 'rgba(165,170,217,1)',
        data: [<?php echo "$avg_hr"; ?>],
        tooltip: {
            valueSuffix: ' 회'
        },
        pointPadding: 0.15,
        pointPlacement: 0,
    },{
        name: 'DANGER',
        color: 'rgba(255,0,0,0.4)',
        data: [null, 220],
        tooltip: {
            valueSuffix: ' 회'
        },
        pointPadding: 0.15,
        pointPlacement: 0,
    }, {
        name: '정상범위',
        color: 'rgba(153,204,254,0.9)',
        data: [null, <?php echo "$MPHR"; ?>],
        tooltip: {
            valueSuffix: ' 회'
        },
        pointPadding: 0.15,
        pointPlacement: 0,
    },  {
        name: '권장심박수',
        color: 'rgba(255,255,153,1)',
        data: [<?php echo $hr_avg;?>],
        tooltip: {
            valueSuffix: ' 회'
        },
        pointPadding: 0.3,
        pointPlacement: 0,
		}]
	});
});

</script>

</html>

