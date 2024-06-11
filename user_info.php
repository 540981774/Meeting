<?php
session_start();
$username = $_SESSION['username'];

// 检查用户是否已登录
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

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户信息</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, #95d7f3, #5a94f9);
            color: #4c84fd;
            text-align: center;
            padding: 20px;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%; /* 圆形头像 */
            border: 3px solid white; /* 头像边框 */
            margin: 10px;
        }
        .user-info, .actions {
            background-color: #ffffff;
            border-radius: 15px;
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            max-width: 400px;
        }
        .user-data {
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 10px;
            margin: 10px auto;
            color: #333;
            font-size: 16px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        input[type="file"], input[type="password"], input[type="submit"] {
            margin-top: 10px;
        }
        input[type="submit"] {
            cursor: pointer;
            background-image: linear-gradient(to right, #77a9ff, #e071ff);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="user-info">
        <h2>用户信息</h2>
        <img src="<?php echo $avatarData; ?>" alt="头像" class="avatar">
        <div class="user-data">用户名: <?php echo $user['username']; ?></div>
        <div class="user-data">用户类型: <?php echo $user['usertype'] == '9' ? '管理员' : '普通用户'; ?></div>
    </div>

    <div class="actions">
        <h2>上传头像</h2>
        <form action="user_info.php" method="post" enctype="multipart/form-data" class="form-group">
            <input type="file" name="avatar" accept="image/*" required>
            <input type="submit" value="上传头像">
        </form>

        <h2>修改密码</h2>
        <form action="user_info.php" method="post">
            输入新密码:
            <input type="password" name="new_password" required>
            <input type="submit" value="更新密码">
        </form>
    </div>
</body>
</html>
