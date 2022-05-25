<?php
session_start();
require_once __DIR__ .'/config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';
require_once _ROOT_DIR.'/vendor/autoload.php';

$login_name = $_SESSION['NAME'];
$login_mail = $_SESSION['EMAIL'];






// POSTされた二重送信防止用トークンを取得
$token = isset($_POST["token"]) ? $_POST["token"] : "";
// セッション変数のトークンを取得
$session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";
// セッション変数のトークンを削除
unset($_SESSION["token"]);

include (_ROOT_DIR .'header.php');

if( isset($_POST['cancel']) && $_POST['cancel'] && $token != "" && $token == $session_token){
    // 二重送信防止用トークンの発行
    $token = uniqid('', true);
    //トークンをセッション変数にセット
    $_SESSION['token'] = $token;
    
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $postmessage = h($_POST["postmessage"]);

      
    ?>
    <form action="contact_form.php" method="post">
    <input type="hidden" name="token" value="<?= $token ?>">
    名前<br><input type="text" name="name" size="40" value="<?= $name ?>"><br>
    Eメール <br><input type="email" name="email" size="40" value="<?= $email ?>"><br>
    本文<br><textarea name="postmessage" id="" rows="6" cols="40"><?= $postmessage ?></textarea><br>
    <input type="submit" name="confirm" value="確認" class="button"><br>
    </form>
    <?php
} else if( isset($_POST['confirm']) && $_POST['confirm'] && $token != "" && $token == $session_token){
    // 二重送信防止用トークンの発行
    $token = uniqid('', true);
    //トークンをセッション変数にセット
    $_SESSION['token'] = $token;
    
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $postmessage = h($_POST["postmessage"]);
    
    echo "こちらの内容で送信してよろしいですか？";

      
    ?>
    <form action="contact_form.php" method="post">
    <input type="hidden" name="token" value="<?= $token ?>">
    名前<br><input type="text" name="name" size="40" value="<?= $name ?>" readonly><br>
    Eメール <br><input type="email" name="email" size="40" value="<?= $email ?>" readonly><br>
    本文<br><textarea name="postmessage" id="" rows="6" cols="40" readonly><?= $postmessage ?></textarea><br>
    <input type="submit" name="send" value="送信" class="button"><br>
    <input type="submit" name="cancel" value="編集画面に戻る" class="button"><br>
    </form>
    <?php
} else if( isset($_POST['send']) && $_POST['send'] && $token != "" && $token == $session_token){
    // 二重送信防止用トークンの発行
    $token = uniqid('', true);
    //トークンをセッション変数にセット
    $_SESSION['token'] = $token;
    
    $name = h($_POST["name"]);
    $email = h($_POST["email"]);
    $postmessage = h($_POST["postmessage"]);
    $roomNumber = h($_SESSION['ROOMNUMBER']);
    
    $houseName = seach_houseName($_SESSION['HOUSEID']);
    $houseName = array_shift($houseName);
    
    $to = $_POST['email'];
    $subject = "お問い合わせフォームより／" . $houseName ."／". $roomNumber . "号室";
//    $mes = "テストです。";
    $mes =<<<EOM
ハウス名：{$houseName}
部屋番号：{$roomNumber}号室
名前：{$name}
--

{$postmessage}


--
お問い合わせフォームより送信されました。
＊株式会社シェアハウス運営(仮)＊


EOM;
    //改行を反映
    $mes = nl2br($mes);

    //メール送信
    $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
    $transport->setUsername('sasaki.test.mail.stm@gmail.com');
    $transport->setPassword('teststm1221');

    //HTML 形式のメール本文
    $html = $mes;
    // テキスト形式のメール本文
    $text = $mes;
    $message = new Swift_Message();
    $message->setSubject($subject);
    $message->setFrom(['sasaki.test.mail.stm@gmail.com' => '株式会社シェアハウス運営(仮)']);
    $message->setTo([$to]);
    // メール本文に HTML パートをセット
    $message->setBody($html, 'text/html');
    // メール本文にテキストパートをセット
    $message->addPart($text, 'text/plain');
    $mailer = new Swift_Mailer($transport);
    $mailsend = $mailer->send($message);

    //メール送信結果
    if($mailsend == true) {
            $mes = '<p>メールを送信しました。</p><p>お問い合わせありがとうございます。</p>';
            $mes .= '<p><a href="'.MAINMENU.'">メインメニュー</a>に戻る</p>';
            echo $mes;
            exit;
    } else {
            $mes =  '<p>メール送信でエラーが発生しました。</p>';
            $mes .= '<p><a href="'.MAINMENU.'">メインメニュー</a>に戻る</p>';
            echo $mes;
            exit;
    }
        
}else{
    // 二重送信防止用トークンの発行
    $token = uniqid('', true);
    //トークンをセッション変数にセット
    $_SESSION['token'] = $token;
    ?>
    <form action="contact_form.php" method="post">
    <input type="hidden" name="token" value="<?= $token ?>">
    名前<br><input type="text" name="name" size="40" value="<?=h($login_name)?>"><br>
    Eメール <br><input type="email" name="email" size="40" value="<?=h($login_mail)?>"><br>
    本文<br><textarea name="postmessage" id="" rows="6" cols="40"></textarea><br>
    <input type="submit" name="confirm" value="確認" class="button"><br>
    </form>
  <?php  
}
?>

<?php include (_ROOT_DIR .'/footer.php');?>