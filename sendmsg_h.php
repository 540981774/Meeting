<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $msg = $_POST['msg'];

    // 连接数据库
    $con = mysqli_connect("localhost", "root", "root", "message");

    // 获取用户类型
    $userQuery = "SELECT `usertype` FROM `usertable` WHERE `username` = '$username'";
    $userResult = mysqli_query($con, $userQuery);
    $userRow = mysqli_fetch_assoc($userResult);
    $usertype = $userRow['usertype'];

    // 设置审核状态
    $isApproved = ($usertype == '9') ? 1 : 0;

    // 插入消息
    $sqlstr = "INSERT INTO `message`(`username`, `msg`, `is_approved`) VALUES('$username', '$msg', $isApproved)";

    // 执行sql
    $result = mysqli_query($con, $sqlstr);

    // 判断消息是否发送成功
    if ($result) {
        header("Location: sendmsg.php");
        exit();
    } else {
        echo "消息发送失败";
    }

    mysqli_close($con);
} else {
    echo "请先登录";
}
?>
