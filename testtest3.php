<?php
session_start();
define('_ROOT_DIR', __DIR__ . '/');
require_once _ROOT_DIR .'/config.php'; 
require_once _ROOT_DIR .'/db_connect.php';
require_once _ROOT_DIR .'/functions.php';
//<?=h($val['notes'])
$date = date('Y-m-d');
/* * ******** 手動設定 ********* */
$hours_st = '00:00'; //設定開始時間('hh:nn'で指定)
$hours_end = '24:00'; //設定終了時間('hh:nn'で指定)
$hours_margin = 30; //間隔を指定(分)
$facility = select_Name_facilityTimetable(search_facilityId($_SESSION['HOUSEID']));
foreach ($facility as $value) {
    $chapters[] = $value['facilityId'];  
}
$tbl_flg = false; //時間を横軸 → true, 縦軸 → falseにする
/* * ******** ここまで ********* */




/* * * DB接続 ** */
$pdo = db_connect();

$id = (int) filter_input(INPUT_POST, 'id');

/* * * ページ読込前の設定部分 ** */
//エラー出力する
ini_set('display_errors', 1);
//タイムゾーンセットv
date_default_timezone_set('Asia/Tokyo');
//本日を取得
$date = date('Y-m-d'); //YYYY-MM-DDの形


$a = search_facilityId(1);
$c = select_Name_facilityTimetable([1, 2]);
$b = select_all_data_facilityTimetable3($a, $date);
echo "<pre>";
print_r($b);
echo "</pre><br>";

echo "<pre>";
foreach ($b as $y){
    print_r($y);
}
echo "</pre>";

foreach ($b as $y){
    print $y['facilityName'];
}



$html =  make_facilityTimetable_html($b, $date);
 


?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>予約フォーム</div>
        <?=$html[0].$html[1]?>
    </body>
</html>
