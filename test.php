    $userdata = array();
    $sql = 'select username, email, password, id from userData';
    $sql .= 'where email = ?';
    $stmt = $pdo->prepare($sql);
//  $stmt->bindValue(1,$login_mail,PDO::PARAM_STR);
//  $stmt->bindValue(2,$login_crypt_pass,PDO::PARAM_STR);
    $stmt->execute([$login_mail]);
    $userdata = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($row);
    print $login_pass;
    print_r($userdata);
    
//  $_SESSION['NAME'] = $userdata[0]['username'];
//  $_SESSION['ID'] = $userdata[0]['id'];




                <?php
        
        		$stmt = $pdo->prepare( 'DELETE FROM sharehouse_timetable WHERE id = :id' );
    	    	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
    	    	$rsl = $stmt->execute(); //実行
        		if ( $rsl == false ){
                    $alart_msg = "削除に失敗しました。";
        	        echo javascript_alart($alart_msg);
    
        		} else {
        		    $alart_msg = "削除しました。";
        	        echo javascript_alart($alart_msg);
            
        	    }
        	    ?>　
        	    
        	    
        	    
        	    
        	    //ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['PASS']);
$login_mail = h($_SESSION['EMAIL']);


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
}

if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}



<script>

    var result = confirm('ログインしますか？');
     
    if( result ) {
     
        document.write('ok');
     
    }

</script>
