<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // 如果用户未登录，重定向到登录页面
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 连接数据库
    $con = mysqli_connect("localhost", "root", "root", "message");
    if (!$con) {
        die("连接失败: " . mysqli_connect_error());
    }

    // 删除指定ID的消息
    $sql = "DELETE FROM `message` WHERE `Id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('删除成功！'); window.location.href='sendmsg.php';</script>";
    } else {
        echo "<script>alert('删除失败：' . $stmt->error); window.location.href='sendmsg.php';</script>";
    }

    mysqli_close($con);
}
?>
