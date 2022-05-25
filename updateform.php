<?php session_start(); ?>

<hr>
更新画面
<hr>
<a href ="member_edit.php">戻る</a>

<?php
require_once('db_connect.php');
$pdo = db_connect();

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);


if(!$login_mail == ADMIN_MAIL) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
}


//idが存在するか、0以上か
if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $_SESSION['id'] = $id;
    print $id;
    
}else{
    exit("パラメータ不正です");
}

//$id = 1;
//$_SESSION['id'] = $id;

// put your code here
try {
    $sql = "select * from userData where id = ?";

// SQLテンプレートの解釈
    $stmh = $pdo->prepare($sql);
// 値の設定
    $stmh->bindValue(1, $id, PDO::PARAM_INT);

// 実行
    $stmh->execute();

    $count = $stmh->rowCount();
    print "検索結果は" . $count . "件です。<br>";
} catch (Exception $exc) {
    print "エラー:" . $exc->getMessage();
}

if ($count < 1) {
    print "更新データがありません。<br>";
} else {
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
?>

    <form action="member_edit.php" method="POST">
        番号:<?= h($row['id']) ?><br>
        username:<br>
        <input type ="text" name="username" value="<?= h($row['username'], ENT_QUOTES) ?>"<br>
        <br>
        email:<br>
        <input type="text" name="email" value="<?= h($row['email'], ENT_QUOTES) ?>"<br>
        <br>
        password:<br>
        <input type="text" name="password" value="<?= h($row['password'], ENT_QUOTES) ?>"<br>
        <br>
        <input type="hidden" name="action" value="update" />
        <input type="submit" value="更　新" />
    </form>

<?php
}
?>
