<?php
require_once('config.php');
require_once('db_connect.php');
$pdo = db_connect();


//データベースへ接続、テーブルがない場合は作成
try {
  $pdo->exec("create table if not exists userData(
      id int not null auto_increment primary key,
      username varchar(255) unique,
      email varchar(255) unique,
      password varchar(255) ,
      created timestamp not null default current_timestamp
    )"); 
} catch (Exception $e) {
    exit('userDataテーブルの作成に失敗しました。'.$e->getMessage().PHP_EOL);
}



//htmlエスケープ
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


$username = h($_POST['username']);
$email = h($_POST['email']);
$password = h($_POST['password']);
$housename = h($_POST['housename']);
$roomnumber = h($_POST['roomnumber']);
$roompass = h($_POST['roompass']);


try {
    $stmt = $pdo->prepare('select * from houseData where housename = ? and roomnumber = ?');
    $stmt->bindValue(1, $housename, PDO::PARAM_STR);
    $stmt->bindValue(2, $roomnumber, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    exit($e->getMessage().PHP_EOL);
}



if ($row['roompass'] != $roompass) {
    echo '登録できませんでした。';
    echo "<br><a href='index.php'>もう一度やりなおしてください。</a>";
    return false;
}


//POSTのValidate。
if (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '登録できませんでした。';
    echo "<br><a href='index.php'>もう一度やりなおしてください。</a>";
    return false;
}

//パスワードの正規表現
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $password)) {
  $password = password_hash($password, PASSWORD_DEFAULT);
} else {
    echo '登録できませんでした。';
    echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
    echo "<br><a href='index.php'>もう一度やりなおしてください。</a>";
    return false;
}

$unique_sign = $housename.$roomnumber;

try {
    $stmt = $pdo->prepare('select * from userData where unique_sign = ?');
    $stmt->bindValue(1, $unique_sign, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row == true ){
			echo '登録できませんでした。';
            echo "<br><a href='index.php'>もう一度やりなおしてください。</a>";
            return false;
    }
} catch (\Exception $e) {
    exit($e->getMessage().PHP_EOL);
}


//登録処理
try {
  $stmt = $pdo->prepare("insert into userData(username, email, password, housename, roomnumber, roompass, unique_sign) value(?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([$username, $email, $password, $housename, $roomnumber, $roompass, $unique_sign]);
  echo '登録完了';
  echo "<br><a href='index.php'>ログインページに戻る</a>";
} catch (\Exception $e) {
  echo '登録できませんでした。';
  echo "<br><a href='index.php'>もう一度やりなおしてください。</a>";
  
}

