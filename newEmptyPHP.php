<?php
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//表示させる年月を設定　↓これは現在の月
$year = date('Y');
$month = date('06');

//月末日を取得
$end_month = date('t', strtotime($year.$month.'01'));
//1日の曜日を取得
$first_week = date('w', strtotime($year.$month.'01'));
//月末日の曜日を取得
$last_week = date('w', strtotime($year.$month.$end_month));

$aryCalendar = [];
$j = 0;

//1日開始曜日までの穴埋め
for($i = 0; $i < $first_week; $i++){
    $aryCalendar[$j][] = '';

}


//1日から月末日までループ
for ($i = 1; $i <= $end_month; $i++){
    //日曜日まで進んだら改行
    if(isset($aryCalendar[$j]) && count($aryCalendar[$j]) === 7){
        $j++;
    }
    $aryCalendar[$j][] = $i; 
   
}

//月末曜日の穴埋め
for($i = count($aryCalendar[$j]); $i < 7; $i++){
    $aryCalendar[$j][] = '';
}

echo "<pre>";

print_r($aryCalendar);

echo "</pre>";

$aryWeek = ['日', '月', '火', '水', '木', '金', '土'];
?>

<table class="calendar">
    <!-- 曜日の表示 -->
    <tr>
    <?php foreach($aryWeek as $week){ ?>
        <th><?php echo $week ?></th>
    <?php } ?>
    </tr>
    <!-- 日数の表示 -->
    <?php foreach($aryCalendar as $tr){ ?>
    <tr>
        <?php foreach($tr as $td){ ?>
            <?php if($td != date('j')){ ?>
                <td><?php echo $td ?></td>
            <?php }else{ ?>
                <!-- 今日の日付 -->
                <td class="today"><?php echo $td ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <?php } ?>
</table>


<style>
    table.calendar{
    width: 70%;
    height:70%;
}

table.calendar th, table.calendar td{
    padding: 8px;
    background-color: #FFF;
    text-align: center;
    border: 1px solid #CCC;
}

/* 行の最初のtdは日曜日なため赤く */
table.calendar th:first-of-type, table.calendar td:first-of-type{
    background-color: #ffefef;
    color: #FF0000;
    font-weight: bold;
}

/* 行の最後のtdは土曜日なため青く */
table.calendar th:last-of-type, table.calendar td:last-of-type{
    background-color: #ededff;
    color: #0000FF;
    font-weight: bold;
}

/* 今日の日付 */
table.calendar td.today{
    background-color: #fbffa3;
    font-weight: bold;
}
</style>