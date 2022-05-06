<?php
session_start();

if(!$_SESSION['EMAIL'] > 0) {
    header('Location: https://snow-milk-sugar-salt-27-1.paiza-user-free.cloud/~ubuntu/index.php');
}

$output = '';
if (isset($_SESSION["EMAIL"])) {
  $output = "Logoutしました。<br><a href='index.php'>ログインはこちら。</a>";
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

