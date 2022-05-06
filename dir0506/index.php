<?php
session_start();
require_once('db_connect.php');
$pdo = db_connect();

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}


//ログイン済みの場合
if (isset($_SESSION['EMAIL'])) {
    echo 'ようこそ' .  h($_SESSION['NAME']) . "さん<br>";
    echo "<a href='main_menu.php'>メインメニューはこちら。</a><br>";
    echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
    exit;
}

$sql = "select distinct housename from houseData";
$stmh = $pdo->query($sql);

$optionTags_housename = "";

while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
    $optionTags_housename .= '<option value="'.h($row['housename'], ENT_QUOTES).'">'.h($row['housename'], ENT_QUOTES)."</option>\n";
}

$sql = "select distinct roomnumber from houseData";
$stmh = $pdo->query($sql);

$optionTags_roomnumber = "";
while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
    $optionTags_roomnumber .= '<option value="'.h($row['roomnumber'], ENT_QUOTES).'">'.h($row['roomnumber'], ENT_QUOTES)."</option>\n";
}


?>

<!DOCTYPE html>
<html lang="ja">
 <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
   <title>Login</title>
 </head>
 <body>
   <h1>ようこそ、ログインしてください。</h1>
   <form action="login.php" method="post">
     <label for="email">ログインメールアドレス</label>
     <input type="email" name="email">
     <label for="password">ログインパスワード</label>
     <input type="password" name="password">
     <button type="submit">Sign In!</button>
   </form>
   <h1>初めての方はこちら</h1>
   <form action="signUP.php" method="post">
     <label for="username">ユーザー名</label>
     <input type="username" name="username">
     <label for="email">ログインメールアドレス</label>
     <input type="email" name="email">
     <label for="password">ログインパスワード</label>
     <input type="password" name="password">
     <label for="housename">ハウス名</label>
     <select name="housename"><?=$optionTags_housename ?></select>
     <label for="roomnumber">部屋番号</label>
     <select name="roomnumber"><?=$optionTags_roomnumber ?></select>
     <label for="roompass">部屋のパスワード</label>
     <input type="roompass" name="roompass">
     <button type="submit">Sign Up!</button>
     <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
   </form>
 </body>
</html>
