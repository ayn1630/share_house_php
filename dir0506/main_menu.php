<?php
session_start();
require_once('db_connect.php');

//ログイン済みの場合
$login_name = h($_SESSION['NAME']);
$delete_key = h($_SESSION['ID']);
$login_mail = h($_SESSION['EMAIL']);


if (isset($login_mail)) {
  echo "ようこそ" . $login_name . "さん<br>";
  echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
}

if($login_mail == ADMIN_MAIL) {
    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
}



if(!$login_mail > 0) {
    header('Location:https://snow-milk-sugar-salt-27-1.paiza-user-basic.cloud/~ubuntu/index.php');
}
?>


<?php




/********** 手動設定 **********/
$hours_st = '00:00'; //設定開始時間('hh:nn'で指定)
$hours_end = '24:00'; //設定終了時間('hh:nn'で指定)
$hours_margin = 30; //間隔を指定(分)
$chapters = array('お風呂', '洗濯機', '定期清掃', '管理者', 'イベント'); //項目名を配列で定義
$chapters_edit_member = array('お風呂', '洗濯機', 'イベント');
$tbl_flg = false; //時間を横軸 → true, 縦軸 → falseにする
$master_key = 'special';
/********** ここまで **********/


/*** DB接続 ***/
$pdo = db_connect();

$id = (int)filter_input(INPUT_POST, 'id');

/*** ページ読込前の設定部分 ***/
//エラー出力する
ini_set( 'display_errors', 1 );
//タイムゾーンセット
date_default_timezone_set('Asia/Tokyo');
//本日を取得
$date = date('Y-m-d'); //YYYY-MM-DDの形

//設定時間を計算して配列化
$hours_baff = new DateTime( $date.' '.$hours_st ); //配列格納用の変数
$hours_end_date = new DateTime( $date.' '.$hours_end ); //終了時間を日付型へ
$hours = array(); //時間を格納する配列
array_push($hours, $hours_baff->format('H:i')); //配列に追加
$hours_baff = $hours_baff->modify ("+{$hours_margin} minutes"); //設定間隔を足す
while ( $hours_baff <= $hours_end_date ) { //終了時間まで繰り返す
	if ( $hours_baff->format('H:i') == '00:00' ){ //終了時間が00:00だったら
		array_push($hours, '24:00'); //24:00で配列に追加
	} else {
		array_push($hours, $hours_baff->format('H:i')); //配列に追加
	}
	$hours_baff = $hours_baff->modify ("+{$hours_margin} minutes"); //設定間隔ずつ足していく
}

//////////////////////////////////////////////////////////
//Cookie
$my_name = (string)filter_input(INPUT_COOKIE, 'my_name');

//タイムテーブル設定
if ( $tbl_flg == true ) {
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

//メッセージ用変数
$log1 = '';
$log2 = '';


/*** 各種ボタンが押された時の処理 ***/
if ( isset($_POST['calendar']) ){
	/*** カレンダーがクリックされた場合 ***/
	$date = !is_string(key($_POST['calendar'])) ? '' : key($_POST['calendar']);

} elseif ( isset($_POST['register']) ) {
	/*** 登録ボタンがクリックされた場合 ***/
	//フォームに入力された情報を各変数へ格納
	foreach (array('date', 'my_name', 'notes', 'time_start', 'time_end', 'item_name', 'delete_key') as $v) { 
		$$v = (string)filter_input(INPUT_POST, $v);
	}
	$time_start = $date . ' ' . $time_start . ':00'; //開始時間（MySQLのDATETIMEフォーマットへ成形）
	$time_end = $date . ' ' . $time_end . ':00'; //終了時間

////////////////////////////////////////////////////////////////////////////////
	//Cookie
	setcookie('my_name', $my_name, time() + 60 * 60 * 24 * 14); //14日間保存


	if( $time_start >= $time_end ) { //開始時間 >= 終了時間の場合
	
	    $alart_msg = "時間設定が不正のため、登録できませんでした。";
	    echo javascript_alart($alart_msg);
	
//		$log1 = '<p>時間設定が不正のため、登録できませんでした。</p>';
	} else { //正常処理
		$sbm_flg = false; //予約済み時間との重複フラグを設定
		$results = $pdo->prepare(
			'SELECT *
			FROM sharehouse_timetable
			WHERE time_start BETWEEN :date1 AND :date2
			AND item_name = :item_name'
		);
		$results->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
		$results->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
		$results->bindValue(':item_name', $item_name, PDO::PARAM_STR);
		$results->execute();
		if ( $results ) { foreach ( $results as $value ) { //該当のデータ数繰り返す
			$time1 = strtotime( $value['time_start'] ); //該当IDの開始時刻
			$time2 = strtotime( $value['time_end'] ); //該当IDの終了時刻
			if ( $time1 <= strtotime( $time_start ) && strtotime( $time_start ) < $time2 ) {
				$sbm_flg = true; //予約済開始時刻 <= 開始時刻 < 予約済終了時刻 ならフラグを立てる
			}
			if ( $time1 < strtotime( $time_end ) && strtotime( $time_end ) <= $time2 ) {
				$sbm_flg = true; //予約済開始時刻 < 終了時刻 <= 予約済終了時刻 ならフラグを立てる
			}
			if ( strtotime( $time_start ) <= $time1 && $time2 <= strtotime( $time_end ) ) {
				$sbm_flg = true; //開始時刻 <= 予約済開始時刻 & 予約済終了時刻 <= 終了時刻 ならフラグを立てる
			}
		} }
		if( $sbm_flg == true ) { //フラグが立ってたら登録できない
	    	$alart_msg = "既に予約されているため、この時間帯では登録できません。";
	        echo javascript_alart($alart_msg);
	    
	    
//			$log1 = '<p>既に予約されているため、この時間帯では登録できません。</p>';
		} else {
			//登録処理
			$sql = $pdo->prepare(
				'INSERT INTO sharehouse_timetable
				( name, item_name, time_start, time_end, notes, delete_key )
				VALUES ( :name, :item_name, :time_start, :time_end, :notes, :delete_key )'
			);
			$sql->bindValue(':name', $my_name, PDO::PARAM_STR);
			$sql->bindValue(':item_name', $item_name, PDO::PARAM_STR);
			$sql->bindValue(':time_start', $time_start, PDO::PARAM_STR);
			$sql->bindValue(':time_end', $time_end, PDO::PARAM_STR);
			$sql->bindValue(':notes', $notes, PDO::PARAM_STR);
			$sql->bindValue(':delete_key', $delete_key, PDO::PARAM_STR);
			$rsl = $sql->execute(); //実行
			if ( $rsl == false ){
			    $alart_msg = "登録に失敗しました";
	            echo javascript_alart($alart_msg);
	    
	    
//				$log1 = '<p>登録に失敗しました。</p>';
			} else {
			    $alart_msg = "登録しました。";
	            echo javascript_alart($alart_msg);
	    
	    
//				$log1 = '<p>登録しました。</p>';
			}
		}
	}
	
/////////////////////////////////////////////////////////////////////////////////
} elseif ( isset($_POST['delete_key_delete']) ) {
    $date = (string)filter_input(INPUT_POST, 'date');
	$id = (int)filter_input(INPUT_POST, 'id');
    try {
        $stmt = $pdo->prepare( 'SELECT name FROM sharehouse_timetable WHERE id = :id' );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
    
    if ( $stmt ) { 
	    foreach ( $stmt as $value ) {
		$name = $value['name'];
	    }
	}
    
    if ( $name == $login_name || $login_mail == ADMIN_MAIL){
    
    
       	$date = (string)filter_input(INPUT_POST, 'date');
	$id = (int)filter_input(INPUT_POST, 'id');

        $sql = $pdo->prepare( 'DELETE FROM sharehouse_timetable WHERE id = :id' );
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $rsl = $sql->execute(); //実行
        if ( $rsl == false ){
            $alart_msg = "削除に失敗しました。";
	    echo javascript_alart($alart_msg);
	        
        } else {
            $alart_msg = "削除しました。";
	    echo javascript_alart($alart_msg);
	      
	}
    }else {
        $alart_msg = "ユーザーが違うため、削除できません。";
	echo javascript_alart($alart_msg);
    }
}

/*** タイムテーブル生成のための下準備をする部分 ***/
foreach ($chapters as $cpt) {
	for ( $i = 0; $i < count($hours); $i++ ) {
		$data_meta[$cpt][$i] = null; //配列を定義しておく（エラー回避）
	}
}
$err_n = 0; //エラー件数カウント用
$data_n = 1; //0はデータ無しにしたいので、1から始める
//指定日付のデータをすべて抽出
$results = $pdo->prepare(
	'SELECT *
	FROM sharehouse_timetable
	WHERE time_start BETWEEN :date1 AND :date2'
);
$results->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
$results->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
$results->execute();
if ( $results ) { foreach ( $results as $value ) { //指定日付のデータ数繰り返す
	$key1 = null; //エラーキャッチ用にnullを入れておく
	$key2 = null;
	
	$time1 = substr($value['time_start'], 11, 5); //該当データの開始日時'00:00'抜出
	$key1 = array_search($time1, $hours); //時間配列内の番号	
	$time2 = substr($value['time_end'], 11, 5); //該当データの終了日時'00:00'抜出
	$key2 = array_search($time2, $hours); //時間配列内の番号
	if ( is_numeric($key1) == false || is_numeric($key2) == false || in_array($value['item_name'], $chapters) == false	) {
		$log2 .= '<li>'.h($value['item_name']).'('.h($value['name']).','.h($value['sect']).') '.$time1.'～'.$time2."</li>\n"; //エラー内容格納
		$err_n++; //エラー件数カウントアップ
	} else {
		//$data_meta['項目名']['開始時間配列番号']へナンバリングしていく
		$data_meta[$value['item_name']][$key1] = $data_n;
		//必要な情報を格納しておく
		$ar_block[$data_n] = $key2 - $key1; //開始時間から終了時間までのブロック数
		$ar_id[$data_n] = $value['id'];
		$ar_name[$data_n] = $value['name'];
		$ar_notes[$data_n] = $value['notes'];
		$ar_delete_key[$data_n] = $value['delete_key'];
		$data_n++; //データ数カウントアップ
	}
} }
?>
<html>
<head>
    <link rel="stylesheet" href="stylesheet.css" >
</head>
<body>
	<div id="content">


<?php
/*** メッセージ ***/
if ( $log1 != '' ) { //処理メッセージがある場合
	$log1 = '<p class="msg">処理メッセージ</p>'."\n".$log1;
}
if ( $log2 != '' ) { //エラーメッセージがある場合
	$log2 = '<p class="msg">'.$err_n."件の不整合データを表示できませんでした。</p>\n<ul>\n".$log2;
	$log2 .= "</ul>";
}
if ( $log1 != '' || $log2 != '' ) { //どちらかのメッセージがある場合
	echo '<div id="attention">'."\n";
	if ( $log1 != '' ) { echo $log1."\n"; } //処理メッセージがある場合
	if ( $log1 != '' && $log2 != '' ) { echo "<br />\n"; } //両方ある場合は改行も
	if ( $log2 != '' ) { echo $log2."\n"; } //エラーメッセージがある場合
	echo "</div>\n";
}
?>

<!-- タイムテーブル -->
<div id="timetable_box">
<?php $sp_date = explode("-", $date); ?>
<h1><?php printf('%s年%s月%s日', $sp_date[0], $sp_date[1], $sp_date[2]); ?></h1>
<?php
for ( $i = 0; $i < $clm_n; $i++ ) {
	$span_n[$i] = 0; //rowspan結合数を格納する配列にゼロを入れておく
}
//ここから $timetable_output へ table の記述を入れていく
$timetable_output = '<table id="timetable">'."\n<thead>\n<tr>\n".'<th id="origin">時間</th>'."\n";
for ( $i = 0; $i < $clm_n; $i++ ) {
	$timetable_output .= '<th class="cts">'.$clm[$i]."</th>\n"; //横軸見出し
}
$timetable_output .= "</tr>\n</thead>\n<tbody>\n";
for ( $i = 0; $i < $row_n; $i++ ) { //縦軸の数繰り返す
	$timetable_output .= "<tr><td>".$row[$i].'</td>'; //縦軸見出し
	for ( $j = 0; $j < $clm_n; $j++ ) { //横軸の数繰り返す
		if ( $tbl_flg == false && $span_n[$j] > 0 ) { //時間軸が縦の場合の繰り上げ処理
			$span_n[$j]--; //rowspan結合の数だけtd出力をスルー
		} else { //通常時
			$block = '';
			$data_n = 0; //ゼロはデータ無し
			if ( $tbl_flg == true ) { //時間軸が横なら
				$data_n = $data_meta[$row[$i]][$j];
			} else { //時間軸が縦なら
				$data_n = $data_meta[$clm[$j]][$i];
			}
			if ( $data_n == 0 ) { //データが無いとき
				$timetable_output .= '<td>&nbsp;</td>'; //空白を入れる
			} else { //データが有るとき
				if ( $ar_block[$data_n] > 1 ) { //ブロックが2つ以上
					if ($tbl_flg == true) { //時間軸が横だったら
						$block = ' colspan="'.$ar_block[$data_n].'"'; //横方向へ結合
						$j = $j + $ar_block[$data_n] - 1; //colspan結合ぶん横軸数を繰り上げ
					} else { //時間軸が縦だったら
						$block = ' rowspan="'.$ar_block[$data_n].'"'; //縦方向へ結合
						$span_n[$j] = $ar_block[$data_n] - 1; //rowspan結合数を格納→冒頭で繰り上げ処理
					}
				}
				$cts = h($ar_name[$data_n]).'<br />'.h($ar_notes[$data_n]); //htmlエスケープしながら中身成形
//				if ( $ar_delete_key[$data_n] === '' ) { //削除キー無
					//onsubmitでJavaScriptを呼び出す
//					$dlt = '<form action="" method="post" onsubmit="return dltChk()"><input type="hidden" name="date" value="'.$date.'" /><input type="hidden" name="id" value="'.$ar_id[$data_n].'" /><input type="submit" name="delete" value="×"></form>';
//				} else { //削除キー有
					//カギ画像付加
					$dlt = '<form action="" method="post" onsubmit="return dltChk()"><input type="hidden" name="date" value="'.$date.'" /><input type="hidden" name="id" value="'.$ar_id[$data_n].'" /><input type="submit" name="delete_key_delete" value="×"></form>';
//				}
				$timetable_output .= '<td class="exist"'.$block.'>'.$cts.$dlt.'</td>'; //tdの中に出力
			}
		}
	} //横軸for
	$timetable_output .= "</tr>\n";
} //縦軸for
$timetable_output .= "</tbody>\n</table>\n";
echo $timetable_output; //出力
?>
</div><!-- /#timetable_box -->

<div id="form_box">
	<h2>登録フォーム</h2>
	<form action="" name="iptfrm" method="post">
		<input type="hidden" name="date" value="<?php echo h($date); ?>" />
		<br />
		<input type="hidden" name="my_name" value="<?php echo $login_name; ?>" />
		<br />
		<label>予約表</label>
		<select name="item_name">
		<?php
		if ($login_mail == ADMIN_MAIL){
		    foreach ($chapters as $value) {
			echo '<option value="'.$value.'">'.$value.'</option>';
		    }
		}elseif ($login_mail == $souji){
		    foreach ($chapters_edit_souji as $value) {
			echo '<option value="'.$value.'">'.$value.'</option>';
		    }
		}else{
		    foreach ($chapters_edit_member as $value) {
			echo '<option value="'.$value.'">'.$value.'</option>';
		    }
		}
		?>
		</select>
		<br />
		<label>開始時間</label>
		<select name="time_start" onChange="autoPlus(this)">
		<?php
		for ( $i=0; $i<count($hours)-1; $i++ ) {
			echo '	<option value="'.$hours[$i].'">'.$hours[$i].'</option>'."\n";
		}
		?>
		</select>
		<br />
		<label>終了時間</label>
		<select name="time_end">
		<?php
		for ( $i=1; $i<count($hours); $i++ ) {
			echo '	<option value="'.$hours[$i].'">'.$hours[$i].'</option>'."\n";
		}
		?>
		</select>
		<br />
		<label>備考</label>
		<input type="text" name="notes" size="10" value="" />
		<br />
		<input type="hidden" name="delete_key" value="<?php echo h($delete_key); ?>" />
		<br />
		<input type="submit" name="register" value="登録" />
	</form>
</div><!-- /#form_box -->

<div id="menu_box">
    <h2>各種メニュー</h2>
    <br><a href='contact_form.php'>お問合せフォーム</a><br>
    <br><a href='infomation.php'>インフォメーション</a><br>
    <br><a href='mypage.php'>マイページ</a><br>
</div>
    

<!-- カレンダー部分 -->
<div id="calendar_box">
	<h2>カレンダー</h2>
	<?php
	$get_y = date('Y'); //本日の年
	$get_m = date('n'); //本日の月
	$i = 0;
	while ( $i < 3 ) { //今月から3つ出したかったら
		get_rsv_calendar($get_y, $get_m, $date); //カレンダー出力
		$get_m++; //月+1
		if ( $get_m > 12 ) { //12月を超えたら
			$get_m = 1; //1月へ
			$get_y++; //年+1
		}
		$i++;
	}
	?>
</div><!-- /#calendar_box -->


<!-- ここから下 カレンダー -->

<?php
/*** htmlエスケープ ***/
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
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

	echo $calendar_output; //出力
}

//alertメッセージ
function javascript_alart($msg){
    $javascript_log = "<script type='text/javascript'>alert('".$msg."');</script>";
    return $javascript_log;
}

//confirmメッセージ
function javascript_confirm($msg){
    $javascript_log = '<script type="text/javascript">';
    $javascript_log .= 'if(confirm("'.$msg.'")){return true;}else{return false;}</script>';
    return $javascript_log;
}

?>
</div><!-- #content -->


<!--javascript-->

<script type="text/javascript">
/* 開始時間が変更されたら */
function autoPlus(s) {
	document.iptfrm.time_end.selectedIndex = s.selectedIndex;
}
/* キー無しの削除確認 */
function dltChk() {
	var flag = confirm ( "削除します。よろしいですか？");
	return flag; /* flag が TRUEなら送信、FALSEなら送信しない */
}
</script>

</body>
</html>