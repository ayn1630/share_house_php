<?php
session_start();
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

if (isset($_GET['username']) && isset($_GET['link_pass'])){
    //必要なパラメータがある場合、データベースを操作
    $userdata = check_preuser($_GET['username'], $_GET['link_pass']);
    if(!empty($userdata) && count($userdata) >= 1){
        //パラメータが合致する
        //preuser(仮登録)テーブルから削除してuserテーブルにデータを挿入する(本登録)
        $message = delete_preuser_and_regist_user($userdata);
        echo $message;
    }else {
        //パラメータが合致しない
        $message = "このURLは無効です。<br>";
        $message .= "新規会員登録は<a href='".LOGIN."'>こちら</a>。<br>";
        echo $message;
    }
    
}else {
    //パラメータがない
    $message = "このURLは無効です。<br>";
    $message .= "新規会員登録は<a href='".LOGIN."'>こちら</a>。<br>";
    echo $message;
}


