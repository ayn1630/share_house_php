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

if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
}


echo "<a href='member_edit.php'>会員情報の編集はこちら</a><br>";
echo "<a href='main_menu.php'>会員用メニューはこちら。</a><br>";
echo "<a href='logout.php'>ログアウトはこちら。</a><br>";

