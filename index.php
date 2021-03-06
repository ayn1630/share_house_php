<?php
session_start();
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

?>

<!DOCTYPE html>
<html lang="ja">
 <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
   <title>Login</title>
 </head>
 <body>
   <h1>ようこそ、ログインしてください</h1>
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
     <label for="roompass">入室パスワード</label>
     <input type="roompass" name="roompass">
     <button type="submit">Sign Up!</button>
     <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
   </form>
 </body>
</html>
