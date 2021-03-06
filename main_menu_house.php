<?php
session_start();
//define('_ROOT_DIR', __DIR__ . '/');
require_once 'Cache/Lite.php';
require_once __DIR__ .'/config.php'; 
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
//if (isset($_POST['calendar'])) {
//    $date = !is_string(key($_POST['calendar'])) ? '' : key($_POST['calendar']);
//}
//if (isset($_POST['calendar']) && $date > date('Y-m-d')){
//    $hours_st = '00:00';
//}
if ($date != date('Y-m-d')){
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
    
    if ($date != date('Y-m-d')){
        $hours_st = '00:00';
    }
    
//} elseif (isset($_POST['facility_today'])) {
    /*     * * カレンダーがクリックされた場合 ** */

    
    
} elseif (isset($_POST['yesterday'])) {    
    $date = $_POST['yesterday'];
    
    if ($date != date('Y-m-d')){
        $hours_st = '00:00';
    }
    
//    echo $date."*2<br>";
    
    
} elseif (isset($_POST['tomorrow'])) {
    $date = $_POST['tomorrow'];
    
    if ($date != date('Y-m-d')){
        $hours_st = '00:00';
    }
    echo $date;
   
 
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
        $res = confirm_reserve_facilityTimetable($_SESSION['USERID']);
        $results = $res[0];
        $results2 =  $res[1];

        if ($results) {
            foreach ($results as $value) { //該当のデータ数繰り返す
                $time1 = strtotime($value['timeStart']); //該当IDの開始時刻
                $time2 = strtotime($value['timeEnd']);
                $roomNumber = $value['roomNumber'];
                if ($time1 <= strtotime($timeStart) && strtotime($timeStart) < $time2) {
                    $sbm_flg = true; //予約済開始時刻 <= 開始時刻 < 予約済終了時刻 ならフラグを立てる
                }
                if ($time1 < strtotime($timeEnd) && strtotime($timeEnd) <= $time2) {
                    $sbm_flg = true; //予約済開始時刻 < 終了時刻 <= 予約済終了時刻 ならフラグを立てる
                }
                if (strtotime($timeStart) <= $time1 && $time2 <= strtotime($timeEnd)) {
                    $sbm_flg = true; //開始時刻 <= 予約済開始時刻 & 予約済終了時刻 <= 終了時刻 ならフラグを立てる
                }
            }
        }
        if ($results2){
            $sbm_flg2 = true; 
        }

        if ($sbm_flg == true) { //フラグが立ってたら登録できない
            $alart_msg = "既に予約されているため、この時間帯では登録できません。";
            echo javascript_alart($alart_msg);
        }elseif($sbm_flg2 == true){
            $alart_msg = "既に予約済みです。キャンセルするか、予約時間が過ぎてからご登録ください。";
            echo javascript_alart($alart_msg);
        }else {
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
//if(is_dir("cache") == false) {
    // 作る
//    mkdir("cache");
//}

 

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
            <div id="title_day">
                <?php
                if ($date > date('Y-m-d'))
                {
                ?>
                <form style="display: inline" action="" method="post" name="date_yesterday"><input type="hidden" name="yesterday" value="<?=h($date = date('Y-m-d', strtotime("$date -1day")))?>" /><a href="javascript:date_yesterday.submit()">ああ</a></form>
                <?php
                }
                ?>
                <?php $sp_date = explode("-", $date);?>
                <h1><?php printf('%s年%s月%s日', $sp_date[0], $sp_date[1], $sp_date[2]); ?></h1>
            </div>
            
            <div class="contents">
                <div class="contents_sub">
                    <div id="rsvform_box"  align='center'>
                        <table class="form_table">
                        <h2>予約フォーム</h2>
                        <form action="" name="iptfrm" method="post">
                            <input type="hidden" name="date" value="<?= h($date); ?>" />
                            <input type="hidden" name="userId" value="<?= h($_SESSION['USERID']); ?>" />
                            <input type="hidden" name="roomNumber" value="<?= h($_SESSION['ROOMNUMBER']); ?>" />
                            <tr><th class="th_rsv_content">設 備</th><td class="td_rsv_input">
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
                            </td></tr>
                            <tr>
                            <!--<br />-->
                            <th class="th_rsv_content">開始時間</th><td class="td_rsv_input">
                            <select class="rsv_time_start" name="timeStart" onChange="autoPlus(this)">
                                <?php
                                for ($i = 0; $i < count($hours) - 1; $i++) {
                                    echo '	<option value="' . $hours[$i] . '">' . $hours[$i] . '</option>' . "\n";
                                }
                                ?>
                            </select></td>
                            </tr>
                            <!--<br />-->
                            <tr>
                            <th class="th_rsv_content">終了時間</th><td class="td_rsv_input">
                            <select  class="rsv_time_end"  name="timeEnd">
                                <?php
                                for ($i = 1; $i < count($hours); $i++) {
                                    echo '	<option value="' . $hours[$i] . '">' . $hours[$i] . '</option>' . "\n";
                                }
                                ?>
                            </select></td>
                            </tr>
                            <!--<br />-->
                            <tr>
                            <th class="th_rsv_content">備 考</th><td class="td_rsv_input">
                            <input class="rsv_note" type="text" name="notes" size="25" value="" /></td>
                            <!--<br />-->
                            </tr>
                            <tr><td class="td_regist_button" colspan="2">
                            <input class="regist_button" type="submit" name="register" value="登録" />
                            </td></tr> 
                        </form>
                        </table><!-- /#rsvform_table -->
                       

                    </div>

                    <div id="weather_box">
                        <h2><?= $area_name . "の" ?>天気</h2>
                        <table  border='1' align='center'>
                            <?= $weather_html ?>
                        </table>
                    </div><!-- /#weather_box -->
                    
                </div>
                <div class="contents_sub2">
                    <div id="facility">
                        <?=$html[0].$html[1]?>
                    </div>                   
                </div>
            </div>



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

<style>
    h1 {
    }
    
    div.contents {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        text-align: center;
        max-width: 100%
    }
    
    div.contents_sub {
        justify-content: center;
        text-align: center;
        width: 40%;
    }
    
    div.contents_sub2 {
        width: 60%;
        hight: 100%;
    }
    
    .contents ul {
        text-align: center;
        justify-content: center
    }
    
    .facili_table {
        font-size: 1.3em;
        text-align: center;
        width: 80%;
        height: 40%;
    }
    
    .facili_time {
        width: 25%;
        font-size: 90%;
        
    }
    
    .facili_room {
        width: 40%;
        font-size: 90%;
    }
    
    .facili_note {
        width: 17.5%;
        font-size: 90%;
    }
    
    .facili_del {
        width: 17.5%;
        font-size: 90%;
    }
    
    .facili_ul {
        margin: 0;
        padding: 0 0 70 0;
    }
    
    div#rsvform_box {
        padding: 0 0 50 0;
        /*margin: 0 20 0 10;*/
 
    }
    
    div#rsvform_box h2 {

    }
    
    div#rsvform_box label {
        
    }
    
    div#weather_box {
        padding: 0 0 25 0;

    }
    
    .form_table {



    }
    
    .th_rsv_content {
        font-size: 1.0em;
        text-align: center;
        width: 20%;
        
    }

    .td_rsv_input{
        font-size: 1.3em;
        padding: 15 0 15 0;
        width: 20%;
        text-align: center;

    }
    
    select.rsv_time_start {
        font-size: 1.1em;
        width: 70%;
        text-align: center;
    }
    
    select.rsv_time_end {
        font-size: 1.1em;
        width: 70%;
        text-align: center;
    }
    
    .td_regist_button{
        padding: 10 10 10 10;
        font-size: 1.0em;
        text-align: center;
    }
    .regist_button {
        font-size: 1.2em;
    }
    
    
    .rsv_note {


    }
    
</style>