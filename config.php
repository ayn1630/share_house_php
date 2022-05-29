<?php
//require_once('functions.php');
//ini_set('display_errors', 1);

define('DSN', '');
define('DB_USER', 'データベースのユーザー名を入力');
define('DB_PASS', 'データベースのユーザーのパスワードを入力');

define('ADMIN_MAIL', 'admin@admin.com');

define('_ROOT_DIR', __DIR__ . '/');

//define('_LINK', "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']));

define('LOGIN', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "index.php");
define('LOGOUT', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "logout.php");
define('MAINMENU', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "main_menu_house.php");
define('BBS', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "bbs_sql.php");
define('GARBAGE', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "garbage_disposal_table.php");
define('CONTACT', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "contact_form.php");
define('MYPAGE', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "mypage.php");
define('EVENT', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "event.php");
define('HOUSEIF', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "house_info.php");
define('LEAVING', "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "leaving.php");


//define('_ROOT_DIR',    __DIR__ . '/Users/ayana/NetBeansProjects/dir0506/');
//define('_ROOT_DIR', '/Users/ayana/NetBeansProjects/dir0506/');

// 関連ファイルを設置するディレクトリ
//define( "_HOUSE1_DIR", _ROOT_DIR . "menu_house1");
//define( "_HOUSE2_DIR", _ROOT_DIR . "menu_house2");
//define( "_HOUSE3_DIR", _ROOT_DIR . "menu_house3");

//define('HOUSE1', "新宿ハウス");
//define('HOUSE2', "渋谷ハウス");
//define('HOUSE3', "池袋ハウス");

// クラスファイル
//define( "_CLASS_DIR", _PHP_LIBS_DIR . "class/");

// 環境変数 
define( "_SCRIPT_NAME", $_SERVER['SCRIPT_NAME']);



//url
$local_url = "http://localhost/dir0506/";
$paizacloud_url = "https://snow-milk-sugar-salt-27-1.paiza-user-free.cloud/~ubuntu/";

//環境によって切り替える
$url = $local_url;
//$url = $paizacloud_url;

$indexphp = $url. 'index.php';
$adminphp = $url. 'admin0.php';
//$logoutphp = $url. 'logout.php';
$admin0php = $url. 'admin0.php';
$main_menu_house1 = $url. 'menu_house1/main_menu_house1.php';
$main_menu_house = $url. 'main_menu_house.php';
$main_menu_house3 = $url. 'menu_house3/main_menu_house3.php';





