<?php
define('_ROOT_DIR', __DIR__ . '/');
require_once _ROOT_DIR .'/config.php'; 
require_once _ROOT_DIR .'/db_connect.php';
require_once _ROOT_DIR .'/functions.php';

//header("Location: http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . "/index.php");
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

//for ($i = 1; $i <= 1; $i++) {
//    $input_roompass = password_hash($i.$i.$i.$i, PASSWORD_DEFAULT);
////    echo $input_roompass."<br>";
//    echo "update room set roomPass='$input_roompass' where roomId=$i;<br>";
//}


//function db_connect() {
//    try {
//        $pdo = new PDO(DSN, DB_USER, DB_PASS,
//                array(
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//            PDO::ATTR_EMULATE_PREPARES => false
//                )
//        );
//    } catch (Exception $e) {
//        exit('データベース接続に失敗しました。' . $e->getMessage() . PHP_EOL);
//    }
//
//    return $pdo;
//}


//function roompass_check($input_roompass) {
//    try {
//        $pdo = db_connect();
//        $sql = "select roomPass from room";
//        $stmt = $pdo->query($sql);
//        $allRoomPassData = $stmt->fetchAll(PDO::FETCH_COLUMN);
////        print_r($allRoomPassData);
//        "<br>";
//        "<br>";
//        foreach ($allRoomPassData as $value){
////            echo $value."<br>";
//            if (password_verify(1111, $value)){
//                $result = $value;
//                return $result;
//            }
//        }
//        $message = "処理できませんでした。";
//        $message .= "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
//        return $message;
        
//        if ($row == True) {
//            $result = $row[roomPass];
//            return $result;
//        }

//        }
//    } catch (\Exception $e) {
//        exit($e->getMessage() . PHP_EOL);
//    }   
//}

//$u = roompass_check(1111);
//echo $u;
// $2y$10$PEvpjSRkDl7b2fMDUSoBZOpuZH41q1Vp3wXb9Xk7gPRc3ZeFNjDmi
// $2y$10$1Dna8HNblJ0mdfd3ugt4YOtEhfK.zaOOds7QHf97z1zyO9LS2gJ.e

"<br>";
"<br>";

//echo password_hash("1121", PASSWORD_DEFAULT)."<br>";
//echo password_hash("2221", PASSWORD_DEFAULT)."<br>";
//echo password_hash("3321", PASSWORD_DEFAULT)."<br>";
//echo password_hash("4421", PASSWORD_DEFAULT)."<br>";
//echo password_hash("5521", PASSWORD_DEFAULT)."<br>";
//echo password_hash("6621", PASSWORD_DEFAULT)."<br>";




//if (password_verify(1111, $hash)) {
//    echo 'Password is valid!';
//} else {
//    echo 'Invalid password.';
//}
//
//foreach($all as $loop){
//    echo "name = ".$loop['name'].PHP_EOL;
//}
//$hash = '$2y$10$PEvpjSRkDl7b2fMDUSoBZOpuZH41q1Vp3wXb9Xk7gPRc3ZeFNjDmi';
//
//if (password_verify(1111, $hash)) {
//    echo 'Password is valid!';
//} else {
//    echo 'Invalid password.';
//}

//$pdo = db_connect();
//$sql = 
//        "SELECT roomId,roomNumber
//        FROM room 
//        WHERE houseId = 2";
//$stmt = $pdo->query($sql);
//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

//print_r($results['roomId']);
//
//print_r($array[roomId]);
"<br>";
"<br>";



        $pdo = db_connect();
        $sql = ("select facilityId from facility where houseId = 1");
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
//        $facilityIdList = $stmt->fetch(PDO::FETCH_COLUMN);
        print_r($result);
        
        
        foreach ($result as $value) {
            echo $value;
        }
"<br>";
"<br>";

//$pdo = db_connect();
//    $sql = 
//            "SELECT facilityId, facilityName
//            FROM facility 
//            WHERE facilityId in (".implode(",",$result).")";
//    $stmt = $pdo->query($sql);
//    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    print_r($results) ;

"<br>";
"<br>";

$date = date('Y-m-d'); 
//    $pdo = db_connect();
//    $results = $pdo->prepare(
//            "SELECT *
//            FROM facilityTimetable 
//            WHERE (timeStart BETWEEN :date1 AND :date2) AND facilityId in (".implode(",",$result).");");
//    $results->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
//    $results->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
//    $results->execute();
//    print_r($results);
    

$results = $pdo->prepare(
	"SELECT *
            FROM facilityTimetable 
            WHERE (timeStart BETWEEN :date1 AND :date2) AND facilityId in (".implode(",",$result).");");
$results->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
$results->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
$results->execute();
print_r($results);

if ( $results ) { foreach ( $results as $value ) {
    $time1 = substr($value['timeStart'], 11, 5);
    $time2 = substr($value['timeEnd'], 11, 5);
    echo "<br>".$time1."<br>";
    echo "<br>".$time2."<br>";
    echo "<br>".$value['timeStart']."<br>";
    echo "<br>".$value['timeEnd']."<br>";
}}


$facility = select_Name_facilityTimetable(search_facilityId(1));
//print_r($facility);
//$chapters = [];
foreach ($facility as $value) {
    $chapters[] = $value['facilityId'];
}

print_r($chapters)."<br>";


$chapter = array('お風呂', '洗濯機', '定期清掃', '管理者', 'イベント');
print_r($chapter);



$today = new DateTime('now');
echo $today->format('Y-m-d H:i:s');
//print str($today);


?>
<div id="title_day">
                <?php $sp_date = explode("-", $date);
                echo $date."<br>";
                if ($date > date('Y-m-d'))
                {
                ?>
                <form style="display: inline" action="main_menu_house.php" method="post" name="date_yesterday"><input type="hidden" name="yesterday" value="<?=h($date = date('Y-m-d', strtotime("$date -1day")))?>" /><a href="javascript:date_yesterday.submit()">＜＜</a></form>
                <?php
                }
                if ($date < date('Y-m-d',strtotime('+2day')))
                {
                ?>
                <form style="display: inline" action="main_menu_house.php" method="post" name="date_tomorrow"><input type="hidden" name="tomorrow" value="<?=h($date = date('Y-m-d', strtotime("$date +1day")))?>"><a href="javascript:date_tomorrow.submit()">＞＞</a></form>
                <?php
                }               
                ?>
                <h1><?php printf('%s年%s月%s日', $sp_date[0], $sp_date[1], $sp_date[2]); ?></h1>
            </div>



function get_rsv_date($date) {
    $sp_date = explode("-", $date);
    $thisyear = $sp_date[0]; //年
    $thismonth = $sp_date[1]; //月
    $thisday = $sp_date[2];//日
    $today = mktime(0, 0, 0, $thismonth, $thisday, $thisyear); //今日のタイムスタンプ
    $prev = date('Y-m-d', strtotime('-1 day', $today)); //昨日
    $next = date('Y-m-d', strtotime('+1 day', $today)); //明日
    printf('%s年%s月%s日', $sp_date[0], $sp_date[1], $sp_date[2]);

    $day_output = "\n\t".'<form action="" method="post">'."\n\t".'<caption>';
    $day_output .= "\n\t\t".'<input type="submit" name="rsv_prev" value="<<">';
    $day_output .= "\n\t\t". $sp_date[0] .'年' . $sp_date[1] . '月'. $sp_date[2] . '日';
    $day_output .= "\n\t\t".'<input type="submit" name="rsv_next" value=">>">';
    $day_output .= "\n\t</caption>\n\t<thead>\n\t<tr>";
    $day_output .= "\n\t</tr>\n\t</tbody>\n\t</form>\n";
    
    return array($day_output , $prev, $next);
}