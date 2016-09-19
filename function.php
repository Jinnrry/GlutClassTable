<?php
/*
*此函数获取一周的课表
*需要先调用Login函数后才能调用该函数
*参数：
*教务处用户名
*密码
*验证码
*cookie
*year学年
*term学期，1代表春季，2代表秋季
*week需要查询的周数（传入0表示本周）
*/
function getClassTbale($year,$term,$week)  
{
	$cookie_file=$_SESSION['cookie_file'];
	/*******获取本学期课表数据部分*******/
	$url='http://202.193.80.58:81/academic/student/currcourse/currcourse.jsdo?groupId=&moduleId=2000';
	$ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url);
	curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($ch2, CURLOPT_REFERER,'http://202.193.80.58:81/academic/index_new.jsp');
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_URL, $url);
    curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_file);
    $response = curl_exec($ch2);
    curl_close($ch2);
     // $response=iconv('GBK','UTF-8//ignore',$response);
	//	print_r($response);
	if(empty($response))
	{
		return false;	
	}
		
	/*******获取周次课表数据部分*******/
	$year-=1980;
	if($week==0)
		$url="http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid={$year}&termid={$term}";
	else
		$url="http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid={$year}&termid={$term}&whichWeek={$week}";
	//	$url='http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid=36&termid=1&whichWeek=11';  //可以加whichWeek参数选择周数，termid为学期（1为春季），yearid为学年，36表示2016年，37表示2017年，依次类推		
	$ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url);
	curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($ch2, CURLOPT_REFERER,'http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do');
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch2, CURLOPT_URL, $url);
    curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_file);
    $response = curl_exec($ch2);
    curl_close($ch2);
     // $response=iconv('GBK','UTF-8//ignore',$response);
	return $response;
}
	
	
	
	
function Login()   //从服务器获取登陆需要的验证码，并将COOKIE保存到SESSION中
{
    $url = "202.193.80.58:81/academic/common/security/login.jsp";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_URL, $url);
    $cookie_file = tempnam('./temp', 'cookie');
    curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file);
    curl_exec($ch);        
    $url='http://202.193.80.58:81/academic/getCaptcha.do';
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    $response = curl_exec($ch);
    curl_close($ch);
    $_SESSION['cookie_file']=$cookie_file;
	echo'<img src="data:image/gif;base64,';
    echo base64_encode($response);
    echo '">';	
	return $cookie_file;
}



function sendLogin($username,$password,$checkcode)//向服务器发送登陆信息
{
	/***********登陆部分******************/
	$cookie_file=$_SESSION['cookie_file'];	
	$login_url='http://202.193.80.58:81/academic/j_acegi_security_check';
	$post="groupId=&j_username=$username&j_password=$password&j_captcha=$checkcode&button1=%B5%C7%C2%BC";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $login_url);
    //curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
  //curl_setopt ($ch, CURLOPT_REFERER,'http://210.36.22.93/');
    $result=curl_exec($ch);
    curl_close($ch);
    $result=iconv('GBK','UTF-8',$result);  //转换编码
    //echo($result);
	if(strpos($result,'您输入的验证码不正确'))	
	{
		//header("Location: index.php");
		return '验证码错误';
		exit();
	}
	if(strpos($result,'密码不匹配'))	
	{
		//header("Location: index.php");
		return '密码错误';
		exit();
	}
	if(strpos($result,'不存在'))	
	{
		//header("Location: index.php");
		return '用户名不存在';
		exit();
	}
	return 'success';
}


/*此类用于将获取到的数据格式化*/
class format{
	private $data;
	public $classinfo;
	
	public function __construct($data)
	{
		$this->classinfo=array(
		"",
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		array('','','','','','','','','','','','',''),
		);
		$this->data=$data;
		$this->clean();	  
		$this->serialize();  //将每个tr标签存到数组中
	}
	
	private function clean()  //清除除table标签以外的内容
	{
		preg_match("/<table cellpadding=\"0\" cellspacing=\"0\" class=\"datalist\">([\s\S]*)<\/table>/U",$this->data,$result);
		$this->data=$result[0];
	}

	private function serialize()//将每个tr标签分开
	{
		preg_match_all("/<tr>([\s\S]*)<\/tr>/U",$this->data,$result);
		$this->data=$result[0];	
	}

	public function getData()
	{
		foreach($this->data  as  $values)
		{
		//	echo $values;
			preg_match("/<td name=\"td1\">.+<\/td>/U",$values,$content);
			@$content=substr($content[0],15,-5);   //课程内容
			preg_match("/<td name=\"td10\">.+<\/td>/U",$values,$place);
			@$place=substr($place[0],16,-5);   //课程内容
		//	echo '地点：'.$place;
			
			//echo $content;
			preg_match("/星期.+<\/td>/",$values,$week);  //识别该课程是星期几
		//	print_r($week);
			preg_match("/第.+节/",$values,$num);  //识别出是第几节课(结果为5、6)
        //  print_r($values);
			@preg_match_all("/\d+/",$num[0],$num2); //将节数保存到一个数组中(结果为[0]=>5[1]=>6)
		//	print_r($num2[0]);
		//	echo '<hr><br>';
			foreach($num2[0] as $num3)
			{
				switch($week[0])
				{
					case '星期一</td>':
					$this->classinfo[1][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期二</td>':
					$this->classinfo[2][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期三</td>':
					$this->classinfo[3][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期四</td>':
					$this->classinfo[4][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期五</td>':
					$this->classinfo[5][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期六</td>':
					$this->classinfo[6][$num3].=$content."<br>".$place."<br>";	
					break;
					case '星期日</td>':
					$this->classinfo[7][$num3].=$content."<br>".$place."<br>";	
					break;
				}	
				
			}
			
		}
		return $this->classinfo;
	}


}


