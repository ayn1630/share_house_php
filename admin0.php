<?php
session_start();
define('_ROOT_DIR', __DIR__ . '/');
require_once _ROOT_DIR .'/config.php'; 
require_once _ROOT_DIR .'/db_connect.php';
require_once _ROOT_DIR .'/functions.php';

$admin_row = search_admin_data($_SESSION['EMAIL']);

if(!$_SESSION['EMAIL'] || empty($admin_row)) {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "/index.php");
}



echo "ようこそ" . h($_SESSION['NAME']). "さん<br>";
echo "<a href='logout.php'>ログアウトはこちら。</a><br>";



echo "<a href='member_edit.php'>会員情報の編集はこちら</a><br>";
echo "<a href='main_menu.php'>会員用メニューはこちら。</a><br>";

