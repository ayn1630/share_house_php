<?php
session_start();
require_once __DIR__.'/config.php'; 
require_once 'Cache/Lite.php';
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

include (_ROOT_DIR .'header.php');

if (isset($_POST['leaving'])) {
    
     
}elseif (isset($_POST['cancel'])) {
    echo "キャンセルしました。<br>";
    exit("<a href='".MYPAGE."'>マイページに戻る</a><br>");
}
?>


<h2>退会してよろしいですか？</h2>
<form method ="post" action ="">
    <button type="submit"  name="leaving" value="" >退会する</button>
    <button type="submit"  name="cancel" value="" >キャンセル</button>
</form>

<?php include (_ROOT_DIR .'footer.php');?>
