<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />

<?php
$refresh_time="10";// 여기에 몇초마다 refresh 할지를 지정하세요^^*
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
echo"$pnum";
$sql="SELECT * from (SELECT * FROM patient where pnum=".$pnum." ORDER BY RT_time DESC LIMIT 15) as a order by RT_time ASC";
$result = mysqli_query($conn, $sql);

$str_time="";
$str_atemper="";

while ($row = mysqli_fetch_array($result)) {
 $str_time .="'".$row['RT_time']."',";
 $str_atemper .="".$row['Temperature'].",";
 $str_temp_arr .="[".$row['RT_time'].",".$row['Temperature']."],";
 $str_temp_range .="[".$row['RT_time'].", 36.0 , 38.0 ],";
}

$str_time= substr($str_time,0,-1);
$str_atemper= substr($str_atemper,0,-1);

?>

<!DOCTYPE HTML>
<html>
<title> USER TEMPERATURE </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style=font-weight:bold;> 


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<div id="container2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

 <style type="text/css">
	 ${highcharts.css}
 </style>
 <link rel="stylesheet" type="text/css" href="./highchart/code/css/highcharts.css"/>
 
 
<script type="text/javascript">
	  
	 var ranges = [
		<?php echo $str_temp_range;?>
    ],
    averages = [
		<?php echo $str_temp_arr;?>
    ];


Highcharts.chart('container2', {

    title: {
        text: 'Body Temperatures'
    },

    xAxis: {
        type: 'time'
    },

    yAxis: {
        title: {
            text: '실시간 Temperature (°C)'
        }
    },

    tooltip: {
        crosshairs: true,
        shared: true,
        valueSuffix: '°C'
    },

    legend: {
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
        name: 'Temperature',
        data: averages,
        zIndex: 1,
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: 'rgba[255,0,0,0]',
        }
    }, {
        name: 'Stable Range',
        data: ranges,
        type: 'arearange',
        lineWidth: 0,
        linkedTo: ':previous',
        color: Highcharts.getOptions().colors[0],
        fillOpacity: 0.3,
        zIndex: 0,
        marker: {
            enabled: false
        }
    }]
});

</script>
</html>

