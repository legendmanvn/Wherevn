<?php
session_start();
require_once("Facebook/autoload.php");
// truyen du lieu the
$fb = new Facebook\Facebook([
  'app_id' => '1082640515081116',
  'app_secret' => 'e70f6b7cb0cdf127d2a94fc5aaa74d4b',
  'default_graph_version' => 'v2.6',
  ]);
  
  $helper = $fb->getCanvasHelper();
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://wherevn.com/check.php', $permissions);

//echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Thu thế giới vào trong tầm mắt</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="author" content="Công ty TNHH Giải pháp số iSolution" />
        <meta name="description" content="Chia sẻ vị trí - Rao vặt miễn phí" />
		<meta property="og:image"   content="images/logo.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
            html, body, #map-canvas {
                height: 100%;
                margin: 0px;
                padding: 0px
            }
        </style>
		<!-- css Gamebank-->
        
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!-- Script -->
        <script src="assets/js/jquery-2.0.2.min.js"></script>
        <script src="js/jquery.form.js"></script> 
        <script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOsEQMLECadC73KgVuvRr9J8yK1hwL6tE&language=vi"></script>
        <script>
			var customIcons = {
				cung: {
					icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
				},
				cau: {
					icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
				}
			};
			function getURLParameter(name) {
				return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
			}
			
            function initialize() {
				var infoWindow = new google.maps.InfoWindow;
               // downloadUrl("phpsqlajax_genxml.php", function(data) {
				var urlshare = "phpsqlajax_genxml.php?sharecode=" + getURLParameter('sharecode');
                downloadUrl("genxml.php", function(data) {
					var xml = data.responseXML;
					var markers = xml.documentElement.getElementsByTagName("marker");
					for (var i = 0; i < markers.length; i++) {
						var userid = markers[i].getAttribute("userid");
						var phonenumber = markers[i].getAttribute("phonenumber");
						var description = markers[i].getAttribute("description");
						var name = markers[i].getAttribute("name");
						var address = markers[i].getAttribute("address");
						var type = markers[i].getAttribute("type");
						var point = new google.maps.LatLng(
							parseFloat(markers[i].getAttribute("lat")),
							parseFloat(markers[i].getAttribute("lng")));
						var html = "Mã số: " + userid + "<br/><b>" + name + "</b><br/>"+ "Điện thoại: <b>"+ phonenumber +"</b><br/><b>Địa chỉ: </b>"+ address + "<br/><b>Mô tả: </b>" + description + "<br/>" + '<a target="_blank" href="info.php?id='+ userid +'"><b>Thông tin chi tiết</b></a>'+' | <a href="'+ loadPopup() +'"><b>Videos</b></a>';
						var icon = customIcons[type] || {};
						var marker = new google.maps.Marker({
							map: map,
							position: point,
							icon: icon.icon
						});
						bindInfoWindow(marker, map, infoWindow, html);
					}
				});
				var myLatlng = new google.maps.LatLng(16.0401833,106.4823441);
                var mapOptions = {
                    zoom: 6,
                    center: myLatlng
                };
                var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            }
			function bindInfoWindow(marker, map, infoWindow, html) {
				google.maps.event.addListener(marker, 'click', function() {
					infoWindow.setContent(html);
					infoWindow.open(map, marker);
				});
			}
           // google.maps.event.addDomListener(window, 'load', initialize);
			function downloadUrl(url, callback) {
				var request = window.ActiveXObject ?
				new ActiveXObject('Microsoft.XMLHTTP') :
				new XMLHttpRequest;
				request.onreadystatechange = function() {
					if (request.readyState == 4) {
						request.onreadystatechange = doNothing;
						callback(request, request.status);
					}
				};
				request.open('GET', url, true);
				request.send(null);
			}
			function doNothing() {}
				$(this).keydown(function(event) {
		if (event.which == 27) { // 27 is 'Ecs' in the keyboard
			disablePopup();  // function close pop up
		}
	});

    $("#background-popup").click(function() {
		disablePopup();  // function close pop up
		disableLoginPopup();
	});

	var popupStatus = 0; // set value

	function loadPopup() {
		if(popupStatus == 0) { // if value is 0, show popup
			$("#to-popup").fadeIn(200); // fadein popup div
			$("#background-popup").css("opacity", "0.8"); // css opacity, supports IE7, IE8
			$("#background-popup").fadeIn(200);
			popupStatus = 1; // and set value to 1
		}
	}

	function disablePopup() {
		if(popupStatus == 1) { // if value is 1, close popup
			$("#to-popup").fadeOut(300);
			$("#background-popup").fadeOut(300);
			$('body,html').css("overflow","auto");//enable scroll bar
			popupStatus = 0;  // and set value to 0
		}
	}
        </script>
	</head>
<body onload="initialize()">
<div id="map-canvas">
</div>
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->


							<!-- Nav -->
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav>

						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a target='_blank' href="member.php"><?php if ($_SESSION['user_id'] <= 0)
							{
								echo '<a target=_blank href="'. $loginUrl.'">Đăng nhập</a>';
							}
							else
							{
								echo "Xin chào: " . $_SESSION['user'].'</a></li>';
							}
							?>
							<li><a target='_blank' href="index.php">Trang chủ</a></li>
							<li><a target="_blank" href="register.php">Thêm địa điểm</a></li>
							<li><a href="index.php">Giới thiệu</a></li>
							<li><a href="index.php">Hướng dẫn</a></li>
						</ul>
					</nav>

				<!-- Main -->
			</div>
<div id="to-popup">
    <span id="btn-close"></span>
		<div id="popup-content">
			<span id="loading-title">Loading...</span>
		</div><!--end #popup-content-->
    </div> <!--to-popup end-->
<div id="background-popup"></div>
		<!-- Scripts -->
		<!--<script src="assets/js/jquery.min.js"></script>-->
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>
