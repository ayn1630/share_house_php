<?php
/* Template Name: rsv_timetable */

/********** 手動設定 **********/
$hours_st = '08:00'; //設定開始時間('hh:nn'で指定)
$hours_end = '17:00'; //設定終了時間('hh:nn'で指定)
$hours_margin = 30; //間隔を指定(分)
$chapters = array('会議室A', '会議室B', '会議室C', '会議室D'); //項目名を配列で定義
$tbl_flg = false; //時間を横軸 → true, 縦軸 → falseにする
$master_key = 'special';
/********** ここまで **********/


/*** ページ読込前の設定部分 ***/

print "a";
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

//Cookie
$my_name = (string)filter_input(INPUT_COOKIE, 'my_name');
$sect = (string)filter_input(INPUT_COOKIE, 'sect');

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
	foreach (array('date', 'my_name', 'sect', 'notes', 'time_st', 'time_end', 'cpt_name', 'kwd') as $v) {
		$$v = (string)filter_input(INPUT_POST, $v);
	}
	$time_st = $date . ' ' . $time_st . ':00'; //開始時間（MySQLのDATETIMEフォーマットへ成形）
	$time_end = $date . ' ' . $time_end . ':00'; //終了時間

	//Cookie
	setcookie('my_name', $my_name, time() + 60 * 60 * 24 * 14); //14日間保存
	setcookie('sect', $sect, time() + 60 * 60 * 24 * 14);

	if( $my_name == '' || $sect == '') { //名前か所属が空欄だったら
		$log1 = '<p>備考・削除キー以外は必須項目です。</p>';
	} elseif( $time_st >= $time_end ) { //開始時間 >= 終了時間の場合
		$log1 = '<p>時間設定が不正のため、登録できませんでした。</p>';
	} else { //正常処理
		$sbm_flg = false; //予約済み時間との重複フラグを設定
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM $wpdb->rsv_timetable
			WHERE time_st BETWEEN %s AND %s
			AND cpt_name = %s",
			$date.' 00:00:00', $date.' 23:59:59', $cpt_name
		) );
		if ( $results ) { foreach ( $results as $value ) { //該当のデータ数繰り返す
			$time1 = strtotime( $value -> time_st ); //該当IDの開始時刻
			$time2 = strtotime( $value -> time_end ); //該当IDの終了時刻
			if ( $time1 <= strtotime( $time_st ) && strtotime( $time_st ) < $time2 ) {
				$sbm_flg = true; //予約済開始時刻 <= 開始時刻 < 予約済終了時刻 ならフラグを立てる
			}
			if ( $time1 < strtotime( $time_end ) && strtotime( $time_end ) <= $time2 ) {
				$sbm_flg = true; //予約済開始時刻 < 終了時刻 <= 予約済終了時刻 ならフラグを立てる
			}
			if ( strtotime( $time_st ) <= $time1 && $time2 <= strtotime( $time_end ) ) {
				$sbm_flg = true; //開始時刻 <= 予約済開始時刻 & 予約済終了時刻 <= 終了時刻 ならフラグを立てる
			}
		} }
		if( $sbm_flg == true ) { //フラグが立ってたら登録できない
			$log1 = '<p>既に予約されているため、この時間帯では登録できません。</p>';
		} else { //登録処理
			$sql_rsl = $wpdb->query( $wpdb->prepare("
				INSERT INTO $wpdb->rsv_timetable
				( name, sect, notes, time_st, time_end, cpt_name, kwd )
				VALUES ( %s, %s, %s, %s, %s, %s, %s )",
				$my_name, $sect, $notes, $time_st, $time_end, $cpt_name, $kwd
			) );
			if ( $sql_rsl == false ) {
				$log1 = '<p>SQLエラー</p>';
			} else {
				$log1 = '<p>登録しました。</p>';
			}
		}
	}

} elseif( isset($_POST['delete']) ) {
	/*** 削除ボタン（キー無）がクリックされた場合 ***/
	$date = (string)filter_input(INPUT_POST, 'date');
	$id = (int)filter_input(INPUT_POST, 'id');
	$sql_rsl = $wpdb->query( $wpdb->prepare(
		"DELETE FROM $wpdb->rsv_timetable WHERE id = %d",
		$id
	) );
	if ( $sql_rsl == false ) {
		$log1 = '<p>削除に失敗しました。</p>';
	} else {
		$log1 = '<p>削除しました。</p>';
	}

} elseif ( isset($_POST['kwd_delete']) ) {
	/*** 削除ボタン（キー有）がクリックされた場合 ***/
	$date = (string)filter_input(INPUT_POST, 'date');
	$id = (int)filter_input(INPUT_POST, 'id');
	$log1 .= "<p>削除キーを入力してください。</p>\n";
	$log1 .= '<form action="" method="post">'."\n";
	$log1 .= '<input type="hidden" name="date" value="'.h($date).'" />'."\n";
	$log1 .= '<input type="hidden" name="id" value="'.h($id).'" />'."\n";
	$log1 .= '<input type="text" name="ipt_kwd" size="10" value="" />'."\n";
	$log1 .= '<input type="submit" name="rgs_delete" value="削除">'."\n";
	$log1 .= "</form>\n";

} elseif( isset($_POST['rgs_delete']) ) {
	/*** キー入力後の削除ボタンがクリックされた場合 ***/
	$date = (string)filter_input(INPUT_POST, 'date');
	$id = (int)filter_input(INPUT_POST, 'id');
	$ipt_kwd = (string)filter_input(INPUT_POST, 'ipt_kwd');
	$kwd = $wpdb->get_var( $wpdb->prepare(
		"SELECT kwd FROM $wpdb->rsv_timetable WHERE id = %d",
		$id
	) );
 	if ( $ipt_kwd === $kwd || $ipt_kwd === $master_key ) {
		$sql_rsl = $wpdb->query( $wpdb->prepare(
			"DELETE FROM $wpdb->rsv_timetable WHERE id = %d",
			$id
		) );
		if ( $sql_rsl == false ) {
			$log1 = '<p>削除に失敗しました。</p>';
		} else {
			$log1 = '<p>削除しました。</p>';
		}
	} else {
		$log1 = '<p>キーワードが間違っているため、削除できません。</p>';
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
$results = $wpdb->get_results( $wpdb->prepare(
	"SELECT *
	FROM $wpdb->rsv_timetable
	WHERE time_st BETWEEN %s AND %s",
	$date.' 00:00:00', $date.' 23:59:59'
) );
if ( $results ) { foreach ($results as $value) { //データ数分繰り返す
	$key1 = null; //エラーキャッチ用にnullを入れておく
	$key2 = null;
	
	$time1 = substr($value->time_st, 11, 5); //開始日時'00:00'抜出
	$key1 = array_search($time1, $hours); //時間配列内の番号	
	$time2 = substr($value->time_end, 11, 5); //終了日時'00:00'抜出
	$key2 = array_search($time2, $hours); //時間配列内の番号
	if ( is_numeric($key1) == false || is_numeric($key2) == false || in_array($value->cpt_name, $chapters) == false	) {
		$log2 .= '<li>'.h($value->cpt_name).'('.h($value->name).','.h($value->sect).') '.$time1.'～'.$time2."</li>\n"; //エラー内容格納
		$err_n++; //エラー件数カウントアップ
	} else {
		//$data_meta['項目名']['開始時間配列番号']へナンバリングしていく
		$data_meta[$value->cpt_name][$key1] = $data_n;
		//必要な情報を格納しておく
		$ar_block[$data_n] = $key2 - $key1; //開始時間から終了時間までのブロック数
		$ar_id[$data_n] = $value->id;
		$ar_name[$data_n] = $value->name;
		$ar_sect[$data_n] = $value->sect;
		$ar_notes[$data_n] = $value->notes;
		$ar_kwd[$data_n] = $value->kwd;
		$data_n++; //データ数カウントアップ
	}
} }

get_header(); ?>

<div id="content">
	<?php while (have_posts()) : the_post(); ?>

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
				$cts = h($ar_name[$data_n]).'（'.h($ar_sect[$data_n]).'）<br />'.h($ar_notes[$data_n]); //htmlエスケープしながら中身成形
				if ( $ar_kwd[$data_n] === '' ) { //削除キー無
					//onsubmitでJavaScriptを呼び出す
					$dlt = '<form action="" method="post" onsubmit="return dltChk()"><input type="hidden" name="date" value="'.$date.'" /><input type="hidden" name="id" value="'.$ar_id[$data_n].'" /><input type="submit" name="delete" value="×"></form>';
				} else { //削除キー有
					//カギ画像付加
					$dlt = '<form action="" method="post"><input type="hidden" name="date" value="'.$date.'" /><input type="hidden" name="id" value="'.$ar_id[$data_n].'" /><input type="submit" name="kwd_delete" value="×"></form><img src="key.gif" width="18" height="18" />';
				}
				//tdの中に出力
				if ( is_user_logged_in() ) {
					$timetable_output .= '<td class="exist"'.$block.'>'.$cts.$dlt.'</td>'; //ログインユーザー
				} else {
					$timetable_output .= '<td class="exist"'.$block.'>'.$cts.'</td>'; //一般ユーザー
				}
			}
		}
	} //横軸for
	$timetable_output .= "</tr>\n";
} //縦軸for
$timetable_output .= "</tbody>\n</table>\n";
echo $timetable_output; //出力
?>
</div><!-- /#timetable_box -->

<?php if(current_user_can('read_private_pages')): //ログインユーザーにだけ出力 ?>
<div id="form_box">
	<h2>登録フォーム</h2>
	<form action="" name="iptfrm" method="post">
		<input type="hidden" name="date" value="<?php echo h($date); ?>" />
		<br />
		<label>名前</label>
		<input type="text" name="my_name" size="10" value="<?php echo h($my_name); ?>" />
		<br />
		<label>所属</label>
		<input type="text" name="sect" size="10" value="<?php echo h($sect); ?>" />
		<br />
		<label>備考</label>
		<input type="text" name="notes" size="10" value="" />
		<br />
		<label>開始時間</label>
		<select name="time_st" onChange="autoPlus(this)">
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
		<label>会議室</label>
		<select name="cpt_name">
		<?php
		foreach ($chapters as $value) {
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
		?>
		</select>
		<br />
		<label>削除キー</label>
		<input type="text" name="kwd" size="10" value="" />
		<br />
		<input type="submit" name="register" value="登録" />
	</form>
</div><!-- /#form_box -->
<?php endif; ?>

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


	<?php endwhile; ?>
</div><!-- #content -->

<?php get_footer(); ?>

