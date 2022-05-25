<?php

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

//ログイン済みかチェック
function login_check($name){
    if (isset($name)) {
        echo "ようこそ" . $name . "さん<br>";
    }else{
        echo "<a href='logout.php'>ログアウト</a><br>";
        echo "<a href='index.php'>こちら</a>からログインしてください<br>";
    }
}

//メールの形式チェック
function email_check($input_email){
    if (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $message = "処理できませんでした。";
        $message .= "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
        return $message;
    }
}

//パスワードの正規表現チェック
function password_check($input_password){
    if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,50}+\z/i', $input_password)) {   
        $message = "処理できませんでした。";
        $message .= "パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。";
        $message .= "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
        return $message;
    }
}

/*** カレンダー ***/
function get_rsv_calendar($yyyy, $mm, $date) {
	$thisyear = $yyyy; //年
	$thismonth = $mm; //月
	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear); //該当月1日のタイムスタンプ
	$last_day = date('t', $unixmonth); //該当月の日数

	$calendar_output = '<table class="rsv_calendar">
	<caption>' . $thisyear .'年' . $thismonth . '月' . '</caption>
	<thead>
	<tr>';

	$myweek = array("日", "月", "火", "水", "木", "金", "土"); //曜日定義

	foreach ( $myweek as $wd ) {
		$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$wd</th>"; //曜日出力
	}

	$calendar_output .= '
	</tr>
	</thead>

	<tbody>
	<form action="" method="post">
	<tr>';

	$pad = date('w', $unixmonth); //該当月1日の曜日番号（日:0～土:6）
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t".'<td colspan="'. $pad .'" class="pad">&nbsp;</td>'."\n\t\t";

	for ( $day = 1; $day <= $last_day; ++$day ) { //1日から最終日まで繰り返し
		if ( isset($newrow) && $newrow )
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t"; //行フラグ$newrowがtrueなら
		$newrow = false; //行フラグリセット

		$sp_date = explode("-", $date); //引数$dateを分割
		if ( $day == $sp_date[2] && $thismonth == $sp_date[1] && $thisyear == $sp_date[0] )
			$calendar_output .= '<td id="current">'; //引数と一致する日にid付加
		elseif ( $day == date('j') && $thismonth == date('m') && $thisyear == date('Y') )
			$calendar_output .= '<td id="today">'; //本日と一致する日にid付加
		else
			$calendar_output .= '<td>';

		$calendar_output .= '<input type="submit" name="calendar['.$thisyear.'-'.str_pad($thismonth,2,"0",STR_PAD_LEFT).'-'.str_pad($day,2,"0",STR_PAD_LEFT).']" value="'.$day.'">';
		$calendar_output .= "</td>\n\t\t";

		if ( 6 == date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear)) )
			$newrow = true; //最終列なら行フラグを立てる
	}

	$pad = 7 - date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear));
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. $pad .'">&nbsp;</td>'; //余ったtdを埋める

	$calendar_output .= "\n\t</tr>\n\t</form>\n\t</tbody>\n</table>\n";

        return $calendar_output; //出力
}

//javascript alertメッセージ
function javascript_alart($msg){
    $javascript_log = "<script type='text/javascript'>alert('".$msg."');</script>";
    return $javascript_log;
}

//javascript confirmメッセージ
function javascript_confirm($msg){
    $javascript_log = '<script type="text/javascript">';
    $javascript_log .= 'if(confirm("'.$msg.'")){return true;}else{return false;}</script>';
    return $javascript_log;
}

function noteDisplay($msg) {
    alert('".$msg."');
    
}

// 浴室と洗濯機の予約済みテーブルを作る関数
function make_facilityTimetable_html($facilityData, $date) {
    $x = 1;
    $y = 1;
    $num = 1;
    $html = "";
    $html2 = "";
    if(in_array( '浴 室', array_column( $facilityData, 'facilityName'))){
	$maxnum_bath = array_count_values(array_column($facilityData, 'facilityName'))["浴 室"];
    }else{
	$html .= "<ul class='facili_ul'>";
        $html .= "<table border='1' align='center' class='facili_table'>";
        $html .= "<h2>浴 室</h2>";
        $html .= "<tr>";
        $html .= "<thead><th class='facili_time'>時間</th><th class='facili_room'>部屋番号</th><th class='facili_note'>備考</th><th class='facili_del'>削除</th></thead>";
        $html .= "</tr>";
        $html .= "<tbody><td colspan='4'>No Reservation</td></tbody>";
        $html .= "</table>";
        $html .= "</ul>";
    }
    if(in_array( '洗濯機', array_column( $facilityData, 'facilityName'))){
	$maxnum_wash = array_count_values(array_column($facilityData, 'facilityName'))["洗濯機"];
    }else{
	$html2 .= "<ul class='facili_ul'>";
        $html2 .= "<table border='1' align='center' class='facili_table'>";
        $html2 .= "<h2>洗濯機</h2>";
        $html2 .= "<tr>";
        $html2 .= "<thead><th class='facili_time'>時間</th><th class='facili_room'>部屋番号</th><th class='facili_note'>備考</th><th class='facili_del'>削除</th></thead>";
        $html2 .= "</tr>";
        $html2 .= "<tbody><td colspan='4'>No Reservation</td></tbody>";
        $html2 .= "</table>";
        $html2 .= "</ul>";
    }
    
    foreach ($facilityData as $value) {

        if ($value['facilityName'] == "浴 室") {
            if ($x == $num) {
                $html .= "<ul class='facili_ul'>";
                $html .= "<table border='1' align='center' class='facili_table'>";
                $html .= "<h2>浴 室</h2>";
                $html .= "<tr>";
                $html .= "<th class='facili_time'>時間</th><th class='facili_room'>部屋番号</th><th class='facili_note'>備考</th><th class='facili_del'>削除</th>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td>";
            $html .= substr(h($value['timeStart']), -8, 5) . "<br>&#x2240;<br>" . substr(h($value['timeEnd']), -8, 5);
            $html .= "</td>";
            $html .= "<td>";
            $html .= h($value['roomNumber']) . "号室";
            $html .= "</td>";
            $html .= "<td>";
            if (!empty($value['notes'])) {
                $html .= '<form action="" method="post">';
                $html .= '<input type="button" name="notedisplay" value="表示" onclick="noteDisplay(\'' . h($value['notes']) . '\')"></form>';
            } else {
                $html .= 'ー';
            }
            $html .= "</td>";
            $html .= "<td>";
            $html .= '<form action="" method="post" onsubmit="return dltChk()">';
            $html .= '<input type="hidden" name="date" value="' . $date . '" />';
            $html .= '<input type="hidden" name="id" value="' . h($value['id']) . '" />';
            $html .= '<input type="submit" name="delete_key_delete" value="×"></form>';
            $html .= "</td>";
            $html .= "</tr>";
            
            if ($x == $maxnum_bath) {
                $html .= "</table>";
                $html .= "</ul>";
            }
            $x++;
        } elseif ($value['facilityName'] == "洗濯機") {
            if ($y == $num) {
                $html2 .= "<ul class='facili_ul'>";
                $html2 .= "<table border='1' align='center' class='facili_table'>";
                $html2 .= "<h2>洗濯機</h2>";
                $html2 .= "<tr>";
                $html2 .= "<th class='facili_time'>時間</th><th class='facili_room'>部屋番号</th><th class='facili_note'>備考</th><th class='facili_del'>削除</th>";
                $html2 .= "</tr>";
            }
            $html2 .= "<tr>";
            $html2 .= "<td>";
            $html2 .= substr(h($value['timeStart']), -8, 5) . "<br>&#x2240;<br>" . substr(h($value['timeEnd']), -8, 5);
            $html2 .= "</td>";
            $html2 .= "<td>";
            $html2 .= h($value['roomNumber']) . "号室";
            $html2 .= "</td>";
            
            if (!empty($value['notes'])) {
                $html2 .= "<td>";
                $html2 .= '<form action="" method="post">';
                $html2 .= '<input type="button" name="notedisplay" value="表示" onclick="noteDisplay(\'' . h($value['notes']) . '\')"></form>';
                $html2 .= "</td>";
            } else {
                $html2 .= "<td>";
                $html2 .= 'ー';
                $html2 .= "</td>";
            }
            
            $html2 .= "<td>";
            $html2 .= '<form action="" method="post" onsubmit="return dltChk()">';
            $html2 .= '<input type="hidden" name="date" value="' . $date . '" />';
            $html2 .= '<input type="hidden" name="id" value="' . h($value['id']) . '" />';
            $html2 .= '<input type="submit" name="delete_key_delete" value="×" /></form>';
            $html2 .= "</td>";
            $html2 .= "</tr>";
            
            if ($y == $maxnum_wash) {
                $html2 .= "</table>";
                $html2 .= "</ul>";
            }
            $y++;
        }
    }
    return [$html, $html2];
}

// 天気のテーブルを作る関数
function make_weathertable_html($time_and_weather_array) {

//    $html = "<tr>";
    
    $week = array( "(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)" );
    $html = "";
    foreach ($time_and_weather_array as $key => $value) {
        $html .= "<tr>";
        $html .= "<th>";
        $date = new DateTime($key);
        $html .= $date->format("n月j日 ").$week[$date->format('w')];
        $html .= "</th>";
        $html .= "<td>";
        $html .= $value;
        $html .= "</td>";
        $html .= "</tr>";
    }
 
//    $html .= "</tr><tr>";
// 
//    foreach ($weather_array as $value) {
//        $html .= "<td>";
//        $html .= $value;
//        $html .= "</td>";
//    }
// 
//    $html .= "</tr>";
    
    return $html;
}

function bbs_display ($all){
    $weekdays = array('(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)');
    $html = "";
    if($all){
        foreach ($all as $row) {
        // YYYY/MM/DD 曜日 HH:mm:ss　の形式に連結する
        $date = "${row['date']} ${weekdays[$row['weekday']]} ${row['time']}";
        $html .= "<tr>";
        $html .= '<td class="name">';
        $html .= h($row['name']);
        $html .= "</td>";
        $html .= '<td class="msg">';
        $html .= nl2br(h($row['msg']), false);
        $html .= "</td>";
        $html .= '<td class="date">';
        $html .= $date;
        $html .= "</td>";
        $html .= '<td align = "center" valign ="middle" >';
        $html .= '<form action="" method="post" onsubmit="return dltChk()">';
        $html .= '<input type="hidden" name="id" value="' . h($row['id']) . '" />';
        $html .= '<input class="message_delete" type="submit" name="message_delete" value=" × "></form>';
        $html .= "</td>";
        $html .= "</tr>";
        }
    }else {
        $html .= "<tr>";
        $html .= '<td colspan="4" rowspan="4">';
        $html .= "投稿はありません。";
        $html .= "</td>";
        $html .= "</tr>";
    }
    return $html; 
}

?>


<!--javascript-->

<script type="text/javascript">
/* 開始時間が変更されたら */
function autoPlus(s) {
	document.iptfrm.timeEnd.selectedIndex = s.selectedIndex;
}
/* キー無しの削除確認 */
function dltChk() {
	var flag = confirm ( "削除します。よろしいですか？");
	return flag; /* flag が TRUEなら送信、FALSEなら送信しない */
}

function noteDisplay($msg) {
    alert($msg);
    
}

var dt=new Date();
var d=encodeDate(dt.getFullYear(),dt.getMonth(),dt.getDate(),dt.getHours(),dt.getMinutes(),dt.getSeconds());


function encodeDate(yy,mm,dd,hh,ii,ss){
  var days=[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  if(((yy % 4) == 0) && (((yy % 100) != 0) || ((yy % 400) == 0)))days[1]=29;
  for(var i=0; i<mm; i++)dd+=days[i];
  yy--;
  return ((((yy * 365 + ((yy-(yy % 4)) / 4) - ((yy-(yy % 100)) / 100) + ((yy-(yy % 400)) / 400) + dd - 693594) * 24 + hh) * 60 + ii) * 60 + ss)/86400.0;
}
</script>


