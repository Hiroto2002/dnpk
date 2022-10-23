<?php
error_reporting(0);
session_start();
//sessionとcookieの有効期限を一日に 
// ini_set("session.gc_maxlifetime",86400);
// ini_set("session.cookie_lifetime",8640);

// ユーザーを追加する
if (isset($_POST["user"])) {
    $_SESSION["user"] =  $_POST["user"];
}

// ログアウト
if (isset($_GET["logout"])) {
    $_SESSION =  array();
    $url = 'http://localhost/%E3%81%A9%E3%82%93%E3%81%B7%E3%81%8F/index.php?logout=1';
    // $url = 'https://dnpk.jp/test/index.php?logout=1';
    $url = strtok($url, '?');
    header("Location: $url");
    exit();
    // print("<br/><br/><br/><br/><br/><br/><br/><br/>");
    // print$_GET["logout"];
}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <option value="a">a</option>
                <option value="b">b</option>
                <option value="c">c</option>
            </select>
            <input type="submit" value="送信" />
        </form>
    <?php endif; ?>
    <!-- <footer style="text-align:center; font-size:20px; line-height: 0.5;">
        <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p>
    </footer> -->

</body>
</html>