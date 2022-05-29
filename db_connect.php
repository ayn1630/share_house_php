<?php
require_once __DIR__.'/config.php';
require_once _ROOT_DIR.'/vendor/autoload.php';



//データベース接続
function db_connect() {
    try {
        $pdo = new PDO(DSN, DB_USER, DB_PASS,
                array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
                )
        );
    } catch (Exception $e) {
        exit('データベース接続に失敗しました。' . $e->getMessage() . PHP_EOL);
    }

    return $pdo;
}

// 

//$sql = "select distinct roomnumber from houseData";
//$stmh = $pdo->query($sql);
//
//$optionTags_roomnumber = "";
//while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
//    $optionTags_roomnumber .= '<option value="'.h($row['roomnumber'], ENT_QUOTES).'">'.h($row['roomnumber'], ENT_QUOTES)."</option>\n";
//}

//各種テーブルの作成
function create_preuser_table() {
    try {
        $pdo = db_connect();
        $pdo->exec("create table if not exists preuser(
            id int not null auto_increment primary key,
            userName varchar(50) ,
            email varchar(255) ,
            password varchar(255) ,
            roomPass varchar(255) ,
            linkPass varchar(255) ,
            created timestamp not null default current_timestamp
            )");
    } catch (Exception $e) {
        exit('preuserテーブルの作成に失敗しました。' . $e->getMessage() . PHP_EOL);
    }
}

function create_user_table() {
    try {
        $pdo = db_connect();
        $pdo->exec("create table if not exists user(
            userId int not null auto_increment primary key,
            userName varchar(50) ,
            email varchar(255) unique,
            password varchar(255) ,
            created timestamp not null default current_timestamp
            )");
    } catch (Exception $e) {
        exit('userテーブルの作成に失敗しました。' . $e->getMessage() . PHP_EOL);
    }
}


////////////////////////////////////////////////////////////////////////////////////////houseId削除するか確認。
function create_facilityTimetable() {
    try {
        $pdo = db_connect();
        $pdo->exec("create table if not exists facilityTimetable(
            facilityTimetableId int not null auto_increment primary key,
            userId int(255) collate utf8mb4_general_ci,
            houseId int(255) collate utf8mb4_general_ci,
            roomNumber int(255) collate utf8mb4_general_ci,
            facilityId int(255) collate utf8mb4_general_ci,
            timeStart datetime,
            timeEnd datetime,
            notes varchar(255) collate utf8mb4_general_ci,
            created timestamp not null default current_timestamp);
            )");
    } catch (Exception $e) {
        exit('facilityTimetableテーブルの作成に失敗しました。' . $e->getMessage() . PHP_EOL);
    }
}

function create_garbageDisposal() {
    try {
        $pdo = db_connect();
        $pdo->exec("create table if not exists garbageDisposal(
            roomNumber int(255) collate utf8mb4_general_ci,
            day int(255) collate utf8mb4_general_ci,
            houseId int(255) collate utf8mb4_general_ci,
            userId int(255) collate utf8mb4_general_ci,
            created timestamp not null default current_timestamp
            )");
    } catch (Exception $e) {
        exit('garbageDisposalテーブルの作成に失敗しました。' . $e->getMessage() . PHP_EOL);
    }
}

 

//部屋のパスワードチェック
//function roompass_check($input_roompass) {
//    $post_roompass = h($_POST['roompass']);
//    try {
//        $pdo = db_connect();
//        $stmt = $pdo->prepare('select * from houseData where housename = ? and roomnumber = ?');
//        $stmt->bindValue(1, $post_housename, PDO::PARAM_STR);
//        $stmt->bindValue(2, $post_roomnumber, PDO::PARAM_STR);
//        $stmt->execute();
//        $row = $stmt->fetch(PDO::FETCH_ASSOC);
//    } catch (\Exception $e) {
//        exit($e->getMessage() . PHP_EOL);
//    }
//
//    if ($row['roompass'] != $input_roompass) {
//        echo "4";
//        echo '処理できませんでした。';
//        echo "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
//        return false;
//    }
//}


//部屋のパスワードチェック
function roompass_check($input_roompass) {
    try {
        $pdo = db_connect();
        $sql = "select roomPass from room";
        $stmt = $pdo->query($sql);
        $allRoomPassData = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($allRoomPassData as $value){
            if (password_verify($input_roompass, $value)){
                $result = $value;
                $stmt = $pdo->prepare("select * from room where roomPass = ?");
                $stmt->bindValue(1, $result, PDO::PARAM_STR);
                $stmt->execute();
                $userId = $stmt->fetch(PDO::FETCH_ASSOC);
                //既にuserIdが登録されている部屋に重複登録されないようにする              
                if ($userId['userId'] == "NULL" || $userId['userId'] == "") {   
                    return $result;
                }
            }
        }
        $message = "処理できませんでした。";
        $message .= "<br><a href='".LOGIN."'>こちら</a>からもう一度やりなおしてください。";
        echo $message;
        exit;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }   
}




//複数登録されないようにブロック
//function duplicate_regist_block() {
//    $post_housename = h($_POST['housename']);
//    $post_roomnumber = h($_POST['roomnumber']);
//    $unique_sign = $post_housename . $post_roomnumber;
//    try {
//        $pdo = db_connect();
//        $stmt = $pdo->prepare('select * from userData where unique_sign = ?');
//        $stmt->bindValue(1, $unique_sign, PDO::PARAM_STR);
//        $stmt->execute();
//        $row = $stmt->fetch(PDO::FETCH_ASSOC);
//        if ($row == true) {
//            echo '処理できませんでした。';
//            echo "3";
//            echo "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
//            return false;
//        }
//    } catch (\Exception $e) {
//        exit($e->getMessage() . PHP_EOL);
//    }
//}

function regist_preuser($username, $email, $password, $roomPass) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $linkPass = hash('sha256', uniqid(rand(), 1));
    try {
        $pdo = db_connect();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("insert into preuser(userName, email, password, roomPass, linkPass) value(?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $roomPass, $linkPass]);
        $stmt = $pdo->prepare("select * from preuser where email = ?");
        $stmt->execute([$email]);
        $pdo->commit();
        $userdata = $stmt->fetch(PDO::FETCH_ASSOC);
        mail_to_preuser($userdata);
        $message  = "登録されたメールアドレスへ確認のためのメールを送信しました。<br>";
        $message .= "メール本文に記載されているURLにアクセスして登録を完了してください。<br>";
        return $message;
    } catch (\Exception $e) {
        $pdo->rollBack();
        $message = "処理できませんでした。";
        $message .= "<br><a href='".LOGIN."'>こちら</a>からもう一度やりなおしてください。";
        exit($e->getMessage() . PHP_EOL);
        return $message;
    } 
}

function mail_to_preuser($userdata) {
    $path = pathinfo(_SCRIPT_NAME)['dirname'];
    $to = $userdata['email'];
    $subject = "会員登録の確認";
//    $mes = "テストです。";
    $mes =<<<EOM
{$userdata['userName']}様

会員登録ありがとうございます。
下のリンクにアクセスして会員登録を完了してください。

http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$path}premember.php?username={$userdata['email']}&link_pass={$userdata['linkPass']}

このメールに覚えがない場合はメールを削除してください。


--
システムより自動送信
※このメールに返信はできません。
※10分でリンクは無効となります。


EOM;
    //改行を反映
    $mes = nl2br($mes);

    //メール送信
    $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
    $transport->setUsername('送信元メールアドレスを入力');
    $transport->setPassword('送信元メールアドレスのパスワードを入力');

    //HTML 形式のメール本文
    $html = $mes;
    // テキスト形式のメール本文
    $text = $mes;
    $message = new Swift_Message();
    $message->setSubject('会員登録の確認');
    $message->setFrom(['送信元メールアドレスを入力' => '株式会社シェアハウス運営(仮)']);
    $message->setTo([$to]);
    // メール本文に HTML パートをセット
    $message->setBody($html, 'text/html');
    // メール本文にテキストパートをセット
    $message->addPart($text, 'text/plain');
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
    
}

function mail_chenge($input_mail, $session_name) {
    $path = pathinfo(_SCRIPT_NAME)['dirname'];
    $to = $input_mail;
    $subject = "メールアドレスの変更";
//    $mes = "テストです。";
    $mes =<<<EOM
{$session_name}様

株式会社シェアハウス運営(仮)アプリより、
メールアドレスの変更がリクエストされました。

下のリンクにアクセスしてメールアドレスの確認を完了してください。

http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$path}mypage.php?username={$input_mail}&link_pass={$userdata['linkPass']}

このメールに覚えがない場合はメールを削除してください。


--
システムより自動送信
※このメールに返信はできません。
※10分でリンクは無効となります。


EOM;
    //改行を反映
    $mes = nl2br($mes);

    //メール送信
    $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
    $transport->setUsername('送信元メールアドレスを入力');
    $transport->setPassword('送信元メールアドレスのパスワードを入力');

    //HTML 形式のメール本文
    $html = $mes;
    // テキスト形式のメール本文
    $text = $mes;
    $message = new Swift_Message();
    $message->setSubject('会員登録の確認');
    $message->setFrom(['送信元メールアドレスを入力' => '株式会社シェアハウス運営(仮)']);
    $message->setTo([$to]);
    // メール本文に HTML パートをセット
    $message->setBody($html, 'text/html');
    // メール本文にテキストパートをセット
    $message->addPart($text, 'text/plain');
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
    
}

    
function check_preuser($usermail, $link_pass) {
    $data = [];
    $pdo = db_connect();
    try {
        //作成から10分後のデータは削除
        $sql = "delete from preuser where DATE_ADD(created, INTERVAL 10 MINUTE) < NOW()";
        $stmt = $pdo->query($sql);
        $stmt->execute();
        $sql2 = "select * from preuser where email = :email AND linkPass = :link_pass limit 1";
        $stmt = $pdo->prepare($sql2);
        $stmt->bindValue(':email', $usermail, PDO::PARAM_STR);
        $stmt->bindValue(':link_pass', $link_pass, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $message = "このURLは無効です。<br>";
        $message .= "新規会員登録は<a href='".LOGIN."'>こちら</a>。<br>";
        echo $message;
        exit($e->getMessage() . PHP_EOL);
    }
    return $data;
}

//userテーブルにユーザー登録実行
function delete_preuser_and_regist_user($userdata) {
    try {
        //preuserテーブルから削除
        $pdo = db_connect();
        $pdo->beginTransaction();
        $sql = "delete from preuser where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $userdata['id'], PDO::PARAM_INT);
        $stmt->execute();
        //userテーブルに本登録
        $stmt = $pdo->prepare("insert into user(userName, email, password) value(?, ?, ?)");
        $stmt->execute([$userdata['userName'], $userdata['email'], $userdata['password']]);
        $stmt = $pdo->prepare("select * from user where email = ?");
        $stmt->execute([$userdata['email']]);
        $userId = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("update room set userId = ".$userId['userId']." where roomPass=?");
        $stmt->bindValue(1, $userdata['roomPass'], PDO::PARAM_STR);
        $stmt->execute();
        $pdo->commit();
        $message = "登録完了";
        $message .= "<br><a href='".LOGIN."'>ログインページに戻る</a>";
        return $message;
    } catch (Exception $e) {
        //うまくいかなかったら処理を取り消す
        $pdo->rollBack();
        $message = "処理できませんでした。";
        $message .= "<br><a href='".LOGIN."'>こちら</a>からもう一度やりなおしてください。";
        exit($e->getMessage() . PHP_EOL);
        return $message;
    }
  
}

//userテーブルの中に、入力されたemailデータがある行を$rowに格納
function search_user_data($input) {
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("select * from user where email = ?");
        $stmt->execute([$input]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}


function search_admin_data($input){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("select * from admin where email = ?");
        $stmt->execute([$input]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

function house_search($data){
    $pdo = db_connect();
    $sql = "select * from room where userId = ".$data;
    $stmt = $pdo->query($sql);
    $result =  $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}


function regist_facilityTimetable($login_id, $roomnumber) {
    $date = date('Y-m-d');
    //フォームに入力された情報を各変数へ格納
//    foreach (array('date', 'my_name', 'notes', 'timeStart', 'timeEnd', 'item_name') as $v) {
//        $$v = h($_POST["'".$v."'"]);
//    }
    foreach (array('date', 'notes', 'timeStart', 'timeEnd', 'facilityId') as $v) {
        $$v = (string) filter_input(INPUT_POST, $v);
    }
    $timeStart = $date . ' ' . $timeStart . ':00'; //開始時間（MySQLのDATETIMEフォーマットへ成形）
    $timeEnd = $date . ' ' . $timeEnd . ':00'; //終了時間
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            'INSERT INTO facilityTimetable 
		( userId, roomNumber, facilityId, timeStart, timeEnd, notes)
		VALUES ( :userId, :roomNumber, :facilityId, :timeStart, :timeEnd, :notes)'
    );
    $stmt->bindValue(':userId', $login_id, PDO::PARAM_STR);
    $stmt->bindValue(':roomNumber', $roomnumber, PDO::PARAM_STR);
    $stmt->bindValue(':facilityId', $facilityId, PDO::PARAM_STR);
    $stmt->bindValue(':timeStart', $timeStart, PDO::PARAM_STR);
    $stmt->bindValue(':timeEnd', $timeEnd, PDO::PARAM_STR);
    $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
    $rsl = $stmt->execute(); //実行
    if ($rsl == false) {
        $alart_msg = "登録に失敗しました";
        echo javascript_alart($alart_msg);

//				$log1 = '<p>登録に失敗しました。</p>';
    } else {
        $alart_msg = "登録しました。";
        echo javascript_alart($alart_msg);

//				$log1 = '<p>登録しました。</p>';
    }
}




function confirm_reserve_facilityTimetable($userId) {
    $date = date('Y-m-d'); 
    foreach (array('date', 'timeStart', 'timeEnd', 'facilityId') as $v) {
        $$v = (string) filter_input(INPUT_POST, $v);
    }
    $timeStart = $date . ' ' . $timeStart . ':00'; //開始時間（MySQLのDATETIMEフォーマットへ成形）
    $timeEnd = $date . ' ' . $timeEnd . ':00'; //終了時間
    $pdo = db_connect();
    $results = $pdo->prepare(
            "SELECT *
            FROM facilityTimetable 
            WHERE (timeStart BETWEEN :date1 AND :date2) AND facilityId = :facilityId"
    );
    $results->bindValue(':date1', $date . ' 00:00:00', PDO::PARAM_STR);
    $results->bindValue(':date2', $date . ' 23:59:59', PDO::PARAM_STR);
    $results->bindValue(':facilityId', $facilityId, PDO::PARAM_STR);
    $results->execute();
    
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            "SELECT *
            FROM facilityTimetable 
            WHERE userId = :userId AND facilityId = :facilityId"
    );
    $stmt->bindValue(':facilityId', $facilityId, PDO::PARAM_STR);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    $results2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array($results, $results2);
}



function delete_facilityTimetable($login_id, $login_mail, $date, $id){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT userId FROM facilityTimetable WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

    if ($stmt) { 
        foreach ($stmt as $value) {
            $userId = $value['userId'];
        }
    }
    $admin_row = search_admin_data($login_mail);

    if (isset($admin_row['email']) || (isset($userId) && $userId == $login_id)) {

        $sql = $pdo->prepare("DELETE FROM facilityTimetable WHERE id = :id");
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $rsl = $sql->execute(); //実行
        if ($rsl == false) {
            $alart_msg = "削除に失敗しました。";
            echo javascript_alart($alart_msg);
        } else {
            $alart_msg = "削除しました。";
            echo javascript_alart($alart_msg);
        }
    } else {
        $alart_msg = "ユーザーが違うため、削除できません。";
        echo javascript_alart($alart_msg);
    }
}

function select_all_data_facilityTimetable($input, $date){
    $pdo = db_connect();
    $results = $pdo->prepare(
            "SELECT *
            FROM facilityTimetable 
            WHERE (timeStart BETWEEN :date1 AND :date2) AND facilityId in (".implode(",",$input).");");
    $results->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
    $results->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
    $results->execute();
    return $results;

}

function select_Name_facilityTimetable($input){
    $pdo = db_connect();
    $sql = 
            "SELECT facilityId, facilityName
            FROM facility 
            WHERE facilityId in (".implode(",",$input).")";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;

}


function search_facilityId($input){
        try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("select facilityId from facility where houseId = ?");
        $stmt->execute([$input]);
        $facilityIdList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $facilityIdList;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}


function search_bathId($input){
        try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("select facilityId from facility where houseId = ? AND facilityName = '浴 室'");
        $stmt->execute([$input]);
        $bathId = $stmt->fetch(PDO::FETCH_COLUMN);
        return $bathId;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

function search_washId($input){
        try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("select facilityId from facility where houseId = ? AND facilityName = '洗濯機'");
        $stmt->execute([$input]);
        $washId = $stmt->fetch(PDO::FETCH_COLUMN);
        return $washId;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

function select_all_data_facilityTimetable2($input, $date){
    $today = new DateTime('now');
    $today = $today->format('Y-m-d H:i:s');
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            "SELECT *
            FROM facilityTimetable 
            WHERE (timeStart BETWEEN :date1 AND :date2) AND facilityId = " .$input. " AND timeEnd >= '$today' order by timeStart");
    $stmt->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;

}


function select_all_data_facilityTimetable3($input, $date){
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            "select 
                a.id, 
                a.userId, 
                a.roomNumber, 
                b.facilityName, 
                a.timeStart, 
                a.timeEnd, 
                a.notes from facilityTimetable a inner join facility b on a.facilityId = b.facilityId 
            WHERE (timeStart BETWEEN :date1 AND :date2) AND a.facilityId in (".implode(",",$input).") order by timeStart");
    $stmt->bindValue(':date1', $date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':date2', $date.' 23:59:59', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;

}


function seach_houseName($input){
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            "select houseName from house where houseId = ?");
    $stmt->bindValue(1, $input, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results;
}

function bbs_all_data($houseId){
    try {
        $pdo = db_connect();
        // 『名前|本文|日付|曜日(数値)|時間』 の形式で、時間で逆ソートして取得
        $stmt = $pdo->prepare("select name, msg, id, date_format(date,'%y/%m/%d') as date, date_format(date, '%w') as weekday, time_format(date, '%H:%i') as time from bbs where houseId = ? order by timestamp(date) desc");
        $stmt->bindValue(1, $houseId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $Exception) {
        die("エラー：" . $Exception->getMessage());
    }
    // 出力用の配列にとっておく
    $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 切断
    $pdo = null;
    
    return $all;
}

function regist_bbs($name, $message, $userId, $houseId){
    try {
        $pdo = db_connect();
        $pdo->beginTransaction();
        // 名前が入力されてないときはデフォルト値になるようにする
        $sql = "insert into bbs(name, msg, userId, houseId) values(" .
                (($name == null) ? "default(name)" : ":name") .
                ", :msg, :userId, :houseId)";
        $stmt = $pdo->prepare($sql);
        // 名前が入力されたときのみバインドする
        if ($name) {
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        }
        // 本文が入力されていないときはNULL型で、入力されたときはSTR型でバインドする
        $stmt->bindValue(':msg', $message, ($message == null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':houseId', $houseId, PDO::PARAM_INT);
        $stmt->execute();
        $pdo->commit();
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            $message = "処理できませんでした。";
            $message .= "<br><a href='index.php'>こちら</a>からもう一度やりなおしてください。";
            exit($Exception->getMessage() . PHP_EOL);
            return $message;
        }

    // MySQL切断
    $pdo = null;
    
}

function display_bbs($houseId){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT roomNumber , GROUP_CONCAT(DISTINCT CONCAT(day, '日') ORDER BY day SEPARATOR ',　') as day_all "
                . "FROM garbageDisposal "
                . "WHERE houseID = ? AND created between date_format(now(), '%Y-%m-01') and last_day(now()) "
                . "GROUP BY roomNumber");
        $stmt->execute([$houseId]);
        $dispoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dispoData;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

function delete_bbs($userId, $id){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("delete from bbs where userId = ? AND id = ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}


function regist_garbageDisposal($roomNumber, $houseId, $userId, $day ,$days){
    if (!in_array($day, $days)){
        $alart_msg = "登録に失敗しました";
        return $alart_msg;
    }
    $day = $day;
    $pdo = db_connect();
    $stmt = $pdo->prepare(
            'INSERT INTO garbageDisposal
		(roomNumber, houseId, userId, day)
		VALUES (:roomNumber, :houseId, :userId, :day)'
    );
    $stmt->bindValue(':roomNumber', $roomNumber, PDO::PARAM_STR);
    $stmt->bindValue(':houseId', $houseId, PDO::PARAM_STR);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':day', $day, PDO::PARAM_INT);
    $rsl = $stmt->execute(); //実行
    
    if ($rsl == false) {
        $alart_msg = "登録に失敗しました";
        return $alart_msg;

    } else {
        $alart_msg = "登録しました。";
        return $alart_msg;

    }
}

function display_garbageDisposal($houseId){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT roomNumber , GROUP_CONCAT(DISTINCT CONCAT(day, '日') ORDER BY day SEPARATOR ',　') as day_all "
                . "FROM garbageDisposal "
                . "WHERE houseID = ? AND created between date_format(now(), '%Y-%m-01') and last_day(now()) "
                . "GROUP BY roomNumber");
        $stmt->execute([$houseId]);
        $dispoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dispoData;
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

function correct_garbageDisposal($day, $userId){
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("delete from garbageDisposal where day = ? AND userId = ? AND created between date_format(now(), '%Y-%m-01') and last_day(now()) ");
        $stmt->bindValue(1, $day, PDO::PARAM_INT);
        $stmt->bindValue(2, $userId, PDO::PARAM_STR);
        $stmt->execute();
    } catch (\Exception $e) {
        exit($e->getMessage() . PHP_EOL);
    }
}

        

    





