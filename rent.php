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
        <title>利用料および共益費の支払い方法</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <hr>
        利用料および共益費の支払い方法
        <hr>
        <div>支払日：毎月末最終日<br>
        ※最終日が土日の場合には前日金曜日にご入金ください。</div><br>
        <div>支払額：契約書参照</div><br>
        <div>支払い先：<br>
        銀行名：○○○○○銀行<br>
        口座種類：普通<br>
        口座番号：××××××××××<br>
        振込先：△△△△△△△△△△株式会社
        </div>
        <br><a href='infomation.php'>戻る</a><br>
    </body>
</html>








