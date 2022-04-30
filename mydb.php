<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


function db_connect(){
    $db_user ="sample";
    $db_pass = "";
    $db_host = "localhost";
    $db_name = "sampledb1";
    $db_type = "mysql";
    

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=sampledb1;charset=utf8', 'root', '');

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        print 'pdoクラスによる接続に成功しました。<br>';
    } catch (Exception $exc) {
//    echo $exc->getTraceAsString();
        print "接続失敗したヨ:" . $exc->getMessage();
    }

    return $pdo;
}

