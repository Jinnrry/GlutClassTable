<?php
/**
 * 本页面用于获取教务运行公告（当前学期，第几周，春秋季）
 */

$ch  =  curl_init ();



curl_setopt ( $ch ,  CURLOPT_URL ,  'http://202.193.80.58:81/academic/calendarinfo/viewCalendarInfo.do' );

curl_setopt ( $ch ,  CURLOPT_RETURNTRANSFER  , 1 );

$result= curl_exec ( $ch );

//$info=iconv_substr($result,9054,21);
$info=iconv_substr($result,9039,36);
preg_match_all("/\d+/",$info,$date);           //获取年份，周数
preg_match("/\d+[\d\D]+第/",$info,$term);
$term=iconv_substr($term[0],5,1);
//print_r($term);
//print_r($date);
$data=array("year"=>$date[0][0],"week"=>$date[0][1],"term"=>$term);
echo json_encode($data);


?>