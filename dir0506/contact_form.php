<?php
session_start();
require_once('db_connect.php');
$pdo = db_connect();

$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);

if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
echo "<a href='main_menu.php'>メインメニューに戻る</a><br>";
}

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if( isset($_POST['cancel']) && $_POST['cancel'] ){
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $message = h($_POST["message"]);

      
    ?>
    <form action="contact_form.php" method="post">
    名前<br><input type="text" name="name" size="40" value="<?= $name ?>"><br>
    Eメール <br><input type="email" name="email" size="40" value="<?= $email ?>"><br>
    <input type="hidden" name="homename" value="<?php echo h($homename); ?>">
    本文<br><textarea name="message" id="" rows="6" cols="40"><?= $message ?></textarea><br>
    <input type="submit" name="confirm" value="確認" class="button"><br>
    </form>
    <?php
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $message = h($_POST["message"]);
    
    echo "こちらの内容で送信してよろしいですか？";

      
    ?>
    <form action="contact_form.php" method="post">
    名前<br><input type="text" name="name" size="40" value="<?= $name ?>" readonly><br>
    Eメール <br><input type="email" name="email" size="40" value="<?= $email ?>" readonly><br>
    <input type="hidden" name="homename" value="<?php echo h($homename); ?>" readonly>
    本文<br><textarea name="message" id="" rows="6" cols="40" readonly><?= $message ?></textarea><br>
    <input type="submit" name="send" value="送信" class="button"><br>
    <input type="submit" name="cancel" value="編集画面に戻る" class="button"><br>
    </form>
    <?php
} else if( isset($_POST['send']) && $_POST['send'] ){
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $message = h($_POST["message"]);
    
    //メール本文の作成
	$honbun = '';
	$honbun .= "メールフォームよりお問い合わせがありました。\n\n";
	$honbun .= "【お名前】\n";
	$honbun .= $name."\n\n";
	$honbun .= "【メールアドレス】\n";
	$honbun .= $email."\n\n";
	$honbun .= "【お問い合わせ内容】\n";
	$honbun .= $message."\n\n";
	
	//エンコード処理
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	
		//メールの作成
	$mail_to = "sasaki-ayana@mgt-net.com";			//送信先メールアドレス
	$mail_subject = "メールフォームよりお問い合わせ";	//メールの件名
	$mail_body	= $honbun;				//メールの本文
	$mail_header = "from:".$email;			//送信元として表示されるメールアドレス
	
	//メール送信処理
	$mailsousin	= mb_send_mail($mail_to, $mail_subject, $mail_body, $mail_header);
	mail("sasaki-ayana@mgt-net.com", "お問い合わせありがとうございます", $honbun );
	
	//メール送信結果
	if($mailsousin == true) {
		echo '<p>お問い合わせメールを送信しました。</p>';
	} else {
		echo '<p>メール送信でエラーが発生しました。</p>';
	}



    
      
}else{
    ?>
     <form action="contact_form.php" method="post">
    名前<br><input type="text" name="name" size="40" value=""><br>
    Eメール <br><input type="email" name="email" size="40" value=""><br>
    <input type="hidden" name="homename" value="<?php echo h($homename); ?>">
    本文<br><textarea name="message" id="" rows="6" cols="40"></textarea><br>
    <input type="submit" name="confirm" value="確認" class="button"><br>
    </form>
  <?php  
}
?>

