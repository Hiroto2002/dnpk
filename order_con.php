<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>来店客状況</title>
    <link rel="stylesheet" href="css/order_con.css">
    <!-- 削除するか確認のダイアログを出す関数 -->
    <script>
        function MoveCheck($str) {
            var res = confirm("オーダー番号"+$str+"を削除しますか？");
            if( res == true ) {
                // 再度確認
                var res = confirm("本当に削除しますか？");
                if( res == true ) {
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
    </script>
    <!-- 削除するか確認のダイアログを出す関数 -->
</head>
<script>
    /* ピッチインピッチアウトによる拡大縮小を禁止 */
    document.documentElement.addEventListener('touchstart', function (e) {
    if (e.touches.length >= 2) {e.preventDefault();}
    }, {passive: false});
    /* ダブルタップによる拡大を禁止 */
    var t = 0;
    document.documentElement.addEventListener('touchend', function (e) {
    var now = new Date().getTime();
    if ((now - t) < 350){
        e.preventDefault();
    }
    t = now;
    }, false);
</script>
<body>
    <header style="text-align:center;line-height: 1.5;">
        <p class="title">来店客状況</p> 
    </header>
    <div class="widget">
        <ol class="widget-list" id="teikyozumi">
            <li>
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li> 
            <?php
            // ブラウザでエラー確認が出来るようにします
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require_once 'DbManager.php';
            $pdo = getDb();
            $sql = 'SELECT * FROM t_d_order_handy where odh_situation=3';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li class='widget-list-link'>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n"; 
                echo "<a href='order.php' class='widget-list-link1'>注変更</a>\n"; 
                echo "<a href='order.php' class='widget-list-link1'>追加</a>\n"; 
                echo "</li>\n"; 
            }
            ?>
        </ol>
        <ol class="widget-list" id="tyumonzumi">
            <li>
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li> 
            <?php
            $sql = 'SELECT * FROM t_d_order_handy where odh_situation=2';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n"; 
                echo "<a href='registercoming.php?p=2&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=2' class='widget-list-link2'>席変更</a>\n"; 
                echo "<a href='order.php' class='widget-list-link2'>注変更</a>\n"; 
                echo "<a href='order.php' class='widget-list-link2'>追加</a>\n"; 
                echo "</li>\n"; 
            }
            ?>
        </ol>

        <ol class="widget-list" id="tyumonmati">
            <li>
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li> 
            <?php
            $sql = 'SELECT * FROM t_d_order_handy where odh_situation=1';
            $products = fetch_all_query($pdo, $sql);
            foreach ($products as $product) {
                echo "<li>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Tbl_No']}</a>\n"; 
                echo "<a class='widget-list-link1'>{$product['odh_Ninzu']}</a>\n"; 
                echo "<a href='order.php?odh_No={$product['odh_No']}&odh_Tbl_No={$product['odh_Tbl_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=1#don' class='widget-list-link2'>注文</a>\n"; 
                echo "<a href='registercoming.php?p=2&odh_No={$product['odh_No']}&odh_Ninzu={$product['odh_Ninzu']}&odh_situation=1' class='widget-list-link2'>席変更</a>\n"; 
                echo "<a href='order_del.php?odh_No={$product['odh_No']}' onclick='return MoveCheck({$product['odh_No']})' class='widget-list-link2'>削除</a>\n"; 
                echo "</li>";
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
    </div>
    <footer style="text-align:center; font-size:20px;line-height: 15.5;" >
        <p style="color: white;">Copyright © DNPK.JP All Rights Reserved.</p> 
    </footer>
</body>
</html>