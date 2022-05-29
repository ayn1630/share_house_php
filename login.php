<?php
session_start();
//define('_ROOT_DIR', __DIR__ . '/');
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';


//メールの形式チェック
$message = email_check($_POST['email']);
if (isset($message)) {
    echo $message;
    exit;
}

//userDataテーブルの中に入力されたemailがあるかチェックして$rowに格納
//search_table_datxa("userData", "email", $post_email);
//DB内に存在しているか確認

//パスワード確認後sessionに値を渡し、管理者と会員で表示するページを分岐する　会員はさらに各ホームのメニューページに分岐する
$row = search_user_data($_POST['email']);
$admin_row = search_admin_data($_POST['email']);




if (!empty($admin_row) && password_verify($_POST['password'], $admin_row['password'])) {
    $_SESSION['NAME'] = $admin_row['userName'];
    $_SESSION['ADMINID'] = $admin_row['id'];
    $_SESSION['EMAIL'] = $admin_row['email'];
    header("Location: http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "admin0.php");
    return false;
    
} elseif (!empty($row) && password_verify($_POST['password'], $row['password'])) {
    $_SESSION['NAME'] = $row['userName'];
    $_SESSION['USERID'] = $row['userId'];
    $_SESSION['EMAIL'] = $row['email'];
    $row = house_search($row['userId']);
    $_SESSION['HOUSEID'] = $row['houseId'];
    $_SESSION['ROOMNUMBER'] = $row['roomNumber'];

    header("Location: http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "main_menu_house.php");
    return false;
    
} else {
    echo '処理できませんでした。';
    echo "!";
    echo "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
    return false;
}

