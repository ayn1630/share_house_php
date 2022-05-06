<?php session_start(); ?>

<hr>
更新画面
<hr>
<a href ="mypage.php">戻る</a>

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
$id = h($_SESSION['id']);

$username = h($_POST['username']);
$email = h($_POST['email']);
$password = h($_POST['password']);


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
}

if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}

//idが存在するか、0以上か
if(isset($_POST['action']) && $username == $login_name){
    try {
    $sql = "select username from userData where username = ?";

// SQLテンプレートの解釈
    $stmt = $pdo->prepare($sql);
// 値の設定
    $stmt->bindValue(1, $username, PDO::PARAM_INT);

// 実行
    $stmt->execute();

    $count = $stmt->rowCount();
    print "検索結果は" . $count . "件です。<br>";
    } catch (Exception $exc) {
        print "エラー:" . $exc->getMessage();
    }

    if ($count < 1) {
        print "更新データがありません。<br>";
    } else {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

    <form action="mypage.php" method="POST">
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
    
        
}else{
    echo "<a href ='mypage.php'>戻る</a><br>";
    exit("パラメータ不正です");
}

