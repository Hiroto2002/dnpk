<?php
error_reporting(0);
session_start();
//sessionとcookieの有効期限を一日に 
ini_set("session.gc_maxlifetime",14400000);
ini_set("session.cookie_lifetime",8640);

require_once 'DbManager.php';

// ユーザーを追加する
if (isset($_POST["user"])) {
    $_SESSION["user"] =  $_POST["user"];
}

// ログアウト
if (isset($_GET["logout"])) {
    $_SESSION =  array();
    $url = 'http://192.168.15.20/dnpk/index.php?logout=1';
    // $url = 'https://dnpk.jp/test/index.php?logout=1';
    $url = strtok($url, '?');
    header("Location: $url");
    exit();
    // print("<br/><br/><br/><br/><br/><br/><br/><br/>");
    // print$_GET["logout"];
}
$day = date("Y-m-d");
try{
    $staffs = getAllStaff($day);
    // print_r($staffs);
    // exit;
}catch(PDOException $e){
    exit($e->getMessage());
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>どんぷく OES ホーム画面</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="viewport" content="width=device-width"> -->
    <link rel="manifest" href="manifest.json">
    <link rel="manifest" href="manifest.webmanifest" />
    <script async src="https://cdn.jsdelivr.net/npm/pwacompat" crossorigin="anonymous"></script>

    <!-- スマホにショートカットを作れるようになるa -->
    <script>
    window.addEventListener('load', function() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register("pwa.js");
        }
    });
    </script>
</head>

<script>
/* ピッチインピッチアウトによる拡大縮小を禁止 */
document.documentElement.addEventListener('touchstart', function(e) {
    if (e.touches.length >= 2) {
        e.preventDefault();
    }
}, {
    passive: false
});
/* ダブルタップによる拡大を禁止 */
var t = 0;
document.documentElement.addEventListener('touchend', function(e) {
    var now = new Date().getTime();
    if ((now - t) < 350) {
        e.preventDefault();
    }
    t = now;
}, false);
</script>

<body>
    <header>
        <p class="title">どんぷく オーダーエントリー</p>
    </header>
    <?php if (isset($_SESSION["user"])) : ?>
    <div class="widget">
        <div class="widget-list">
            <a href="registercoming.php?p=1">来店登録</a>
        </div>
        <div class="widget-list">
            <a href="order_con.php#tyumonmati">注文待ちリスト</a>
        </div>
        <div class="under">
            <div class="widget-list">
                <a href="order_con.php#tyumonzumi">注文済みリスト</a>
            </div>
            <div class="widget-list">
                <a href="order_con.php#teikyozumi">提供済みリスト</a>
            </div>
            <div class="widget-list">
                <a href="administrator.php">管理者</a>
            </div>
            <div class="widget-list">
                <a href="index.php?logout=1">ログアウト</a>
            </div>
        </div>

        <!-- <div class="widget-list">
                    <button id="InstallBtn" class="installbotton" style="display:none;">
                    アプリをインストールする
                    </button>
                </div>
                <script src="pwainnstall.js"></script> -->
    </div>
    <?php else : ?>
    <div class="login">
        <p>ユーザーを選択してください</p>
    </div>
    <form action="" method="post" class="user">
        <select name="user">
            <option value="242">前田</option>
            <?php foreach($staffs as $value):?>
            <option value="<?php print_r($value["stf_ID"]);?>"><?php print_r($value["stf_Name"])?></option>
            <?php 
            endforeach;
            ?>
        </select>
        <input type="submit" value="送信" />
    </form>
    <?php endif; ?>
    <!-- <footer style="text-align:center; font-size:20px; line-height: 0.5;">
        <p style="color: rgb(255, 255, 255);">Copyright c DNPK.JP All Rights Reserved.</p>
    </footer> -->
</body>

</html>