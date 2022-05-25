<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

//$items = ['name' => 'cat','ad' => 'dog'];
//
//foreach  ($items as $key => $val){
//   echo $key . 'は' . $val . 'です。<br>';
//}



$apes = ['name' => 'cat','ad' => 'dog'];
// 配列のすべてのキーと値を、変数$keyと$valueに代入し出力する場合
foreach( $apes as $key => $value ){
    ?>
    <input type="hidden" name="<?= $key?>" value="<?= $value ?>">
<?php        
}

//<input type="hidden" name="token" value="<?= $token ?>">

