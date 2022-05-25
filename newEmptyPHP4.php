<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$apes = ['name' => 'cat','ad' => 'dog'];
// 配列のすべてのキーと値を、変数$keyと$valueに代入し出力する場合
foreach( $apes as $key => $value ){
    
    
    
}

?>

    <form action="mypage.php" method="POST">
        番号:<?= h($row['userId']) ?><br>
        username:<br>
        <input type ="text" name="username" value="<?= h($row['userName'], ENT_QUOTES) ?>"<br>
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