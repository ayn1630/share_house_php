<?php
session_start();
define('_ROOT_DIR', __DIR__ . '/');
require_once 'Cache/Lite.php';
require_once _ROOT_DIR .'config.php'; 
require_once _ROOT_DIR .'db_connect.php';
require_once _ROOT_DIR .'functions.php';

//if (isset($login_mail)) {
//    echo "ようこそ" . $login_name . "さん<br>";
//    echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
//}
//
//if ($login_mail == ADMIN_MAIL) {
//    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
//}


//if (!$login_mail > 0) {
//    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
//}
$log1 = "";
$log2 = "";

//タイムゾーンセット
date_default_timezone_set('Asia/Tokyo');
//本日を取得
$date = date('Y-m-d'); //YYYY-MM-DDの形
//echo $date."<br>";
//
//$tpl = <<<'EOS'
//'$date +1day'         
//EOS;
//eval("\$format = \"$tpl\";");
//print $format."<br>";
////echo h($date = date('Y-m-d', strtotime("'".$date. " +1 day '")))."<br>";
//echo h($date = date('Y-m-d', strtotime("$date +1day")))."<br>";
//echo gettype($date);


//$date = $_POST['date_tomorrow'];
//echo $_POST['tomorrow_next']."<br>";

/* * ******** 手動設定 ********* */
$hours_st = date('H').":00";
if (isset($_POST['calendar'])) {
    $date = !is_string(key($_POST['calendar'])) ? '' : key($_POST['calendar']);
}
if (isset($_POST['calendar']) && $date > date('Y-m-d')){
    $hours_st = '00:00';
}
  
//設定開始時間('hh:nn'で指定)
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

$hours_baff = new DateTime($date . ' ' . $hours_st); //配列格納用の変数
$hours_end_date = new DateTime($date . ' ' . $hours_end); //終了時間を日付型へ
$hours = array(); //時間を格納する配列
array_push($hours, $hours_baff->format('H:i')); //配列に追加
$hours_baff = $hours_baff->modify("+{$hours_margin} minutes"); //設定間隔を足す
while ($hours_baff <= $hours_end_date) { //終了時間まで繰り返す
    if ($hours_baff->format('H:i') == '00:00') { //終了時間が00:00だったら
        array_push($hours, '23:59'); //24:00で配列に追加
    } else {
        array_push($hours, $hours_baff->format('H:i')); //配列に追加
    }
    $hours_baff = $hours_baff->modify("+{$hours_margin} minutes"); //設定間隔ずつ足していく
}


//タイムテーブル設定
if ($tbl_flg == true) {
    $clm = $hours; //縦軸 → 時間
    $row = $chapters; //横軸 → 設定項目
    $clm_n = count($clm) - 1; //縦の数（時間配列の-1）
    $row_n = count($row); //横の数
} else {
    $clm = $chapters; //縦軸 → 設定項目
    $row = $hours; //横軸 → 時間
    $clm_n = count($clm); //縦の数
    $row_n = count($row) - 1; //横の数（時間配列の-1）
}

/* * * 各種ボタンが押された時の処理 ** */
if (isset($_POST['calendar'])) {
    /*     * * カレンダーがクリックされた場合 ** */
    $date = !is_string(key($_POST['calendar'])) ? '' : key($_POST['calendar']);
    
//} elseif (isset($_POST['facility_today'])) {
    /*     * * カレンダーがクリックされた場合 ** */
    
}elseif (isset($_POST['rsv_next'])){
    $date = get_rsv_date($date);
    $date = $date[2];
    $sp_date = explode("-", $date);
    $this_day = mktime(0, 0, 0, $sp_date[1], $sp_date[2], $sp_date[0]);
    $date = date('Y-m-d', strtotime('+0 day', $this_day));
    
} elseif (isset($_POST['reserveDate'])) {
//    $date = date('Y-m-d', strtotime('+1 day'));
//    $date = $_POST['date_tomorrow'];
    echo $date = $_POST['reserveDate'];
//    $date = date('Y-m-d', strtotime("$date +1day"));

//    echo $date."*1<br>";
    
    
} elseif (isset($_POST['yesterday'])) {    
    $date = $_POST['yesterday'];
//    echo $date."*2<br>";
    
    
}elseif (isset($_POST['date_tomorrow_next'])) {
    $date = $_POST['tomorrow_next'];
   
 
} elseif (isset($_POST['register'])) {
    /*     * * 登録ボタンがクリックされた場合 ** */
    //フォームに入力された情報を各変数へ格納
    foreach (array('date', 'notes', 'timeStart', 'timeEnd', 'facilityId') as $v) {
        $$v = (string) filter_input(INPUT_POST, $v);
    }
    $timeStart = $date . ' ' . $timeStart . ':00'; //開始時間（MySQLのDATETIMEフォーマットへ成形）
    $timeEnd = $date . ' ' . $timeEnd . ':00'; //終了時間
    $today = new DateTime('now');
    $today = $today->format('Y-m-d H:i:s');
    if ($timeStart >= $timeEnd) { //開始時間 >= 終了時間の場合
        $alart_msg = "時間設定が不正のため、登録できませんでした。";
        echo javascript_alart($alart_msg);
    }elseif($timeEnd < $today){
        $alart_msg = "時間が過ぎているため、登録できませんでした。";
        echo javascript_alart($alart_msg);
//		$log1 = '<p>時間設定が不正のため、登録できませんでした。</p>';
    } else { //正常処理
        $sbm_flg = false;//予約済み時間との重複フラグを設定
        $sbm_flg2 = false;
        $results = confirm_reserve_facilityTimetable(search_facilityId($_SESSION['HOUSEID']));
        if ($results) {
            foreach ($results as $value) { //該当のデータ数繰り返す
                $time1 = strtotime($value['timeStart']); //該当IDの開始時刻
                $time2 = strtotime($value['timeEnd']);
                $roomNumber = $value['roomNumber'];//該当IDの終了時刻
                if ($time1 <= strtotime($timeStart) && strtotime($timeStart) < $time2) {
                    $sbm_flg = true; //予約済開始時刻 <= 開始時刻 < 予約済終了時刻 ならフラグを立てる
                }
                if ($time1 < strtotime($timeEnd) && strtotime($timeEnd) <= $time2) {
                    $sbm_flg = true; //予約済開始時刻 < 終了時刻 <= 予約済終了時刻 ならフラグを立てる
                }
                if (strtotime($timeStart) <= $time1 && $time2 <= strtotime($timeEnd)) {
                    $sbm_flg = true; //開始時刻 <= 予約済開始時刻 & 予約済終了時刻 <= 終了時刻 ならフラグを立てる
                }
                if($_SESSION['ROOMNUMBER'] == $roomNumber){
                    $sbm_flg2 = true;
                    
                }

            }
        }
        if ($sbm_flg == true) { //フラグが立ってたら登録できない
            $alart_msg = "既に予約されているため、この時間帯では登録できません。";
            echo javascript_alart($alart_msg);
        }elseif($sbm_flg2 == true){
	    $alart_msg = "既に予約済みです。キャンセルするか、予約時間が過ぎてからご登録ください。";
            echo javascript_alart($alart_msg);
        } else {
            //登録処理
            regist_facilityTimetable($_SESSION['USERID'], $_SESSION['ROOMNUMBER']);   
        }
    }

/////////////////////////////////////////////////////////////////////////////////
} elseif (isset($_POST['delete_key_delete'])) {
    delete_facilityTimetable($_SESSION['USERID'], $_SESSION['EMAIL'], $date = (string) filter_input(INPUT_POST, 'date'), $id = (int) filter_input(INPUT_POST, 'id'));
}


$facility_today=date('n月d日');
$facility_tomorrow=date('n月d日', strtotime('+1 day'));

$a = search_facilityId($_SESSION['HOUSEID']);
$b = select_all_data_facilityTimetable3($a, $date);

$html =  make_facilityTimetable_html($b, $date);

$option = [
    'cacheDir'=>'./cache/',
    'lifeTime'=>'15'
];
 
// キャッシュディレクトリがない場合は
if(is_dir("cache") == false) {
    // 作る
    mkdir("cache");
}
 
 
// 東京電力電力使用量
$url = "http://tepco-usage-api.appspot.com/latest.json";
 



// APIURL
$weatherURL = "https://www.jma.go.jp/bosai/forecast/data/forecast/130000.json";

// URLをキャッシュ判別用のIDとしても使う
$cacheID = $weatherURL;
 
// CacheLiteクラスを使う
$cacheLite = new Cache_Lite($option);
 
// キャッシュがある？
if( ($cache = $cacheLite->get($cacheID)) )  {
    // あるのでキャッシュを使う
    $json = $cache;
} else {
    // ないので取得する
    $json = file_get_contents($weatherURL);
    
    // キャッシュする
    $cacheLite->save($json);
} 

// JSON解析
$obj = json_decode($json);
 
// 地域の名前
$area_name = $obj[0]->timeSeries[0]->areas[0]->area->name;

$area_name = mb_substr($area_name, 0, 2);
// お天気の日時取得
$time_array = $obj[0]->timeSeries[0]->timeDefines;

//print_r($time_array);
// お天気取得
$weather_array = $obj[0]->timeSeries[0]->areas[0]->weathers;

$time_and_weather_array = array_combine($time_array, $weather_array);

$weather_html =  make_weathertable_html($time_and_weather_array);

include (_ROOT_DIR .'header.php');

?>




            <!-- タイムテーブル -->
            <div id="title_day">
                <?php
                $get_y = date('Y'); //本日の年
                $get_m = date('n'); //本日の月
                $title_date = get_rsv_date($date);
                echo $title_date[0];

                
                
                if ($date > date('Y-m-d'))
                {
                ?>
                <form style="display: inline" action="" method="post" name="date_yesterday"><input type="hidden" name="yesterday" value="<?=h($date = date('Y-m-d', strtotime("$date -1day")))?>" /><a href="javascript:date_yesterday.submit()">ああ</a></form>
                <?php
                }
                ?>
                <form style="display: inline" action="" name="" method="post">
                    <select name="reserveDate">
                    <?php
                    for ($i = 0; $i < 3; $i++) {
                        if ($date == date('Y-m-d', strtotime('+' . $i . 'day'))){
                            echo $date;
                        }else {
                            echo '<option value="' . h($date = date('Y-m-d', strtotime('+' . $i . 'day'))) . '">' . h($date = date('Y-m-d', strtotime('+' . $i . 'day'))) . '</option>' . "\n";
                        }
                    }
                    ?>
                    </select>
                    <button type="submit">日にちを変更する</button>
                </form> 
            </div>
            
            
            <div id="timetable_box">
                <?=$html[0].$html[1]?>
            </div><!-- /#timetable_box -->
              
            

            <div id="form_box">
                <h2>予約フォーム</h2>
                <form action="" name="iptfrm" method="post">
                    <input type="hidden" name="date" value="<?= h($date); ?>" />
                    <input type="hidden" name="userId" value="<?= h($_SESSION['USERID']); ?>" />
                    <input type="hidden" name="roomNumber" value="<?= h($_SESSION['ROOMNUMBER']); ?>" />
                    <br />
                    <label>予約日</label>
                   　<!--3日後まで予約できるようにする-->
                    <select name="reserveDate">
                       <?php
                        for ($i = 0; $i < 3; $i++) {
                            echo '<option value="' . h($date = date('Y-m-d', strtotime('+'.$i. 'day'))) . '">' . h($date = date('Y-m-d', strtotime('+'.$i. 'day'))) . '</option>' . "\n";
                        }
                        ?>
                    </select>
                    <br />
                    <label>設 備</label>
                    <?php
                    $facility = select_Name_facilityTimetable(search_facilityId($_SESSION['HOUSEID']));
                    foreach ($facility as $value) {
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

            <div id="weather_box">
                <h2><?= $area_name . "の" ?>天気 (直近3日間)</h2>
                <table  border='1' align='center'>
                    <?= $weather_html ?>
                </table>
            </div><!-- /#weather_box -->

            <!-- カレンダー部分 -->
            
            <div id="calendar_box">
                <h2>カレンダー</h2>
                <?php
                $get_y = date('Y'); //本日の年
                $get_m = date('n'); //本日の月
                $i = 0;
                while ($i < 3) { //今月から3つ出したかったら
                    echo get_rsv_calendar($get_y, $get_m, $date); //カレンダー出力
                    $get_m++; //月+1
                    if ($get_m > 12) { //12月を超えたら
                        $get_m = 1; //1月へ
                        $get_y++; //年+1
                    }
                    $i++;
                }
                ?>
            </div><!-- /#calendar_box -->

<?php include (_ROOT_DIR .'/footer.php');?>