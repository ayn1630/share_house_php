<?php
session_start();
require_once 'Cache/Lite.php';
require_once __DIR__ . '/config.php';
require_once _ROOT_DIR . 'db_connect.php';
require_once _ROOT_DIR . 'functions.php';
?>

<hr>
更新画面
<hr>

<?php
if($_SESSION['EMAIL'] == ADMIN_MAIL) {
    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
    exit;
}

include (_ROOT_DIR . '/header.php');


//idが存在するか、0以上か
if (isset($_POST['userName_edit']) && $_POST['userName_edit'] == $_SESSION['NAME']) {
    try {
        $pdo = db_connect();
        $sql = "select userName from user where userName = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $_POST['userName_edit'], PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->rowCount();

    } catch (Exception $exc) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
        exit($e->getMessage() . PHP_EOL);
        print "エラー:" . $exc->getMessage();
    }
    if ($count < 1) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
    } else {
        $userName = $stmt->fetch(PDO::FETCH_ASSOC);
        $userName =  $userName['userName'];
        
        $html = "<form method ='post' action ='mypage.php'>";
        $html .= "<p>現在のユーザー名 : ";
        $html .= $userName;
        $html .= "</p>";
        $html .= "<p>新しいユーザー名 : ";
        $html .= '<input type="text" name="userName"></p>';
        $html .= '<input type="hidden" name="action" value="update" />';
        $html .= '<br><input type="submit" value="　確定　">';
        $html .= '<input type="submit" name="cancel" value="　キャンセル　" /></form>';
        echo $html;
        
    }

} elseif (isset($_POST['email_edit']) && $_POST['email_edit'] == $_SESSION['EMAIL']) {
    try {
        $pdo = db_connect();
        $sql = "select email from user where email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $_POST['email_edit'], PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->rowCount();

    } catch (Exception $exc) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
        exit($exc->getMessage() . PHP_EOL);
        print "エラー:" . $exc->getMessage();
    }
    if ($count < 1) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
    } else {
        $email = $stmt->fetch(PDO::FETCH_ASSOC);
        $html =  $email['email'];
    }
} elseif (isset($_POST['password_edit']) && $_POST['password_edit']) {
    try {
        $pdo = db_connect();
        $sql = "select password from user where password = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $_POST['password_edit'], PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->rowCount();

    } catch (Exception $exc) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
        exit($exc->getMessage() . PHP_EOL);
        print "エラー:" . $exc->getMessage();
    }
    if ($count < 1) {
        $message = "処理できませんでした。<br>";
        $message .= "メインメニューに<a href='" . MAINMENU . "'>戻る</a>。<br>";
        echo $message;
    } else {
        $password = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $password['password'];
    }
} else {
    echo "パラメータ不正です<br>";
    echo "<a href ='mypage.php'>戻る</a><br>";
    exit;
}

?>




<?php

include (_ROOT_DIR . '/footer.php');