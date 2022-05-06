<?php
require_once('config.php');

function db_connect(){
    try{
    	$pdo = new PDO(DSN, DB_USER, DB_PASS, 
        	array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        		PDO::ATTR_EMULATE_PREPARES => false
        	)
        );
    } catch (Exception $e) {
    	exit('データベース接続に失敗しました。'.$e->getMessage().PHP_EOL);
    }
    
    return $pdo;
}
    
