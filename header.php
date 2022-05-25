<?php
require_once __DIR__.'/config.php'; 



//if (isset($login_mail)) {
//    "ようこそ" . $login_name . "さん<br>";
//    echo "<a href='logout.php'>ログアウトはこちら。</a><br>";
//}
//
//if ($login_mail == ADMIN_MAIL) {
//    echo "<a href='admin0.php'>管理メニューはこちら</a><br>";
//}

if (!$_SESSION['EMAIL'] > 0) {
    header("Location:" .LOGIN. "");
    return false;
}

$houseName = seach_houseName($_SESSION['HOUSEID']);

//if ($login_mail == ADMIN_MAIL)  {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">-->
        <title>メインページ</title>
    </head>
    <body>
        <div id="container">
            <header>
                <ul class="topmenu">        
                    <li class="topmenu_left"><?=h($_SESSION['NAME'])?>でログイン中</a></li>
                    <li class="topmenu_center"><?=$houseName['houseName']?></a>
                    <li class="topmenu_right"><a href="<?=LOGOUT?>">ログアウト</a></li>
                </ul>
                <ul class="ddmenu">        
                    <li><a href="<?=MAINMENU?>">メインメニュー</a></li>
                    <li><a href="#">イベント</a>
                    </li>
                    <li><a href="<?=BBS?>">掲示板</a>
                    </li>
                    <li><a href="<?=GARBAGE?>">ごみ出し表</a>
                    </li>
                    <li><a href="#">その他</a>
                        <ul>
                            <li><a href="<?=MYPAGE?>">マイページ</a></li>
                            <li><a href="#">ハウス情報</a></li>
                            <li><a href="<?=CONTACT?>">お問合せ</a></li>
                        </ul>
                    </li>
                </ul>
            </header>




<style>
    html{
        min-height: 100%;
        position: relative;
    }
    body {
        margin: 0;
        padding: 0;
        margin-top: 128px;
        margin-bottom: 64px;
        background-color: linen;
    }
    
    #container{
        padding-left: 80px;
        padding-right: 80px;
    }

    header{
        /*z-index: 10;*/
        top: 0;
        left: 0;
        height: 64px;
        position: fixed;
        background-color: #333;
        width: 100%;
        text-align: center;
        /*z-index: 2;*/
        /*box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.25);*/
    }

    

    .loginck{
        float: left;
        overflow:hidden;
        list-style:none;
        margin: 0 auto;
        z-index: 20;
        display: inline-block;
        padding: 23px 0px 0px 20px;
        color: linen;


    }
    .housename{
        float: right;
        overflow:hidden;
        list-style:none;
        margin: 0 auto;
        z-index: 20;
        display: inline-block;
        padding: 23px 20px 0px 0px;
        color: linen;
    }

    nav{
        margin: 0 auto;
        text-align: center;

    }

    footer{
        position: absolute;
        bottom: 0;
        left: 0;
        background-color: #333;
        color: #f6f6f6;
        width: 100%;
        text-align: center;
        height: 64px;
        /*z-index: 2;*/
        /*box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.25);*/
    }

    /* -------------------- */
    /* ▼メニューバーの装飾 */
    /* -------------------- */
    ul.ddmenu {
        margin: 0px;               /* メニューバー外側の余白(ゼロ) */
        padding: 0px 0px 0px 15px; /* メニューバー内側の余白(左に15px) */
        background-color: #696969; /* バーの背景色(濃い赤色) */
        margin-top: 0px;
        font-size: 90%;
    }
    
    ul.topmenu {
        margin: 0px;               /* メニューバー外側の余白(ゼロ) */
        padding: 0px 15px 0px 15px; /* メニューバー内側の余白(左に15px) */
        background-color: #333; /* バーの背景色(濃い赤色) */
        color: white;
    }
    
    ul.topmenu li {
        /*width: 125px;           メニュー項目の横幅(125px) */
        display: inline-block; /* ★横並びに配置する */
        list-style-type: none; /* ★リストの先頭記号を消す */
        position: relative;    /* ★サブメニュー表示の基準位置にする */
        width: 33%; 
        line-height: 50px;
        color: white; 
        font-weight: bold; 
    }
    
    ul.topmenu li.topmenu_left {
        text-align: left;
        
    }
    
    ul.topmenu li.topmenu_center {
        
    }
    ul.topmenu li.topmenu_right {
        text-align: right;
    }
    
    ul.topmenu a{
        color: white;
        line-height: 40px;  
        text-decoration: none;
        font-weight: bold;
        display: block;
    }

    /* -------------------------- */
    /* ▼メインメニュー項目の装飾 */
    /* -------------------------- */
    ul.ddmenu li {
        width: 125px;          /* メニュー項目の横幅(125px) */
        display: inline-block; /* ★横並びに配置する */
        list-style-type: none; /* ★リストの先頭記号を消す */
        position: relative;    /* ★サブメニュー表示の基準位置にする */
    }
    ul.ddmenu a {
        background-color: #696969; /* メニュー項目の背景色(濃い赤色) */
        color: white;              /* メニュー項目の文字色(白色) */
        line-height: 40px;         /* メニュー項目のリンクの高さ(40px) */
        text-align: center;        /* メインメニューの文字列の配置(中央寄せ) */
        text-decoration: none;     /* メニュー項目の装飾(下線を消す) */
        font-weight: bold;         /* 太字にする */
        display: block;            /* ★項目内全域をリンク可能にする */
    }
    ul.ddmenu a:hover {
        background-color: #ffc0cb; /* メニュー項目にマウスが載ったときの背景色(淡いピンク) */
        color: #000000;            /* メニュー項目にマウスが載ったときの文字色(濃い赤色) */
    }
    
    ul.topmenu a:hover {
   
        color: #87cefa;            /* メニュー項目にマウスが載ったときの文字色(濃い赤色) */
    }

    /* ---------------------------------- */
    /* ▼サブメニューがある場合に開く処理 */   /* ※サブメニューが1階層しか存在しない場合の記述 */
    /* ---------------------------------- */
    ul.ddmenu li:hover ul {
        display: block;      /* ★マウスポインタが載っている項目の内部にあるリストを表示する */
    }

    /* -------------------- */
    /* ▼サブメニューの装飾 */
    /* -------------------- */
    ul.ddmenu ul {
        margin: 0px;         /* ★サブメニュー外側の余白(ゼロ) */
        padding: 0px;        /* ★サブメニュー内側の余白(ゼロ) */
        display: none;       /* ★標準では非表示にする */
        position: absolute;  /* ★絶対配置にする */
    }

    /* ------------------------ */
    /* ▼サブメニュー項目の装飾 */
    /* ------------------------ */
    ul.ddmenu ul li {
        width: 135px;               /* サブメニュー1項目の横幅(135px) */
        border-top: 1px solid pink; /* 項目上側の枠線(ピンク色で1pxの実線) */
    }
    ul.ddmenu ul li a {
        line-height: 35px;     /* サブメニュー1項目の高さ(35px) */
        text-align: left;      /* 文字列の配置(左寄せ) */
        padding-left: 5px;     /* 文字列前方の余白(5px) */
        font-weight: normal;   /* 太字にはしない */
    }
    ul.ddmenu ul li a:hover {
        background-color: #ffb6c1; /* サブメニュー項目にマウスが載ったときの背景色(淡い黄色) */
        color: #005500;            /* サブメニュー項目にマウスが載ったときの文字色(濃い緑色) */
    }

</style>
