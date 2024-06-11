<?php
session_start();

if ($_SESSION['username'] != 'zzq') {
    echo "没有权限";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 连接数据库
    $con = mysqli_connect("localhost", "root", "root", "message");
    if (!$con) {
        die("连接失败: " . mysqli_connect_error());
    }

    // 审核指定ID的消息
    $sql = "UPDATE `message` SET `is_approved` = 1 WHERE `Id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('审核成功！'); window.location.href='sendmsg.php';</script>";
    } else {
        echo "<script>alert('审核失败：' . $stmt->error); window.location.href='sendmsg.php';</script>";
    }

    mysqli_close($con);
}
?>
