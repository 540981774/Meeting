<?php
session_start();

// 处理退出登录请求
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.html');
    exit();
}

if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // 如果用户未登录，重定向到登录页面
    exit();
}
$username = $_SESSION['username'];

// 打开数据库
$con = mysqli_connect("localhost", "root", "root", "message");

// 获取用户类型
$userQuery = "SELECT `usertype` FROM `usertable` WHERE `username` = '$username'";
$userResult = mysqli_query($con, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$usertype = $userRow['usertype'];

// 访问数据
$sqlstr = "SELECT `message`.`Id`, `message`.`username`, `message`.`msg`, `message`.`is_approved`, `usertable`.`usertype`, `usertable`.`avatar_path` 
           FROM `usertable`, `message` 
           WHERE `usertable`.`username` = `message`.`username`";

// 执行SQL语句
$result = mysqli_query($con, $sqlstr);

echo "<div class='header'>";
echo "<h1>消息会议</h1>";
if ($usertype == '9') {
    echo "<div class='welcome-msg'>欢迎 $username（管理员）</div>";
    echo "<div class='actions'><a href='user_info.php' class='btn'>用户信息</a>";
    echo "<form action='sendmsg.php' method='post' style='display:inline;'>
            <button type='submit' name='logout' class='btn'>
            退出登录
            </button>
          </form>
        </div>";
} else {
    echo "<div class='welcome-msg'>欢迎 " . htmlspecialchars($username) . "（普通用户）</div>";
    echo "<div class='actions'><a href='user_info.php' class='btn'>用户信息</a>";
    echo "<form action='sendmsg.php' method='post' style='display:inline;'><button type='submit' name='logout' class='btn'>退出登录</button></form></div>";
}
echo "</div>";
// 下划线
echo "<hr>";

// 开始滚动框的div
echo "<div style='height: calc(100vh - 250px); overflow-y: auto; margin: 20px;'>";

// 循环读取得到的每一条记录
echo "<div class='message-container'>";

while ($row = mysqli_fetch_array($result)) {
    $isMyMessage = ($username == $row['username']);
    $messageClass = $isMyMessage ? 'my-message' : 'other-message';
    $color = ($row['usertype'] == '9') ? 'red' : 'black'; // 管理员消息颜色设置为红色
    $avatarData = $row['avatar_path'] ? 'data:image/jpeg;base64,' . base64_encode($row['avatar_path']) : 'default_avatar.png'; // 默认头像
    $isApproved = $row['is_approved'];
    $approvalText = $isApproved ? '' : ' (未审核)';
    $messageColor = $isApproved ? ($isMyMessage ? '#32CD32' : '#C0C0C0') : '#D3D3D3';

    echo "<div class='message $messageClass' style='background-color: $messageColor;'>";
    echo "<img src='$avatarData' alt='头像' class='avatar'>";
    echo "<font color=$color>";
    if ($isMyMessage) {
        // 发送方格式：消息在前，用户名在后
        echo htmlspecialchars($row['msg']) . "：" . htmlspecialchars($row['username']) . $approvalText;
    } else {
        // 接收方格式：用户名在前，消息在后
        echo htmlspecialchars($row['username']) . "：" . htmlspecialchars($row['msg']) . $approvalText;
    }
    echo "</font>";
    // 提供删除链接，如果是管理员或消息的发送者
    if ($username == 'zzq' || $username == $row['username']) {
        echo " <a href='delete_message.php?id=" . $row['Id'] . "' onclick='return confirm(\"Are you sure?\");'>删除</a>";
        if ($username == 'zzq' && !$isApproved) {
            echo " <a href='approve_message.php?id=" . $row['Id'] . "' onclick='return confirm(\"Are you sure?\");'>审核</a>";
        }
    }
    echo "</div><p>";
}

echo "</div>";

// 结束滚动框的div
echo "</div>";

mysqli_close($con);
?>

<style>
    h1{
        height: 20px;
;
    }
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .header {
        text-align: center;
        background-color: #333;
        color: white;
        padding: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        height: 180px;
    }
    .fixed-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: white;
        padding: 10px;
        text-align: center;
    }
    .btn {
        text-align: center;
        padding: 10px;
        width: 10%;
        margin-top: 40px;
        background-image: linear-gradient(to right, #77a9ff, #e071ff);
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }
    .message-container {
        text-align: center;
        margin: 20px;
        height: calc(100vh - 350px);
        overflow-y: auto;
    }
    .message {
        margin: 5px;
        padding: 10px;
        border-radius: 10px;
        width: fit-content;
        max-width: 60%;
    }
    .my-message {
        background-color: #32CD32; /* Light green background for user's own messages */
        margin-left: auto;
        border-top-right-radius: 0;
    }
    .other-message {
        background-color: #C0C0C0; /* White background for other messages */
        margin-right: auto;
        border-top-left-radius: 0;
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        vertical-align: middle;
    }
</style>

<div class="fixed-footer">
    <form action="sendmsg_h.php" method="post">
        <?php echo $username; ?>：
        <input type='text' name='msg' size='100'>
        &nbsp;&nbsp;
        <input class="btn" type='submit' value='发送'>
    </form>
</div>
