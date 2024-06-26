# Meeting（聊天会议）
本项目用前端HTML、CSS、JS，后端PHP以及数据库MySQL开发一个聊天会议网站。该项目是一个用户登录、注册及消息发送系统，具备用户认证和消息管理功能。管理员可以审核和删除消息，普通用户可以发送消息和管理个人信息。
## 页面设计
### 1.登录页面 (index.html)
      功能: 提供用户登录入口。
      设计: 包含用户名和密码输入框，登录按钮和注册链接。
### 2.注册页面 (register.php)
      功能: 提供用户注册入口。
      设计: 包含用户名和密码输入框，注册按钮和登录页面链接。
### 3.消息页面 (sendmsg.php)
      功能: 显示用户消息，允许用户发送消息，管理员可审核和删除消息。
      设计: 包含消息显示区、消息发送输入框和发送按钮。
### 4.用户信息页面 (user_info.php)
      功能: 显示用户信息，允许用户上传头像和修改密码。
      设计: 包含用户信息展示区、头像上传和密码修改功能。
## 功能描述
### 1.登录功能
      功能描述: 用户通过输入用户名和密码进行登录验证，成功登录后跳转到消息页面。
      文件: login.php
### 2.注册功能
      功能描述: 用户通过输入用户名和密码进行注册，成功注册后跳转到登录页面。
      文件: register.php
### 3.消息发送功能
      功能描述: 用户通过输入消息内容进行消息发送，管理员消息无需审核直接显示，普通用户消息需审核后显示。
      文件: sendmsg.php
### 4.审核消息功能
      功能描述: 管理员可以审核和删除用户消息。
      文件: approve_message.php, delete_message.php
### 5.用户信息功能
      功能描述: 用户可以查看和修改个人信息，包括上传头像和修改密码。
      文件: user_info.php
## 数据库设计
### 数据库: message
#### 表: usertable 
      字段:
        Id (INT, 主键, 自动增长)
        username (VARCHAR)
        password (VARCHAR)
        usertype (INT, 1 表示普通用户，9 表示管理员)
        avatar_path (BLOB, 存储头像数据)
#### 表: message
      字段:
        Id (INT, 主键, 自动增长)
        username (VARCHAR)
        msg (TEXT)
        is_approved (TINYINT, 0 表示未审核, 1 表示已审核)
## 使用说明
1.设置数据库:
      创建名为 message 的数据库。
      创建 usertable 和 message 两张表，字段如上所述。

2.配置服务器:
      使用 Apache 或 Nginx 服务器，并确保 PHP 和 MySQL 已正确配置。

3.部署代码:
      将项目文件上传到服务器的 www 或 public_html 目录。

4.访问应用:
      在浏览器中访问应用首页 index.html，进行登录或注册操作。

5.管理员操作:
      管理员登录后可以审核和删除消息，普通用户只能发送消息和查看个人信息。

6.用户信息管理:
      用户可以在 user_info.php 页面上传头像和修改密码。
