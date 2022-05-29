<?php
session_start();
require_once __DIR__.'/config.php'; 
require_once 'Cache/Lite.php';
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

include (_ROOT_DIR .'header.php');
?>

<h2>準備中です。</h2>

<?php include (_ROOT_DIR .'footer.php');?>