<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require_once "DbManager.php";

// 複数のオプションを同じ配列に入れる
/**
 * 繰り返し呼ばれる
 * $count:$i
 * $odhm_Nos:odhm_Noの配列
 * $value:odhm_Nosの中身
 */

if ($_SESSION["situ"] === "add") {
    // これによって注文変更のif文に入れる
    $_GET["odh_No"] = $_SESSION["odh_No"];
} else {
    // print("aaa");
}

// 削除
if (isset($_POST["delete"])) {
    $delete_id = $_POST["delete"];
    $_SESSION['orders'][$delete_id] = array();
    // print_r($_SESSION['orders'][$count]);
    // print("<br/>");
    // print_r($_SESSION['options']);
    // print("<br/>");
    $_SESSION['options'][$delete_id] = array();
    header("Location: cart.php");
    exit();
}

// DBから削除
if (isset($_POST["DB_delete"])) {
    $pdo = getDb();
    // print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
    // print($_POST["DB_delete"]."a");

    // 外部キー制約によって参照ごと消える
    $statement = $pdo->prepare(" DELETE FROM t_d_morder_handy WHERE odhm_No=?");
    $statement->execute(array(
        $_POST["DB_delete"],
    ));
}

// 注文済みから注文変更
if (isset($_GET["odh_No"])) {
    $pdo = getDb();
    $option = [];
    // $stmt= $pdo->prepare("SELECT DISTINCT t_d_order_handy.odh_No,t_d_order_handy.odh_Tbl_No,t_d_order_handy.odh_Ninzu,t_d_morder_handy.odhm_No, t_d_morder_handy.mn_ID,t_d_morder_handy.odhm_Quant 
    //                     FROM t_d_order_handy 
    //                     INNER JOIN t_d_morder_handy on t_d_order_handy.odh_No = t_d_morder_handy.odh_No 
    //                     WHERE t_d_order_handy.odh_No = ? ORDER BY t_d_morder_handy.odhm_No;
    //                     ");
    // $stmt->execute(array($_GET["odh_No"]));

    // $stmt = $pdo->prepare("SELECT the_order.odh_No,the_order.odh_Tbl_No,the_order.odh_Ninzu, 
    //                         menu.odhm_No,menu.mn_ID,menu.odhm_Quant,the_option.opm_ID 
    //                         FROM t_d_order_handy as the_order 
    //                         INNER JOIN t_d_morder_handy as menu on the_order.odh_No = menu.odh_No 
    //                         INNER JOIN t_d_morder_option as the_option on menu.odhm_No = the_option.odhm_No 
    //                         WHERE the_order.odh_No = ? ORDER BY menu.odhm_No;");
    // $stmt->execute(array($_GET["odh_No"]));

    // $statement = $pdo->prepare("SELECT odhm_No FROM t_d_morder_handy WHERE odh_No=?");
    // $statement->execute(array($_GET["odh_No"]));


    // $i = 0;
    // while($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)){
    //     print_r($odh_Nos);
    //     print("<br/>");
    //     print("<br/>");
    // }
    // while($no_option = $statement->fetch(PDO::FETCH_ASSOC)){
    //     print "全部".$no_option["odhm_No"]."<br/>";

    //         if($odh_Nos["odhm_No"] === $no_option["odhm_No"]){
    //             print "オプション無いよ".$odh_Nos["odhm_No"]."<br/>";
    //         }
    //     }
    // exit();
    $stmt = $pdo->prepare("SELECT handy.odh_No,handy.odh_Tbl_No,handy.odh_Ninzu, 
                        menu.odhm_No,menu.mn_ID,menu.odhm_Quant,
                        op.opm_ID 
                        FROM t_d_order_handy as handy 
                        INNER JOIN t_d_morder_handy as menu 
                        ON handy.odh_No = menu.odh_No 
                        LEFT OUTER JOIN t_d_morder_option as op 
                        ON menu.odhm_No = op.odhm_No 
                        WHERE handy.odh_No = ?
                        ORDER BY menu.odhm_No;
                        ");
    $stmt->execute(array($_GET["odh_No"]));


    $options = [];
    $opt_count = 0;
    $j = 0;
    $i = 0;
    $quant = [];
    $odhm_Nos = [];
    $delete_No = [];
    while ($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
        // print("i:".$i."<br/>");
        // print_r($odh_Nos);

        // 最小値を取る

        $_SESSION["odh_No"] = $odh_Nos["odh_No"];
        $_SESSION["odh_Tbl_No"] = $odh_Nos["odh_Tbl_No"];
        $_SESSION["odh_Ninzu"] = $odh_Nos["odh_Ninzu"];

        array_push($odhm_Nos, $odh_Nos["odhm_No"]);

        // for($j = $i;$j > 0;$j--){
        // 一個前をチェック
        // ここ
        if ($odhm_Nos[$i] === $odhm_Nos[$i - 1]) {
            // print("同じ");
            $opt_count++;
            $options[$j - 1][$opt_count] = ChangeOptionName($odh_Nos["opm_ID"]);
            $flg = 1;
            // print("<br/> opt_count:".$opt_count);
            // print("<br/> j:".$j);
            // print("<br/> i-opt_count:".$i-$opt_count);
        } else {
            $menu[0][$j] = $odh_Nos["mn_ID"];
            $options[$j][0] = ChangeOptionName($odh_Nos["opm_ID"]);
            array_push($quant, $odh_Nos["odhm_Quant"]);
            array_push($delete_No, $odh_Nos["odhm_No"]);
            // print("<br/> j:".$j);
            $j++;
        }
        // } //for
        $i++;
        // print("<br/><br/>opt:");
        // print_r($options);
    }
    //数量
    // $quants = json_encode($quant);

    $orders = $menu[0];
} else {
    if (!isset($_SESSION["orders"])) {
        print("<p class='none'>注文がありません</p>");
    }
}

$odh_No = $_SESSION['odh_No'];
$odh_Tbl_No = $_SESSION['odh_Tbl_No'];
$odh_Ninzu = $_SESSION['odh_Ninzu'];

if (isset($_SESSION['orders'])) {
    $orders = $_SESSION['orders'];
}

if (isset($_SESSION['options'])) {
    $options = $_SESSION['options'];
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カート</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<script src="./frontend/public/js/JQuery.js"></script>
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
        <!-- <div class="information">
                <div class="info_1">席番号<br><span style="font-size:2em;"><?php //print $odh_Tbl_No; 
                                                                        ?></span></div>
                <div class="info_1">人数<br><span style="font-size:2em;"><?php //print $odh_Ninzu; 
                                                                        ?>名</span></div>
                <div class="info_2">オーダーNo<br><?php //print $odh_No; 
                                                ?></div>
            </div> -->
        <div class="information">
            <p class="title">カート</p>

            <p class="back"><a href="./order_con.php#tyumonzumi">＜戻る</a></p>
        </div>
    </header>

    <div class="widget">

        <div class="user">

            <div>
                <dl>
                    席番号
                </dl>
                <dt>
                    <?php print $_SESSION['odh_Tbl_No']; ?>
                </dt>
            </div>
            <div>
                <dl>
                    人数
                </dl>
                <dt>
                    <?php print $_SESSION['odh_Ninzu']; ?>
                </dt>
            </div>
            <div>
                <dl>
                    オーダーNo.
                </dl>
                <dt>
                    <?php print $_SESSION['odh_No']; ?>
                </dt>
            </div>
        </div>





        <?php
        // $_SESSION= array();
        // $_SESSION["options"] = array();
        // $_SESSION["orders"] = array();
        // カートの中身に対してすべて表示
        $count = 0;
        ?>

        <script>
            // 数量用の配列
            let cart = [];
            let i = 0;
        </script>

        <?php
        foreach ($orders as $value) :
        ?>
            <script>
                // 数量配列の初期化
                cart.push(1)
            </script>

            <?php
            // 中身があるとき
            if ($value) :
            ?>
                <div class="list">
                    <div class="menu">
                        <?php print(ChangeName($value)); ?>

                        <?php if (!isset($_GET["odh_No"])) : ?>
                            <div class="amount">
                                <button class="down" onclick=down(<?php echo $count; ?>)>ー</button>
                                <div class="number" id="<?php print $count ?>"></div>
                                <button class="up" onclick=up(<?php echo $count; ?>)>＋</button>
                            </div>
                        <?php else : ?>
                            <div class="amount">
                                <div class="number" id="<?php print $count ?>">✕<?php print $quant[$count]; ?></div>
                            </div>
                        <?php endif; ?>


                        <!-- DBから持ってくる場合 -->
                        <!-- <?php #if(isset($_GET["odh_No"])):
                                ?>
                <script>
                    cart = JSON.parse('<?php #echo $quants;
                                        ?>');
                        $("#" +  i).html(cart[i]);
                        i++;
                </script>
            <?php #else:
            ?>
                <script>
                        $("#" +  i).html(cart[i]);
                        i++;
                </script>
            <?php #endif;
            ?> -->

                        <!-- 数量の初期化 -->
                        <?php if (!isset($_GET["odh_No"])) : ?>
                            <script>
                                $(".number").html(1);
                            </script>
                        <?php endif; ?>

                        <?php if (!empty($options)) { ?>
                            <br>
                            <div class="option">
                            <?php
                            $option_count = 0;
                            foreach ($options as $value) {
                                foreach ($value as $val) {
                                    if ($options[$count] == $value) {
                                        // オプションを配列に格納
                                        $a[$option_count] = $val;
                                        // １つ目じゃないとき
                                        if ($option_count !== 0) {
                                            // 同じじゃなければ表示
                                            if ($a[$option_count - 1] !== $a[$option_count]) {
                                                print_r($a[$option_count] . ",");
                                            }
                                        } else {
                                            print($val . ",");
                                        }
                                        $option_count++;
                                    }
                                }
                            }
                        } ?>
                            </div>
                            <div class="tool">
                                <?php if (!isset($_GET["odh_No"])) : ?>
                                    <form action="option.php" method="post">
                                        <input type="hidden" name="mode" value="<?php print($count) ?>">
                                        <input type="submit" value="変更" class="change">
                                    </form>

                                    <form action="" method="post">
                                        <input type="hidden" name="delete" value="<?php print($count) ?>">
                                        <input type="submit" value="削除" class="delete">
                                    </form>
                                <?php else : ?>
                                    <?php if ($_SESSION["situ"] !== "add") : ?>
                                        <!-- 注文済みを削除 -->
                                        <form action="" method="post">
                                            <input type="hidden" name="count" value="<?php print($count) ?>">
                                            <input type="hidden" name="DB_delete" value="<?php print($delete_No[$count]) ?>">
                                            <input type="submit" value="削除" class="order_delete">
                                        </form>
                                    <?php else : ?>
                                        <!-- 注文済みに追加 -->
                                        <strong style="color:red;font-size:3rem;margin: 55px 0px 0 160px;">注文済</strong>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- <button class="delete" onclick=Delete(<?php #echo $count
                                                                            ?>)>
                        削除
                    </button> -->
                            </div>



                    </div>
                    <!-- <div class="quantity"></div> -->

                </div>
        <?php
            endif;
            $count++;
        endforeach;
        // print("<br/><br/><br/><br/>");
        // print_r($options);
        ?>


    </div>

    <form action="order.php" method="post" id="post">
        <input type="hidden" value="" name="add" />
        <input type="submit" value="追加" class="decision">
    </form>



    <script>
        const down = (value) => {
            if (cart[value] > 1) {
                cart[value]--;
            }
            $("#" + value).html(cart[value]);
        };

        const up = (value) => {
            cart[value]++;
            $("#" + value).html(cart[value]);
        };

        // postで数量を送信
        $('.decision').on("click", function() {
            // POST先
            const url = "./order_finish.php";
            const inputs = '<input type="hidden" name="quant" value="' + cart + '" />';
            $("#post").append(inputs);
        });
    </script>
</body>

</html>