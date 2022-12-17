<?php
ini_set('display_errors', 0);
session_start();
$user = $_SESSION["user"];
$_SESSION = array();
$_SESSION["user"] = $user;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>来店客状況</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order_con.css">
    <script src="./js/JQuery.js" type="text/javascript"></script>

    <!-- 削除するか確認のダイアログを出す関数 -->
    <script>
        function MoveCheck($str) {
            var res = confirm("オーダー番号" + $str + "を削除しますか？");
            if (res == true) {
                // 再度確認
                var res = confirm("本当に削除しますか？");
                if (res == true) {
                    //OKなら削除処理に進む
                } else {
                    // キャンセルなら処理を中断する
                    return false;
                }
            } else {
                // キャンセルなら処理を中断する
                return false;
            }
        }
        $(function(){
            $(".reload").on("click",function(){
                location.reload()
            })
        })
    </script>
    <!-- 削除するか確認のダイアログを出す関数 -->
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
    setInterval(() => {
        location.reload()
    }, 60000);
</script>

<body>
    <header>
        <p class="title">来店客状況</p>
        <p class="back"><a href="./index.php">< 戻る</a></p>
        <a href="registercoming.php?p=1" class="register">来店登録</a>
    </header>
    <div class="widget">
        <ol class="widget-list" id="teikyozumi">
            <li style="text-align: left;">
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li>
            <hr size="3px" color="#888">
            <?php
            // ブラウザでエラー確認が出来るようにします
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require_once 'DbManager.php';
            $pdo = getDb();
            $sql = 'SELECT * FROM t_d_order_handy where odh_situation=3';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=2&table_No={$product['odh_Tbl_No']}' class='widget-list-link2'>席変更</a>\n";
                echo "<a href='cart.php?odh_No={$product['odh_No']}' class='widget-list-link2'>注変更</a>\n";
                echo "<a href='order.php?odh_Tbl_No={$product['odh_Tbl_No']}&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&situ=add' class='widget-list-link2 add'>追加</a>\n";
                echo "</li>\n";
                echo "<hr>";

            }
            ?>
        </ol>
        <ol class="widget-list" id="tyumonzumi">
            <li style="text-align: left;">
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li>
            <hr size="3px" color="#888">

            <?php
            $sql = 'SELECT DISTINCT o.* FROM t_d_order_handy as o INNER JOIN t_d_morder_handy as m ON o.odh_No = m.odh_No WHERE o.odh_situation=2 ';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=2&table_No={$product['odh_Tbl_No']}' class='widget-list-link2' >席変更</a>\n";
                echo "<a href='cart.php?odh_No={$product['odh_No']}' class='widget-list-link2'>注変更</a>\n";
                echo "<a href='order.php?odh_Tbl_No={$product['odh_Tbl_No']}&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&situ=add' class='widget-list-link2 add'>追加</a>\n";
                echo "</li>\n";
                echo "<hr>";

            }

            if (isset($_POST["back"])) {

                $check  = $pdo->prepare("SELECT odhm_No FROM t_d_morder_handy WHERE odh_No = ?");
                $check->execute(array($_POST["back"]));
                if ($odh_Nos = $check->fetch(PDO::FETCH_ASSOC)) {
                } else {
                    $update = $pdo->prepare("UPDATE t_d_order_handy SET odh_situation=1 WHERE odh_No=?;");
                    $update->execute(array(
                        $_POST["back"]
                    ));
                }
            }
            ?>
        </ol>

        <ol class="widget-list" id="tyumonmati">
            <li style="text-align: left;">
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li>
            <hr size="3px" color="#888">

            <?php
            $sql = 'SELECT * FROM t_d_order_handy where odh_situation=1';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n";
                echo "<a href='order.php?odh_No={$product['odh_No']}&odh_Tbl_No={$product['odh_Tbl_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=1#don' class='widget-list-link2'>注文</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=1&table_No={$product['odh_Tbl_No']}' class='widget-list-link2'>席変更</a>\n";
                echo "<a href='order_del.php?odh_No={$product['odh_No']}' onclick='return MoveCheck({$product['odh_No']})' class='widget-list-link2 delete'>削除</a>\n";
                echo "</li>";
                echo "<hr>";

            }
            ?>
        </ol>
        <ul class="widget-tabs">
            <li class="widget-tab">
                <a href="#teikyozumi" class="widget-tab-link">提供済み</a>
            <li class="widget-tab">
                <a href="#tyumonzumi" class="widget-tab-link">注文済み</a>
            <li class="widget-tab">
                <a href="#tyumonmati" class="widget-tab-link">注文待ち</a>
        </ul>
        <div class="reload">
            <img src="./img/reload.svg" alt="再更新" />
        </div>
    </div>
</body>

</html>