<html>
<head>
    <title>更新密码</title>
</head>


<body>

<?php

session_start();
if(empty($_POST['jwpw'])) {
    ?>


    您好！XXXX您的学号是：<?php echo $_SESSION['sid']; ?><br>

    <form action="#" method="post">
        在易班更新您的教务处密码：<input type="password" name="jwpw"><br>
        <input type="submit" value="确定">
    </form>

    <?php
}
else
    {
        include 'mysql.php';
        /*检查用户是否存在*/
        $sql="select * from userpassword where sid= ? ";
		
		$stmt=$mysqli->prepare($sql);//准备sql语句

		$stmt->bind_param("s",$sid);//绑定参数
		
		$sid=$_SESSION['sid'];
		
		$stmt->execute();  //执行操作

		$stmt -> store_result ();//一次将所有结果都获取过来
		
        if($stmt -> num_rows )
        {
            //echo '存在该用户';
            $sql="update userpassword set jw='{$_POST['jwpw']}' where sid='{$_SESSION['sid']}'";
        }
        else{
            //echo '不存在该用户';
            $sql="insert into userpassword (sid,jw) VALUES ('{$_SESSION['sid']}','{$_POST['jwpw']}')";
        }
        //echo $sql;

        if($mysqli->query($sql))
        {
            echo "修改成功！";
            echo '
                <script language="javascript">
                    window.location= "index.php";
                </script>
                ';
        }
		else
		{
			echo '数据库更新/插入出错！';	
		}



    }
    ?>




</body>


</html>
