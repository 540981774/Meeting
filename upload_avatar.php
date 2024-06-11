<?php
session_start();
$username = $_SESSION['username'];

// 检查是否已登录
if (!$username) {
    echo "请先登录。";
    exit;
}

// 打开数据库连接
$con = mysqli_connect("localhost", "root", "root", "message");
if (!$con) {
    die("连接失败: " . mysqli_connect_error());
}

// 如果是POST请求，则处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['avatar'])) {
        handleAvatarUpload($username, $con);
    } elseif (isset($_POST['new_password'])) {
        updatePassword($username, $_POST['new_password'], $con);
    }
}

// 获取用户信息
$sql = "SELECT * FROM usertable WHERE username='$username'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "用户信息获取失败。";
    mysqli_close($con);
    exit;
}

// 获取头像数据
$avatarData = $user['avatar_path'] ? 'data:image/jpeg;base64,' . base64_encode($user['avatar_path']) : 'default_avatar.png';

mysqli_close($con);

function handleAvatarUpload($username, $con) {
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));

    // 检查是否为有效图片
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "文件不是图片。";
        $uploadOk = 0;
    }

    // 检查文件大小
    if ($_FILES["avatar"]["size"] > 5000000) { // 最大5MB
        echo "对不起，您的文件太大。";
        $uploadOk = 0;
    }

    // 检查文件类型
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "对不起，只允许 JPG, JPEG, PNG & GIF 文件。";
        $uploadOk = 0;
    }

    // 尝试上传文件
    if ($uploadOk == 1) {
        $imgData = addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
        $sql = "UPDATE usertable SET avatar_path='$imgData' WHERE username='$username'";
        if (mysqli_query($con, $sql)) {
            echo "头像上传成功。";
        } else {
            echo "更新数据库时出错: " . mysqli_error($con);
        }
    } else {
        echo "对不起，您的文件未上传。";
    }
}

function updatePassword($username, $newPassword, $con) {
    $sql = "UPDATE usertable SET password='$newPassword' WHERE username='$username'";
    if (mysqli_query($con, $sql)) {
        echo "密码已更新。";
    } else {
        echo "更新密码时出错: " . mysqli_error($con);
    }
}
?>