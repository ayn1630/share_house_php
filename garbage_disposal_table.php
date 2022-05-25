<?php
session_start();
require_once 'Cache/Lite.php';
require_once __DIR__ . '/config.php';
require_once _ROOT_DIR . 'db_connect.php';
require_once _ROOT_DIR . 'functions.php';

// POSTされた二重送信防止用トークンを取得
$token = isset($_POST["token"]) ? $_POST["token"] : "";
// セッション変数のトークンを取得
$session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";
// セッション変数のトークンを削除
unset($_SESSION["token"]);

date_default_timezone_set('Asia/Tokyo');
create_garbageDisposal();

$this_month = date('n');
$day_count = date('t');
$html = "";
$days = [];
for ($day = 1; $day <= $day_count; $day++) {
    if ($day == date('j')) {
        $html .= '<option value="' . $day . '" selected>' . $day . "日" . '</option>' . "\n";
    } else {
        $html .= '<option value="' . $day . '">' . $day . "日" . '</option>' . "\n";
    }
    $days[] = $day;
}

if (isset($_POST['dispo_regist']) && $token != "" && $token == $session_token) {
    $alart_msg = regist_garbageDisposal($_SESSION['ROOMNUMBER'], $_SESSION['HOUSEID'], $_SESSION['USERID'], $_POST['day'], $days);
    echo javascript_alart($alart_msg);
} elseif (isset($_POST['dispo_correct']) && $token != "" && $token == $session_token) {
    correct_garbageDisposal($_POST['day'], $_SESSION['USERID']);
}

// 二重送信防止用トークンの発行
$token = uniqid('', true);

//トークンをセッション変数にセット
$_SESSION['token'] = $token;

$dispoData = display_garbageDisposal($_SESSION['HOUSEID']);
$htmlDispo = "";
if ($dispoData) {
    foreach ($dispoData as $val) {
        $htmlDispo .= "<tbody><tr><td>";
        $htmlDispo .= $val['roomNumber'] . "号室";
        $htmlDispo .= "</td><td>";
        $htmlDispo .= $val['day_all'];
        $htmlDispo .= "</td></tr></tbody>";
    }
} else {
    $htmlDispo .= "<tbody><tr><td colspan='2'>";
    $htmlDispo .= "今月はまだ登録がありません。";
    $htmlDispo .= "</td></tr></tbody>";
}

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//  header('Location:http://localhost/dir0506/garbage_disposal_table.php');
//  exit;
//}


include (_ROOT_DIR . 'header.php');
?>
<h2 class="bbs_garb"><?= $this_month ?>月のごみ出し登録表<br>
    <span class="subtitle">〜月に2回以上のごみ出しをお願いします〜</span>
</h2>

<div id="form_box">
    <form action="" name="iptfrm" method="post">
        <br />
        <label>ごみを捨てた日：<?= $this_month ?>月</label>
        <select name="day">
<?= $html ?>
        </select>
        <input type="hidden" name="token" value="<?= $token ?>" />
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="dispo_regist" value="　登録　" />
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="dispo_correct" value="　削除　" />
    </form>
</div><!-- /#form_box -->

<table border="1" align="center">
    <thead><tr><th>部屋番号</th><th>捨てた日</th></tr></thead>
<?= $htmlDispo ?>
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
        font-size: 1.2em;
        line-height: 1.2em;
        text-align: center;
        font-weight: normal;

    }
    table {
        font-size: 1.5em;
        text-align: center;
        width: 70%;
        height: 300px;
        margin-top: 10%;
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
    }
    div {
        margin-bottom: 3%;
    }
</style>


