<?php
session_start();
require_once('db_connect.php');
$pdo = db_connect();

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);
$id = h($_SESSION['ID']);

$username = h($_POST['username']);
$email = h($_POST['email']);
$password = h($_POST['password']);


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
  echo "<a href ='main_menu.php'>メインメニューに戻る</a><br>";
}

if($login_mail == ADMIN_MAIL) {
    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
}


if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}


try {
    $stmt = $pdo->prepare('select * from userData where id = ?');
    $stmt->bindValue(1, $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    exit($e->getMessage().PHP_EOL);
}

//更新処理
        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            print "updateです。";
            $id = $_SESSION['id'];
            
            //POSTのValidate。
            if (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
              echo '入力された値が不正です。<br>';
              echo "<a href='member_edit.php'>会員情報の編集に戻る</a><br>";
              return false;
            }
            //パスワードの正規表現
            if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $password)) {
              $password = password_hash($password, PASSWORD_DEFAULT);
            } else {
              echo "パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。<br>";
              echo "<a href='member_edit.php'>会員情報の編集に戻る</a><br>";
              return false;
              
            }

            try {
                
                $pdo->beginTransaction();
                $sql = "update userData set username = ?, email = ?, password = ?";

                $stmt = $pdo->prepare($sql);
                
                
                
                // 値の設定
                $stmt->bindValue(1, $username, PDO::PARAM_STR);
                $stmt->bindValue(2, $email, PDO::PARAM_STR);
                $stmt->bindValue(3, $password, PDO::PARAM_INT);

// 実行
                $stmt->execute();
                $pdo->commit();
                print "データを" . $stmt->rowCount() . "件、更新しました。<br>";
            } catch (Exception $ex) {
                $pdo->rollBack();
                print "エラー:" . $ex->getMessage();
            }
        }
            
?>
            
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>マイページ</title>
    </head>
    <body>       
        <hr>
        登録情報
        <hr>
        <form method ="post" action ="mypage_updateform.php">
            <table border ="1">
                <tbody>
                    <tr><th>ユーザー名</th><td><?= h($row['username'], ENT_QUOTES) ?></td><td><input type="submit" name="username_edit" value="編集" class="button"></td></tr>
                    <tr><th>ログインメールアドレス</th><td><?= h($row['email'], ENT_QUOTES) ?></td><td><input type="submit" name="email_edit" value="編集" class="button"></td></tr>
                    <tr><th>ログインパスワード</th><td><?= h($row['password'], ENT_QUOTES) ?></td><td><input type="submit" name="password_edit" value="編集" class="button"></td></tr>
                    <tr><th>ハウス名</th><td><?= h($row['housename'], ENT_QUOTES) ?></td><td></td></tr>
                    <tr><th>部屋番号</th><td><?= h($row['roomnumber'], ENT_QUOTES) ?></td><td></td></tr>
                    <tr><th>部屋のパスキー</th><td><?= h($row['roompass'], ENT_QUOTES) ?></td><td></td></tr>
                </tbody>
            </table>
        </form>
        <p>ハウス名、部屋番号、部屋のパスキーは編集不可のため、部屋を移動する際は、退会後、新規登録をお願いいたします。</p>
        <p><a href ='main_menu.php'>退会する</a><br></p>
    </body>
</html>
