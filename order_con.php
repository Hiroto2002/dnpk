<?php
    require_once 'DbManager.php';
    ini_set('display_errors', 0);
    session_start();
    $user = $_SESSION["user"];;
    $_SESSION = array();
    $_SESSION["user"] = $user;
    $pdo = getDb();
    $sql = 'SELECT * FROM t_d_order_handy where odh_situation=3';
    $customers = fetch_all_query($pdo, $sql);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>来店客状況</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order_con.css">
    <script src="./frontend/public/js/JQuery.js" type="text/javascript"></script>
    <script src="./frontend/public/js/page/orderCon.js"></script>
</head>

<body>
    <header>
        <p class="title">来店客状況</p>
        <p class="back"><a href="./index.php">
                < 戻る</a>
        </p>
        <p class="register">
            <a href="registercoming.php?p=1">来店登録</a>
        </p>
    </header>
    <div class="widget">
        <ol class="widget-list" id="teikyozumi">
            <li style="text-align: left;">
                <div class="midashi">席番</div>
                <div class="midashi">人数</div>
            </li>
            <hr size="3px" color="#888">
            <?php
            
            foreach ($customers as $customer) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Ninzu']}</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$customer['odh_No']}&odh_Ninzu={$customer['odh_Ninzu']}&odh_situation=2&table_No={$customer['odh_Tbl_No']}' class='widget-list-link2'>席変更</a>\n";
                echo "<a href='cart.php?odh_No={$customer['odh_No']}' class='widget-list-link2'>注変更</a>\n";
                echo "<a href='order.php?odh_Tbl_No={$customer['odh_Tbl_No']}&odh_No={$customer['odh_No']}&odh_Ninzu={$customer['odh_Ninzu']}&situ=add' class='widget-list-link2 add'>追加</a>\n";
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
            $customers = fetch_all_query($pdo, $sql);
            foreach ($customers as $customer) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Ninzu']}</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$customer['odh_No']}&odh_Ninzu={$customer['odh_Ninzu']}&odh_situation=2&table_No={$customer['odh_Tbl_No']}' class='widget-list-link2' >席変更</a>\n";
                echo "<a href='cart.php?odh_No={$customer['odh_No']}' class='widget-list-link2'>注変更</a>\n";
                echo "<a href='order.php?odh_Tbl_No={$customer['odh_Tbl_No']}&odh_No={$customer['odh_No']}&odh_Ninzu={$customer['odh_Ninzu']}&situ=add' class='widget-list-link2 add'>追加</a>\n";
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
            $customers = fetch_all_query($pdo, $sql);
            foreach ($customers as $customer) {
                echo "<li>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Tbl_No']}</a>\n";
                echo "<a class='widget-list-link1'>{$customer['odh_Ninzu']}</a>\n";
                echo "<a href='order.php?odh_No={$customer['odh_No']}&odh_Tbl_No={$customer['odh_Tbl_No']}&odh_Ninzu={$customer['odh_Ninzu']}&odh_situation=1#don' class='widget-list-link2'>注文</a>\n";
                echo "<a href='registercoming.php?p=2&odh_No={$customer['odh_No']}&odh_Ninzu={$customer['odh_Ninzu']}&odh_situation=1&table_No={$customer['odh_Tbl_No']}' class='widget-list-link2'>席変更</a>\n";
                echo "<a href='order_del.php?odh_No={$customer['odh_No']}' onclick='return MoveCheck({$customer['odh_No']})' class='widget-list-link2 delete'>削除</a>\n";
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
            <img src="./frontend/public/imgs/reload.svg" alt="再更新" />
        </div>
    </div>
</body>

</html>