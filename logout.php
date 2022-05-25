<?php
session_start();
require_once('config.php');

if(!isset($_SESSION['EMAIL'])) {
    header("Location: $indexphp");
}

$output = '';
if (isset($_SESSION["EMAIL"])) {
  $output = "Logoutしました。<br>ログインは<a href='$indexphp'>こちら</a>";
} else {
  $output = 'SessionがTimeoutしました。';
}
//セッション変数のクリア
$_SESSION = array();
//セッションクッキーも削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
//セッションクリア
session_destroy();

echo $output;

