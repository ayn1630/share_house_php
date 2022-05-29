<?php
session_start();
require_once 'Cache/Lite.php';
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$login_mail = h($_SESSION['EMAIL']);
$userId = $_SESSION['USERID'];



include (_ROOT_DIR . 'header.php');

if($login_mail == ADMIN_MAIL) {
    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
    exit;
}

try 
{
    $pdo = db_connect();
    $stmt = $pdo->prepare('select * from user where userId = ?');
    $stmt->bindValue(1, $userId, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit($e->getMessage().PHP_EOL);
}

//更新処理
        if (isset($_POST['userName']) && $_POST['regist'] == '　確定　') {
            $userName = $_POST['userName'];
//            $email = h($_POST['email']);
//            $password = h($_POST['password']);
            
            //POSTのValidate。
//            if (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
//              echo '入力された値が不正です。<br>';
//              echo "<a href='member_edit.php'>会員情報の編集に戻る</a><br>";
//              return false;
//            }
            //パスワードの正規表現
//            if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $password)) {
//              $password = password_hash($password, PASSWORD_DEFAULT);
//            } else {
//              echo "パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。<br>";
//              echo "<a href='member_edit.php'>会員情報の編集に戻る</a><br>";
//              return false;
//              
//            }
            if (mb_strlen($userName) >= 15 ){
                echo "文字数は15文字以内で入力してください。<br>";
                exit("<a href='".MYPAGE."'>マイページに戻る</a><br>");
            }
            $text = str_replace(["\r\n", "\r", "\n"], '', $userName);
            $text = preg_replace('/^\r\n/m', '', $text);
            $text = mb_convert_kana($text, "s", 'UTF-8');
            $text = trim($text);
            if (empty($text)){
                echo "空欄では登録できません。<br>";
                exit("<a href='".MYPAGE."'>マイページに戻る</a><br>");
            }

            try {
                
                $pdo->beginTransaction();
                $sql = "update user set userName = ? where userId = ?";

                $stmt = $pdo->prepare($sql);
                
                
                
                // 値の設定
                $stmt->bindValue(1, $userName, PDO::PARAM_STR);
                $stmt->bindValue(2, $_SESSION['USERID'], PDO::PARAM_STR);
//                $stmt->bindValue(3, $password, PDO::PARAM_INT);

// 実行
                $stmt->execute();
                $pdo->commit();
                $_SESSION['NAME'] = $userName;
                echo "データを" . $stmt->rowCount() . "件、更新しました。<br>";
                exit("<a href='".MYPAGE."'>マイページに戻る</a><br>");
            } catch (Exception $ex) {
                $pdo->rollBack();
                echo "エラー:" . $ex->getMessage();
            }
        }elseif (isset($_POST['userName']) && $_POST['cancel'] == '　キャンセル　')  {
            echo "キャンセルしました。<br>";
            exit("<a href='".MYPAGE."'>マイページに戻る</a><br>");
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
                    <tr><th>ユーザー名</th><td><?= h($row['userName'], ENT_QUOTES) ?></td><td><button type="submit"  name="userName_edit" value="<?= h($row['userName'], ENT_QUOTES) ?>" >変更する</button></td></tr>
                    <tr><th>ログインメールアドレス</th><td><?= h($row['email'], ENT_QUOTES) ?></td><td><button type="submit"  name="email_edit" value="<?= h($row['email'], ENT_QUOTES) ?>" >変更する</button></td></tr>
                    <tr><th>ログインパスワード</th><td>***非表示***</td><td><button type="submit"  name="password_edit" value="<?= h($row['password'], ENT_QUOTES) ?>" >変更する</button></td></tr>
                </tbody>
            </table>
        </form>
        <p>部屋を移動する際は、退会後、新規登録をお願いいたします。</p>
        <p><a href="<?=LEAVING?>">退会する</a><br></p>
    </body>
</html>

<?php include (_ROOT_DIR . 'footer.php'); ?>

