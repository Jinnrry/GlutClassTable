<?php
/**
 * Created by PhpStorm.
 * User: 蒋为
 * Date: 2016/8/9
 * Time: 17:49
 */


$mysqli=new mysqli("localhost","root","78667602","ybdb");
/* check connection */
if ( $mysqli -> connect_errno ) {
    printf ( "Connect failed: %s\n" ,  $mysqli -> connect_error );
    exit();
}