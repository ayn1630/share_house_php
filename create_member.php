<?php
session_start();
require_once('db_connect.php');

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}


//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);

if(!$login_mail == ADMIN_MAIL) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>新しいメンバーの追加</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <hr>
        新規登録画面
        <hr>
        <a href ="member_edit.php">戻る</a>
        <form method ="post" action ="member_edit.php">
            username:<br>
            <input type ="text" name="username">
            <br>
            email:<br>
            <input type="text" name="email" value="" />  
            <br>
            password:<br>
            <input type="text" name="password" value="" />
            <br>
            <input type="hidden" name="action" value="insert" />
            <input type="submit" value="送信" />
        </form>
    </body>
</html>
