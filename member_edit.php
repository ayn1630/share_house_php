<?php
session_start();
require_once('db_connect.php');
$pdo = db_connect();

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);


$username = h($_POST['username']);
$email = h($_POST['email']);
$password = h($_POST['password']);


if(!$login_mail == ADMIN_MAIL) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
  echo "<a href='admin0.php'>戻る</a><br>";
}


?>



        <?php
        print "member_edit.phpです。";


//削除処理
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0) {

            print "deleteです。";
            try {
                $pdo->beginTransaction();
                $id = $_GET['id'];
                $sql = "delete from userData where id=:id";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':id', $id, PDO::PARAM_INT);
                $stmh->execute();
                $pdo->commit();
//        $pdo->rollback();

                print "データを" . $stmh->rowCount() . "件、削除しました。";
            } catch (Exception $ex) {
                $pdo->rollBack();
                print "エラー:" . $ex->getMessage();
                print $ex->getTraceAsString();
            }
        }



//挿入処理
        if (isset($_POST['action']) && $_POST['action'] == 'insert') {
            print "insertです。";
            
            if (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
              echo '入力された値が不正です。<br>';
              echo "<a href='member_edit.php'>戻る</a><br>";
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
                $sql = "insert into userData(username,email,password) values(?,?,?)";
// SQLテンプレートの解釈
                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(1, $username, PDO::PARAM_STR);
                $stmh->bindValue(2, $email, PDO::PARAM_STR);
                $stmh->bindValue(3, $password, PDO::PARAM_INT);

// 実行
                $stmh->execute();
                $pdo->commit();
                print "データを" . $stmh->rowCount() . "件、挿入しました。<br>";
            } catch (Exception $ex) {
                $pdo->rollback();
                print "エラー:" . $ex->getMessage();
            }
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
                $sql = "update userData set username = ?, email = ?, password = ? where id = ?";

                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(1, $username, PDO::PARAM_STR);
                $stmh->bindValue(2, $email, PDO::PARAM_STR);
                $stmh->bindValue(3, $password, PDO::PARAM_INT);
                $stmh->bindValue(4, $id, PDO::PARAM_INT);

// 実行
                $stmh->execute();
                $pdo->commit();
                print "データを" . $stmh->rowCount() . "件、更新しました。<br>";
            } catch (Exception $ex) {
                $pdo->rollBack();
                print "エラー:" . $ex->getMessage();
            }


//            $_SESSION = array();
//
//            session_destroy();
        }



        try {
            if (isset($_POST['search_key']) && $_POST['search_key'] != '') {
                print "search_key検索です。";

                $search_key = '%' . $_POST['search_key'] . '%';
                $sql = "select * from userData where username like ? or email like ?";

// SQLテンプレートの解釈
                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(1, $search_key, PDO::PARAM_STR);
                $stmh->bindValue(2, $search_key, PDO::PARAM_STR);

// 実行
                $stmh->execute();
                

            } else {

//          //検索キーがなかった時の処理
                $sql = "select * from userData";
                $stmh = $pdo->query($sql);
            }
            
//           よくわからないところ。後で直す。
            $count = $stmh->rowCount();
            print "検索結果は" . $count . "件です。<br>";

                
        } catch (Exception $ex) {
            print "エラー:" . $ex->getMessage();
        }

        if ($count < 1) {
            print "検索結果がありません。<br>";
        } else {
            ?>
            <html>
    <head>
        <meta charset="UTF-8">
        <title>list.php</title>
    </head>
    <body>       
        <hr>
        会員名簿一覧
        <hr>
        [<a href="create_member.php">新規作成</a>]
        <br>
        <form action="member_edit.php" method="POST">
            <input type="text" name="search_key" value="" />
            <input type="submit" value="検索する" />
        </form>

            <table border ="1">
                <tr>
                    <th>番号</th><th>username</th></th><th>email</th></th><th>password</th>

                </tr>

                <?php
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <td><?= h($row['id'], ENT_QUOTES) ?></td>
                    <td><?= h($row['username'], ENT_QUOTES) ?></td>
                    <td><?= h($row['email'], ENT_QUOTES) ?></td>
                    <td><?= h($row['password'], ENT_QUOTES) ?></td>
        <!--                    <td><?= h($row['id'], ENT_QUOTES) ?></td>-->
                    <td><a href="updateform.php?id=<?= h($row['id'], ENT_QUOTES) ?>">更新</a></td>
                    <td><a href="member_edit.php?action=delete&id=<?= h($row['id'], ENT_QUOTES) ?>">削除</a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
</body>
</html>
