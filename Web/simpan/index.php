<?php 
/**** 병원측알람확인 ****/
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

if($HT_attack == 1){
$msg = "[긴급]위급환자발생";
echo "<script type=\"text/javascript\">alert(\"$msg\");location.replace('http://localhost/simpan/alert.php');</script>";
}

mysqli_close($conn);

$refresh_time="20";// 여기에 몇초마다 refresh 할지를 지정하세요^^*
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";

?>

<!DOCTYPE HTML>
<!--
	Typify by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>심질환전문병원:심판</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body>
        
		<!-- Banner -->
			<section id="banner">
                <h2>심질환전문병원<br><strong>심판</strong></h2>
                <p>각종 심질환 및 위급상황에 최적화 되어있는 병원입니다.</p>
				<ul class="actions">
					<li><a href="/var/www/html/heart/index.php" class="button special">심장을부탁해:심박맨 서비스 바로가기</a></li>
				</ul>
			</section>

		<!-- One -->
			<section id="one" class="wrapper special">
				<div class="inner">
					<header class="major">
						<h2>심판의 심장기능판별시스템 MENU </h2>
					</header>
					<div class="features">
						<div class="feature">
							<i class="fa fa-diamond"></i>
							<h3>Real-Time</h3>
							<p>실시간 환자의 심박상태</p>
						</div>
						<div class="feature">
							<i class="fa fa-copy"></i>
							<h3>Emergency</h3>
                            <p>위급상황 현황보기</p>
                        </div>
						<div class="feature">
							<i class="fa fa-paper-plane-o"></i>
							<h3>VIDEO</h3>
							<p>시스템 활용 데모비디오</p>
						</div>
						<div class="feature">
							<i class="fa fa-save"></i>
							<h3>Heart stat</h3>
							<p>DIFF / REACH / NON-STABLE 결과분석</p>
						</div>
						<div class="feature">
							<i class="fa fa-envelope-o"></i>
                            <h3>Setting</h3>
							<p>위급상황 Setting</p>
						</div>
					</div>
				</div>
			</section>

		<!-- Two -->
			<section id="two" class="wrapper style2 special">
				<div class="inner narrow">
					<header>
						<h2>Get in touch</h2>
					</header>
					<form class="grid-form" method="post" action="#">
						<div class="form-control narrow">
							<label for="name">Name</label>
							<input name="name" id="name" type="text">
						</div>
						<div class="form-control narrow">
							<label for="email">Email</label>
							<input name="email" id="email" type="email">
						</div>
						<div class="form-control">
							<label for="message">Message</label>
							<textarea name="message" id="message" rows="4"></textarea>
						</div>
						<ul class="actions">
							<li><input value="Send Message" type="submit"></li>
						</ul>
					</form>
				</div>
			</section>
			
					<!-- Footer -->
			<footer id="footer">
				<div class="copyright">
					&copy; 당신의심장을위하여! <a href="http://127.0.0.1/simpan/">SIMPAN</a>.
				</div>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>

