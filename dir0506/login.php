<?php
session_start();
require_once('db_connect.php');

//htmlエスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

print "テストです<br>";

$login_name = h($_POST['username']);
$login_mail = h($_POST['email']);
$login_pass = h($_POST['password']);
// $login_crypt_pass = crypt($login_pass);



print "テストです2<br>";

//POSTのvalidate
if (!filter_var($login_mail, FILTER_VALIDATE_EMAIL)) {
    echo '入力された値が不正です。';
    return false;
}

print "テストです3<br>";

//DB内でPOSTされたメールアドレスを検索
try {
    $pdo = db_connect();
    $stmt = $pdo->prepare('select * from userData where email = ?');
    $stmt->execute([$login_mail]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    exit($e->getMessage().PHP_EOL);
}

print "テストです4<br>";

//emailがDB内に存在しているか確認
if (!isset($row['email'])) {
    echo 'メールアドレス又はパスワードが間違っています。';
    return false;
}

print "テストです5<br>";

//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($login_pass, $row['password']) && $login_mail == ADMIN_MAIL) {
    $_SESSION['NAME'] = $row['username'];
    $_SESSION['ID'] = $row['id'];
    print "テストです8<br>";
    $_SESSION['EMAIL'] = $row['email'];
    $_SESSION['PASS'] = $login_pass;
    print "テストですadmin<br>";
    echo 'ログインしました';
    header("Location: https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/admin0.php");


}elseif (password_verify($login_pass, $row['password'])) {
    print "テストです6<br>";


    print "テストです7<br>";


    $_SESSION['NAME'] = $row['username'];
    $_SESSION['ID'] = $row['id'];
    print "テストです8<br>";
    $_SESSION['EMAIL'] = $row['email'];
    $_SESSION['PASS'] = $login_pass;
    print "テストです9<br>";
    echo 'ログインしました';
    header("Location: https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/main_menu.php");
} else {
    echo 'メールアドレス又はパスワードが間違っています。';
    return false;
}

print "テストです<br>";

