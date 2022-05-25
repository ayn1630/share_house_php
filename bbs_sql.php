<?php
session_start();
require_once __DIR__ . '/config.php';
require_once _ROOT_DIR . '/db_connect.php';
require_once _ROOT_DIR . '/functions.php';

// POSTされた二重送信防止用トークンを取得
$token = isset($_POST["token"]) ? $_POST["token"] : "";

// セッション変数のトークンを取得
$session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";

// セッション変数のトークンを削除
unset($_SESSION["token"]);

$weekdays = array('日', '月', '火', '水', '木', '金', '土');
$name = $_SESSION['NAME'];
$userId = $_SESSION['USERID'];
$houseId = $_SESSION['HOUSEID'];

if (isset($_POST['message']) && $_POST['message'] !="" && $token != "" && $token == $session_token) {
    $message = $_POST["message"];
    $message = regist_bbs($name, $message, $userId, $houseId);
    if (isset($message)) {
        echo $message;
        exit;
    }
    $all = bbs_all_data($_SESSION['HOUSEID']);
} elseif (isset($_POST['message_delete'])) {
    delete_bbs($_SESSION['USERID'], $_POST['id']);
    $all = bbs_all_data($_SESSION['HOUSEID']);
} else {
    $all = bbs_all_data($_SESSION['HOUSEID']);
}

// 二重送信防止用トークンの発行
$token = uniqid('', true);

//トークンをセッション変数にセット
$_SESSION['token'] = $token;

$html = bbs_display($all);

include (_ROOT_DIR . '/header.php');
?>

<h2 class="bbs_garb">掲示板<br>
    <span class="subtitle">〜伝言などにご活用ください〜</span>
</h2>

<div id="form_box">

    <form name="form1" method="post" action="">
        <lavel>名前 : <?= h($name) ?></lavel>&nbsp;&nbsp;&nbsp;
        <textarea name="message" cols="35" rows="3"  placeholder="50文字まで。仲良く活用しましょう。"></textarea>
        <input type="hidden" name="token" value="<?= $token ?>" />
        &nbsp;&nbsp;&nbsp;<input type="submit" value="　投稿　">
    </form>
</div>

<table border="1" align="center">
    <thead><tr><th>名前</th><th>メッセージ</th><th>日時</th><th>削除</th></tr></thead>
    <?= $html ?>
</table>

<?php include (_ROOT_DIR . '/footer.php'); ?>


<style>
    h2.bbs_garb {
        font-size: 1.5em;
        line-height: 1.2em;
        text-align: center;
        margin-top: 25%;
    }

    h2 .subtitle {
        font-size: 0.7em;
        font-weight: normal;
    }
    div {
        font-size: 1.0em;
        line-height: 1.2em;
        text-align: center;
        font-weight: normal;

    }
    lavel {
        font-size: 1.1em;


    }

    textarea{
        vertical-align: top;
        font-size: 1.2em;
    }

    table {
        font-size: 1.0em;
        text-align: center;
        width: 80%;
        height: 100px;
        margin-top: 7%;
        margin-bottom: 30%;
    }

    select {
        font-size: 1.2em;
        height: 2.1em;
        width: 3.7em;
    }
    input {
        font-size: 1.0em;
        height: 2.5em;
        text-align: center;
    }

    .message_delete {
        font-size: 1.2em;
        text-align: center;
        height: 1.5em;
        margin-top: 20px;
    }

    #form_box {

    }

    .name {
        width: 20%;

    }

    .msg {
        width: 60%;

    }

    .date {
        width: 20%;
        font-size: 90%;
    }

</style>
