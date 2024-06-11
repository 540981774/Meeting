<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_username = $_POST['reg_username'];
    $reg_password = $_POST['reg_password'];

    // 验证用户输入
    if (empty($reg_username) || empty($reg_password)) {
        $message = "用户名和密码不能为空";
        echo "<script>alert('$message'); window.location.href = 'register.php';</script>";
        exit();
    }

    // 连接数据库
    $con = mysqli_connect("localhost", "root", "root", "message");

    // 检查连接是否成功
    if (!$con) {
        $message = "数据库连接失败: " . mysqli_connect_error();
        echo "<script>alert('$message'); window.location.href = 'register.php';</script>";
        exit();
    }

    // 使用预处理语句来避免 SQL 注入
    $stmt = $con->prepare("INSERT INTO `usertable`(`username`, `password`, `usertype`) VALUES (?, ?, 1)");
    $stmt->bind_param("ss", $reg_username, $reg_password);

    // 执行sql
    if ($stmt->execute()) {
        $message = "注册成功";
        echo "<script>alert('$message'); window.location.href = 'index.html';</script>";
    } else {
        $message = "注册失败: " . $stmt->error;
        echo "<script>alert('$message'); window.location.href = 'register.php';</script>";
    }

    // 关闭数据库
    mysqli_close($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册</title>
     <style>
        * {
            margin: 0;
            padding: 0;
        }
        html {
            height: 100%;
        }
        body {
            height: 100%;
        }
        .container {
            height: 100%;
            background-image: linear-gradient(to right, #95d7f3, #5a94f9);
        }
        .login-wrapper {
            background-color: #89ebfe;
            width: 358px;
            height: 588px;
            border-radius: 15px;
            padding: 0 50px;
            position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .header {
            font-size: 38px;
            font-weight: bold;
            text-align: center;
            line-height: 200px;
            color: #4c84fd;
        }
        .input-item {
            display: block;
            width: 100%;
            margin-bottom: 20px;
            border: 0;
            padding: 10px;
            border-bottom: 1px solid rgb(128, 125, 125);
            font-size: 15px;
            outline: none;
        }
        .input-item::placeholder {
            text-transform: uppercase;
        }
        .btn {
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: 40px;
            background-image: linear-gradient(to right, #77a9ff, #e071ff);
            color: #fff;
        }
        .msg {
            text-align: center;
            line-height: 88px;
        }
        a {
            text-decoration-line: none;
            color: #216afc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="header">User Register</div>
            <form method="post" action="register.php">
                <div class="form-wrapper">
                    <input class="input-item" type="text" name="reg_username" placeholder="username" required>
                    <input class="input-item" type="password" name="reg_password" placeholder="password" required>
                    <input class="btn" type="submit" value="Register">
                </div>
            </form>
            <div class="msg">
                <a href="index.html">返回登录界面</a>
            </div>
        </div>
    </div>
</body>
</html>
