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
}


if( isset($_POST['cancel']) && $_POST['cancel'] ){
    header("Location: https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/main_menu.php");
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      
      $name = h($_POST["name"]);
      $email = h($_POST["email"]);
      $message = h($_POST["message"]);
      ?>
      <form action="contact_confirmation.php" method="post">
        名前<br><input type="text" name="name" size="40"　value="$name"><br>
        Eメール <br><input type="email" name="email" size="40"　value="$email"><br>
        <input type="hidden" name="housename" value="<?= h($housename); ?>">
        本文<br><textarea name="message" id="" rows="6" cols="40" value"$message"></textarea><br>
        <input type="submit" name="send" value="送信" class="button"><br>
        <input type="submit" name="cancel" value="キャンセル" class="button"><br>
      </form>
      <?php
      
}else{
      header("Location: https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/contact_form.php");
    
}
  
  
