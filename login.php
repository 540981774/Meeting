<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

// 连接数据库
$con = mysqli_connect("localhost", "root", "root", "message");
if (!$con) {
    die("连接失败: " . mysqli_connect_error());
}

// 验证用户名和密码
$sqlstr = "SELECT * FROM `usertable` WHERE `username` = '$username' AND `password` = '$password'";
$result = mysqli_query($con, $sqlstr);
if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    header('Location: sendmsg.php');
} else {
    echo "用户名或密码错误";
}

mysqli_close($con);
?>
