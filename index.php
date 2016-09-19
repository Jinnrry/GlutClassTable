<?php

session_start();
include'OcrKing.php';   //包含OcrKing
include 'mysql.php';


if(!empty($_COOKIE['sid'])){
	$username=$_COOKIE['sid'];
	//$username='3140767238';     //用户学号
	$_SESSION['sid']=$username;
}
else
{
	echo '
	    <script language="javascript">
			window.location= "test.html";
		</script>
	
	
	';
}

/***************OCR验证码识别部分***********************/

//保存验证码图片到本地，同时保存对应的cookie
$down = getRemoteFile('http://202.193.80.58:81//academic/getCaptcha.do','.jpg');


//实例化OcrKing识别
$ocrking = new OcrKing(API_KEY);

//上传图片识别 请在doOcrKing方法前调用
$ocrking->setFilePath($down['imagepath']);

//提交识别
$ocrking->doOcrKing($var);

//检查识别状态
if (!$ocrking->getStatus()) {
	die ($ocrking->getError());
}

//获取识别结果
$result = $ocrking->getResult();



/*
echo '识别状态：'.($result['ResultList']['Item']['Status']? '成功' : '失败');
if ($result['ResultList']['Item']['Status'] == 'true') 
{
		echo '<br /><br />原始图片： <br /><br /><img src="' . $result['ResultList']['Item']['SrcFile'] . '">' ;
		echo '<br /><br />处理后图片： <br /><br /><img src="' . $result['ResultList']['Item']['DesFile'] . '">' ;
		echo '<br /><br />识别结果：'.$result['ResultList']['Item']['Result'] ;
		echo '<br /><br /><a href="'.$down['cookieurl'].'" target="_blank">对应cookie</a>' ;
}
*/



/*************将OcrKing返回的Cookie文件Url路径转换为服务器相对路径**********************/
/**Linux服务器***/

/*

$url=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$url=str_replace("/index.php","",$url);
$url="http://".$url;
//echo $url.'<br>';
$cookie=$down['cookieurl'];
$cookie=str_replace($url,".",$cookie);
//echo $cookie;
$_SESSION['cookie_file']=$cookie;



//如果项目出错，请在此处输出$_SESSION['cookie_file']，检查文件是否存在
*/


/**Window服务器版本***/
/**********************************/
/*注意！！！！Window服务器需要修改d:\\webroot为你自己的服务器绝对路径！！！*/
/**********************************/



$url=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$url=str_replace("/index.php","",$url);
$url="http:\\\\".$url;

$cookie = str_replace("/","\\",$down['cookieurl']);
$cookie = str_replace($url,"d:\\webroot",$cookie);
//echo $cookie;
$_SESSION['cookie_file']=$cookie;
//如果项目出错，请在此处输出$_SESSION['cookie_file']，检查文件是否存在


/******************验证码识别结束***********************/

/*
 *从数据库获取用户密码
 * */

$sql="select * from userpassword where sid='{$username}'";
$re=$mysqli->query($sql);
if($re->num_rows>0)
{
   // echo '有数据';
   $data=$re->fetch_assoc();
    $password=$data['jw'];
}
else
{
    //数据库没有用户的教务处密码
    echo '
    <script language="javascript">
        window.location= "updatePw.php";
    </script>
    ';
    exit;
}






/**********************模拟登陆部分*********************************/
$checkcode=$result['ResultList']['Item']['Result'];
$login_url='http://202.193.80.58:81/academic/j_acegi_security_check';
$post="groupId=&j_username=$username&j_password=$password&j_captcha=$checkcode&button1=%B5%C7%C2%BC";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_COOKIEFILE , $cookie);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
//curl_setopt ($ch, CURLOPT_REFERER,'http://210.36.22.93/');
$result=curl_exec($ch);
$result=iconv('GBK','UTF-8',$result);  //转换编码



if(strpos($result,'您输入的验证码不正确'))
{
    echo '
    <script language="javascript">
        window.location= "index.php";
    </script>
    ';
    exit;
}
if(strpos($result,'密码不匹配'))
{
    header("Location: updatePw.php");
    exit;
}
if(strpos($result,'不存在'))
{
    //header("Location: index.php");

    return '用户名不存在';
    exit();
}

if(strpos($result,'frameset'))   //登陆成功
{
    //header("Location: index.php");
    $_SESSION['login']=true;
    echo '
    <script language="javascript">
        window.location= "info.php";
    </script>
    ';
    exit;


}


echo $result;
curl_close($ch);

