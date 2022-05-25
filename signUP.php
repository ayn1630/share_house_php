<?php
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

//データベースへ接続、テーブルがない場合は作成(db_connect.phpより)
create_preuser_table();
create_user_table();

//部屋のパスワードチェック(db_connect.phpより)
$roomPass = roompass_check($_POST['roompass']);

//メールの形式チェック(functions.phpより)
$message = email_check($_POST['email']);
if (isset($message)) {
    echo $message;
    exit;
}

//ログインパスワードの正規表現チェック(functions.phpより)
$message = password_check($_POST['password']);
if (isset($message)) {
    echo $message;
    exit;
}

//preuserテーブルに登録実行(db_connect.phpより)
//本人確認用のメールを送信
$message = regist_preuser($_POST['username'], $_POST['email'], $_POST['password'], $roomPass);
echo $message;


