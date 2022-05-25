<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>   


        
       
        
<?php
session_start();
define('_ROOT_DIR', __DIR__ . '/');
require_once _ROOT_DIR .'/config.php'; 
require_once _ROOT_DIR .'/db_connect.php';
require_once _ROOT_DIR .'/functions.php';
//<?=h($val['notes'])
$date = date('Y-m-d')."<br>";
$thisyear = date('Y'); //年
$thismonth = date('n'); //月
$thisday = date('j');
$today = mktime(0, 0 , 0, $thismonth, $thisday, $thisyear); //今日のタイムスタンプ
$prev = date('Y-m-d', strtotime('+2 day', $today)); 
echo $prev."<br>";
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
//設定時間を計算して配列化
$hours_baff = new DateTime($date . ' ' . $hours_st); //配列格納用の変数
$hours_end_date = new DateTime($date . ' ' . $hours_end); //終了時間を日付型へ
$hours = array(); //時間を格納する配列
array_push($hours, $hours_baff->format('H:i')); //配列に追加
$hours_baff = $hours_baff->modify("+{$hours_margin} minutes"); //設定間隔を足す
while ($hours_baff <= $hours_end_date) { //終了時間まで繰り返す
    if ($hours_baff->format('H:i') == '00:00') { //終了時間が00:00だったら
        array_push($hours, '24:00'); //24:00で配列に追加
    } else {
        array_push($hours, $hours_baff->format('H:i')); //配列に追加
    }
    $hours_baff = $hours_baff->modify("+{$hours_margin} minutes"); //設定間隔ずつ足していく
}



$bathId = search_bathId(1);
$results = select_all_data_facilityTimetable2($bathId, $date);

//echo $k;
//foreach($results as $row){   
//        echo $row['timeStart'];
//        
//    
//}
$week = array('日', '月', '火', '水', '木', '金', '土');
$w = date('w');
$x = date('w', strtotime('+1 day'));
$facility_today=date('n月d日');
$facility_tomorrow=date('n月d日', strtotime('+1 day'));        
echo date('n月d日', strtotime('+1 day')).$week[$w]."曜日";
echo "<pre>";
    print_r($results);
echo "</pre>";

echo "<br>";
echo $facility_tomorrow;
echo "<pre>";
    var_dump($results);
echo "</pre>";

//foreach($results as $val){
//  echo $val; // 順に1, 2, 3
//}
?>
 <div id="form_box">

                <form action="" name="iptfrm" method="post">
                    <input type="hidden" name="date" value="<?= h($date); ?>" />
                    <input type="hidden" name="userId" value="<?= h($_SESSION['USERID']); ?>" />
                    <input type="hidden" name="roomNumber" value="<?= h($_SESSION['ROOMNUMBER']); ?>" />
                    <br />
                    <label>予約表</label>
                    <br />
                    <label>日にち</label>
                    <form action="" method="post"><input type="submit" name="facility_today" value="<?= $facility_today ?>">
                    <input type="submit" name="facility_tomorrow" value="<?= $facility_tomorrow ?>"></form>
                    <label>設 備</label>
                        <?php
                        $facility = select_Name_facilityTimetable(search_facilityId($_SESSION['HOUSEID']));
                        foreach ($facility as $value){
                            if ($value === reset($facility)) {
                                echo '<label><input type="radio" name="facilityId" value="' . $value['facilityId'] . '"checked>' . $value['facilityName'].'</label>';
                            }else{
                                echo '<label><input type="radio" name="facilityId" value="' . $value['facilityId'] . '">' . $value['facilityName'].'</label>';
                            }
                        }
                        ?> 
                    
                    <br />
                    <label>開始時間</label>
                    <select name="timeStart" onChange="autoPlus(this)">
                        <?php
                        for ($i = 0; $i < count($hours) - 1; $i++) {
                            echo '	<option value="' . $hours[$i] . '">' . $hours[$i] . '</option>' . "\n";
                        }
                        ?>
                    </select>
                    <br />
                    <label>終了時間</label>
                    <select name="timeEnd">
                        <?php
                        for ($i = 1; $i < count($hours); $i++) {
                            echo '	<option value="' . $hours[$i] . '">' . $hours[$i] . '</option>' . "\n";
                        }
                        ?>
                    </select>
                    <br />
                    <label>備考</label>
                    <input type="text" name="notes" size="10" value="" />
                    <br />
                    <input type="submit" name="register" value="登録" />
                </form>
</div><!-- /#form_box -->
                <?php $sp_date = explode("-", $date); ?>
                <p><?php printf('%s年%s月%s日', $sp_date[0], $sp_date[1], $sp_date[2]); ?></p>
                <?php
                if(!empty($results)){       
                ?>
                <table border ="1">
                    <caption>浴 室</caption>
                    <tr>
                        <th>時間</th><th>部屋番号</th><th>備考</th><th>削除</th>
                    </tr>
                    <?php
                    foreach($results as $val){
                    ?>
                    <tr>
                        <td><?=substr(h($val['timeStart']),-8,5)."<br>&#x2240;<br>".substr(h($val['timeEnd']),-8,5)?></td>
                        <td><?=h($val['roomNumber'])."号室"?></td>
                        <?php if(!empty($val['notes'])){?><td><form action="" method="post"><input type="button" name="notedisplay" value="表示" onclick="noteDisplay('<?=h($val['notes'])?>')"></form><?php } else{ echo "<td></td>";}?>
                        <td><form action="" method="post" onsubmit="return dltChk()"><input type="hidden" name="date" value="' . $date . '" /><input type="hidden" name="id" value="' . $ar_id[$data_n] . '" /><input type="submit" name="delete_key_delete" value="×"></form></td>
                    </tr>
                <?php
                    } 
                }else{
                    echo "本日の浴室の予約はまだありません。"."<br>";
                }
                ?>
                </table> 
                <?php
                $a = search_facilityId(1);
                $c = select_Name_facilityTimetable([1, 2]);
                $b = select_all_data_facilityTimetable($a, $date);
                print_r($b);
      ?>          
    </body>
</html>
