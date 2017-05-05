<?php
header('Content-type:text/html;charset=utf-8');
session_start();
include"function.php";
if(!$_SESSION['login'])  //如果没登陆
{
	echo
	'
    <script language="javascript">
        window.location= "index.php";
    </script>
    ';
	exit;
}

if(empty($_GET['week']))
{
	$data=getClassTbale(2016,2,0);
	$info=new format($data);
	$classdata=$info->getData();
}
else{
	$data=getClassTbale(2016,2,$_GET['week']);
	$info=new format($data);
	$classdata=$info->getData();


}
?>


<html>
<head>

<script src="js/jquery-2.2.0.min.js"></script>
<script>
	<?php

	if(empty($_GET['week']))
		echo 'week=0';
	else
		echo 'week='.$_GET['week'];
	?>


</script>


<script>
	$(function(){
		$.get("GetJwInfo.php",function (data)
		{
			$("#year").html(data.year);
			$("#term").html(data.term);
			if(week!=0)
				$("#week").val(week);
			else
				$("#week").val(data.week);
		},"json")


	})


function submit(){
	$("#form").submit();
}

</script>
</head>



<body>
<div class="header">
	<form action="info.php" method="get" id="form">
		<span id="year"></span>
		<span id="term"></span>
		第  <select id="week" name="week" onchange="submit()">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>

			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>

			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>

			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>

			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
		</select>周
	</form>
</div>


<table border="1px">
<tr><td></td><td>星期一</td><td>星期二</td><td>星期三</td><td>星期四</td><td>星期五</td><td>星期六</td><td>星期天</td></tr>
<tr><td>第1节</td><td><?=$classdata[1][1]?></td><td><?=$classdata[2][1]?></td><td><?=$classdata[3][1]?></td><td><?=$classdata[4][1]?></td><td><?=$classdata[5][1]?></td><td><?=$classdata[6][1]?></td><td><?=$classdata[7][1]?></td></tr>
<tr><td>第2节</td><td><?=$classdata[1][2]?></td><td><?=$classdata[2][2]?></td><td><?=$classdata[3][2]?></td><td><?=$classdata[4][2]?></td><td><?=$classdata[5][2]?></td><td><?=$classdata[6][2]?></td><td><?=$classdata[7][2]?></td></tr>
<tr><td>第3节</td><td><?=$classdata[1][3]?></td><td><?=$classdata[2][3]?></td><td><?=$classdata[3][3]?></td><td><?=$classdata[4][3]?></td><td><?=$classdata[5][3]?></td><td><?=$classdata[6][3]?></td><td><?=$classdata[7][3]?></td></tr>
<tr><td>第4节</td><td><?=$classdata[1][4]?></td><td><?=$classdata[2][4]?></td><td><?=$classdata[3][4]?></td><td><?=$classdata[4][4]?></td><td><?=$classdata[5][4]?></td><td><?=$classdata[6][4]?></td><td><?=$classdata[7][4]?></td></tr>
<tr><td>第5节</td><td><?=$classdata[1][5]?></td><td><?=$classdata[2][5]?></td><td><?=$classdata[3][5]?></td><td><?=$classdata[4][5]?></td><td><?=$classdata[5][5]?></td><td><?=$classdata[6][5]?></td><td><?=$classdata[7][5]?></td></tr>
<tr><td>第6节</td><td><?=$classdata[1][6]?></td><td><?=$classdata[2][6]?></td><td><?=$classdata[3][6]?></td><td><?=$classdata[4][6]?></td><td><?=$classdata[5][6]?></td><td><?=$classdata[6][6]?></td><td><?=$classdata[7][6]?></td></tr>
<tr><td>第7节</td><td><?=$classdata[1][7]?></td><td><?=$classdata[2][7]?></td><td><?=$classdata[3][7]?></td><td><?=$classdata[4][7]?></td><td><?=$classdata[5][7]?></td><td><?=$classdata[6][7]?></td><td><?=$classdata[7][7]?></td></tr>
<tr><td>第8节</td><td><?=$classdata[1][8]?></td><td><?=$classdata[2][8]?></td><td><?=$classdata[3][8]?></td><td><?=$classdata[4][8]?></td><td><?=$classdata[5][8]?></td><td><?=$classdata[6][8]?></td><td><?=$classdata[7][8]?></td></tr>
<tr><td>第9节</td><td><?=$classdata[1][9]?></td><td><?=$classdata[2][9]?></td><td><?=$classdata[3][9]?></td><td><?=$classdata[4][9]?></td><td><?=$classdata[5][9]?></td><td><?=$classdata[6][9]?></td><td><?=$classdata[7][9]?></td></tr>
<tr><td>第10节</td><td><?=$classdata[1][10]?></td><td><?=$classdata[2][10]?></td><td><?=$classdata[3][10]?></td><td><?=$classdata[4][10]?></td><td><?=$classdata[5][10]?></td><td><?=$classdata[6][10]?></td><td><?=$classdata[7][10]?></td></tr>
<tr><td>第11节</td><td><?=$classdata[1][11]?></td><td><?=$classdata[2][11]?></td><td><?=$classdata[3][11]?></td><td><?=$classdata[4][11]?></td><td><?=$classdata[5][11]?></td><td><?=$classdata[6][11]?></td><td><?=$classdata[7][11]?></td></tr>
<tr><td>第12节</td><td><?=$classdata[1][12]?></td><td><?=$classdata[2][12]?></td><td><?=$classdata[3][12]?></td><td><?=$classdata[4][12]?></td><td><?=$classdata[5][12]?></td><td><?=$classdata[6][12]?></td><td><?=$classdata[7][12]?></td></tr>
</table>


</body>

</html>