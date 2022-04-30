<?php session_start(); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>list.php</title>
    </head>
    <body>       
        <hr>
        会員名簿一覧
        <hr>
        [<a href="form1.html">新規作成</a>]
        <br>
        <form action="list.php" method="POST">
            <input type="text" name="search_key" value="" />
            <input type="submit" value="検索する" />
        </form>

        <?php
        print "list.phpです。";
        require_once 'mydb.php';
        $pdo = db_connect();

//削除処理
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0) {

            print "deleteです。";
            try {
                $pdo->beginTransaction();
                $id = $_GET['id'];
                $sql = "delete from member where id=:id";
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

            try {
                $pdo->beginTransaction();
                $sql = "insert into member(last_name,first_name,age) values(:last_name,:first_name,:age)";
// SQLテンプレートの解釈
                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);

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

            try {
                
                $pdo->beginTransaction();
                $sql = "update member set last_name = :last_name, first_name = :first_name, age = :age where id = :id";

                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                $stmh->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                $stmh->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
                $stmh->bindValue(':id', $id, PDO::PARAM_INT);

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
                $sql = "select * from member where last_name like :last_name or first_name like :first_name";

// SQLテンプレートの解釈
                $stmh = $pdo->prepare($sql);
// 値の設定
                $stmh->bindValue(':last_name', $search_key, PDO::PARAM_STR);
                $stmh->bindValue(':first_name', $search_key, PDO::PARAM_STR);

// 実行
                $stmh->execute();
            } else {

//          //検索キーがなかった時の処理
                $sql = "select * from member";
                $stmh = $pdo->query($sql);
            }

            $count = $stmh->rowCount();
            print "検索結果は" . $count . "件です。<br>";
        } catch (Exception $ex) {
            print "エラー:" . $ex->getMessage();
        }

        if ($count < 1) {
            print "検索結果がありません。<br>";
        } else {
            ?>

            <table border ="1">
                <tr>
                    <th>番号</th><th>氏</th><th>名</th><th>年齢</th>

                </tr>

                <?php
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <td><?= htmlspecialchars($row['id'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($row['last_name'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($row['first_name'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($row['age'], ENT_QUOTES) ?></td>
        <!--                    <td><?= htmlspecialchars($row['id'], ENT_QUOTES) ?></td>-->
                    <td><a href="updateform.php?id=<?= htmlspecialchars($row['id'], ENT_QUOTES) ?>">更新</a></td>
                    <td><a href="list.php?action=delete&id=<?= htmlspecialchars($row['id'], ENT_QUOTES) ?>">削除</a></td>
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
