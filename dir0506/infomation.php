<?php
session_start();
require_once('db_connect.php');
$pdo = db_connect();

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
  echo "<a href ='main_menu.php'>メインメニューに戻る</a><br>";
}

if($login_mail == ADMIN_MAIL) {
    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
}


if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>インフォメーション</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <hr>
        インフォメーション
        <hr>
        <a href='rule.php'>ハウスルール</a><br>
        <a href='dustbox.php'>ゴミ捨て場</a><br>
        <a href='rent.php'>利用料および共益費の支払い方法</a><br>

    </body>