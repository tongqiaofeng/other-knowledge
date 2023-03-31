<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJW4jsPlNKgv6jFm3B5Edp5ywgdqLWdmc&sensor=true">
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
<title>手表详情</title>

<?php
	error_reporting(0); 
	
	$AddrCnt = 0;
	$PlaceData = '';
	$keyword = $_POST["a1"];
	if(strlen($keyword) == 0)
		$keyword = "324325342df";
	//$keyword = '上海';
	
	$mysql_conf = array(
		'host'    => 'b-34g8pjsefojud0.bch.rds.gz.baidubce.com:3306', 
		'db'      => 'b_34g8pjsefojud0', 
		'db_user' => 'b_34g8pjsefojud0', 
		'db_pwd'  => 'qspqsp', 
		);
	$mysql_conn = @mysql_connect($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
	if (!$mysql_conn) {
		die("could not connect to the database:\n" . mysql_error());//诊断连接错误
	}
	mysql_query("set names 'utf8'");//编码转化
	$select_db = mysql_select_db($mysql_conf['db']);
	if (!$select_db) {
		die("could not connect to the db:\n" .  mysql_error());
	}
	$sql = "";
	if(strpos($keyword, ",") !== false)
	{
		$keyword = str_replace(",", "','", $keyword);
		$sql = "select * from place2 where (品牌 = '劳力士') and (城市 IN ('" . $keyword . "') or 国家 IN ('" . $keyword . "')) order by 城市;";
	}
	else
		$sql = "select * from place2 where (品牌 = '劳力士') and (城市 = '" . $keyword . "' or 国家 = '" . $keyword . "') order by 城市;";
	//echo $sql;
	
	$res = mysql_query($sql);
	if(!$res) 
	{
		die("could get the res:\n" . mysql_error());
	}
	
	$PlaceData = 'var data_info = [';
	while($row = mysql_fetch_assoc($res)) 
	{
		$pingp = $row["品牌"];
		$city = $row["城市"];
		$tel = $row["官方电话"];
		$email = $row["官方邮箱"];
		$dpmc = $row["店铺名称"];
		$addr = $row["地址"];
		$addr_EN = $row["地址_英文"];
		$gps3 = $row["GPS"];
		
		//echo '----------------------------<br>';
		//echo "店铺名称：$dpmc<br>";		
		//echo "城市：$city<br>";			
		//echo "地址：$addr_EN<br>";		
		//echo '----------------------------<br>';
		
		if(strlen($addr_EN) > 0)
		{
			$per = "[$gps3,\"品牌: $pingp<br />店铺名称: <a href=\\\"https://www.google.com/maps/dir/?api=1&language=zh-CN&origin=&destination=$gps3\\\" target=\\\"_self\\\">$dpmc</a><br /><div id=\\\"addr\\\" onclick=\\\"Clickaddr();\\\" oncopy=\\\"Copyaddr(event,this);\\\">$addr</div><br /><div id=\\\"addr\\\" onclick=\\\"Clickaddr();\\\" oncopy=\\\"Copyaddr(event,this);\\\">$addr_EN</div><br />官方电话: $tel\"],";
		
		}
		else
		{
			$per = "[$gps3,\"品牌: $pingp<br />店铺名称: <a href=\\\"https://www.google.com/maps/dir/?api=1&language=zh-CN&origin=&destination=$gps3\\\" target=\\\"_self\\\">$dpmc</a><br /><div id=\\\"addr\\\" onclick=\\\"Clickaddr();\\\" oncopy=\\\"Copyaddr(event,this);\\\">$addr</div><br />官方电话: $tel\"],";
		
		}
		++$AddrCnt;
		$PlaceData = $PlaceData . $per;
	}
	
	$PlaceData = $PlaceData . '[0,0,""]];';
?>

<style type="text/css">
<!--
html,body
{
height:100%;
margin:0px;
padding:0px;
}

#googleMap
{
width:100%;
height:calc(100% - 50px);
}

-->
</style>

</head>

<body>
<form action="" method="post">  
<input name="a1" type="text" size="50">
<input type="submit" value="提交">
</form>
<div style="height:50px;">
<button onClick="MapMode();">显示店铺</button>
<button onClick="MyPlace();">我的位置</button>
<br>
<?php
echo "一共查询到".$AddrCnt."个地址";
?>
</div>
<div id="googleMap"></div>

<script> 
	var map = null;
	var Mymarker = null;
	
	var Mylat = 0;
	var Mylong = 0;
	
	function showPosition(position) 
	{	
		Mylat = position.coords.latitude;
		Mylong = position.coords.longitude;
	}
	
	
	navigator.geolocation.getCurrentPosition(showPosition);
	
	function initialize()
	{
	
		var mapProp = {
		  center:new google.maps.LatLng(51.508742,-0.120850),
		  zoom:8,
		  mapTypeId:google.maps.MapTypeId.ROADMAP
		  };
		  
		map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	
	//showwz();
	}
	
	
	
	google.maps.event.addDomListener(window, 'load', initialize);
	
	function showwz1()
	{
		if(Mymarker != null)
			Mymarker.setMap(null);
		//创建一个标记
		var myCenter=new google.maps.LatLng(51.508742,-0.120850);
		Mymarker=new google.maps.Marker({position:myCenter,});
		Mymarker.setMap(map);
		
		var str = "Hello World!";
		//给标记绑定一个提示信息
		var infowindow = new google.maps.InfoWindow({ content:str });
		
		google.maps.event.addListener(Mymarker, 'click', function() {
																		infowindow.open(map,Mymarker);
																	});
		map.panTo(myCenter);
	}
	
	function MapMode()
	{
<?php
		echo "\t\t";
		echo $PlaceData;
		echo "\r\n";
?>
		var myCenter = null;
		for(var i=0;i<data_info.length-1;i++)
		{
			//创建一个标记
			myCenter=new google.maps.LatLng(data_info[i][0],data_info[i][1]);
			var str = data_info[i][2];
			Mymarker=new google.maps.Marker({position:myCenter,});
			Mymarker.setMap(map);
			
			//给标记绑定一个提示信息
			//var infowindow = new google.maps.InfoWindow({ content: str});
			
			//google.maps.event.addListener(Mymarker, 'click', function() {
			//																infowindow.open(map,Mymarker);
			//															});
			
			attachSecretMessage(Mymarker, str);														
		}
		
		if(myCenter != null)
		{
			map.panTo(myCenter);
		}
	}
	
		
	function attachSecretMessage(marker, str) {
	  var infowindow = new google.maps.InfoWindow(
		  { content: str,
			size: new google.maps.Size(50,50)
		  });
	  google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	  });
	}
		
		
	function MyPlace()
	{
		navigator.geolocation.getCurrentPosition(showPosition);
	
		if(Mymarker != null)
			Mymarker.setMap(null);
		//创建一个标记
		var imageRed = {
				url:"./myplace.png",
				scaledSize: new google.maps.Size(15,15),
				size: new google.maps.Size(15,15)
				};
	
		var myCenter=new google.maps.LatLng(Mylat, Mylong);
		Mymarker=new google.maps.Marker({position:myCenter,icon:imageRed,});
		Mymarker.setMap(map);
		
		//var urlDaohan = "https://www.google.com/maps/dir/?api=1&origin=&destination=22.5428600000,114.0595600000&language=zh-CN"
		//给标记绑定一个提示信息
		var infowindow = new google.maps.InfoWindow({ content:"Hello World!<a href=\"http://www.baidu.com\">123</a>" });
		
		google.maps.event.addListener(Mymarker, 'click', function() {
																		infowindow.open(map,Mymarker);
																	});
	
		map.panTo(myCenter);
	}


function Clickaddr(){
    document.execCommand("copy");
}

function Copyaddr(event,thisDiv){
    if(isIE()){
        if(window.clipboardData){
            window.clipboardData.setData("Text", thisDiv.textContent);
            alert('地址复制成功!');
        }
    }else{
        event.preventDefault();
        if (event.clipboardData) {
            event.clipboardData.setData("text/plain", thisDiv.textContent);
            alert('地址复制成功!');
        }
    }
}

function isIE(){
    var input = window.document.createElement ("input");
    if (window.ActiveXObject === undefined) return null;
    if (!window.XMLHttpRequest) return 6;
    if (!window.document.querySelector) return 7;
    if (!window.document.addEventListener) return 8;
    if (!window.atob) return 9;
    if (!input.dataset) return 10;
    return 11;
}
</script>
</html>
