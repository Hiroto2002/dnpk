<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require_once "DbManager.php";

if($_SESSION["situ"]==="add"){
    // これによって注文変更のif文に入れる
    $_GET["odh_No"] =$_SESSION["odh_No"];
}else{
    // print("aaa");
}

 // 削除
if(isset($_POST["delete"])){
    $delete_id=$_POST["delete"] ;
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
if(isset($_POST["DB_delete"])){
    $pdo = getDb();
    // print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
    // print($_POST["DB_delete"]);

    // オプションがない場合の削除
    // $statement = $pdo->prepare(" DELETE FROM t_d_morder_handy WHERE odhm_No=?");
    // $statement->execute(array(
    //     $_POST["DB_delete"],
    // ));

    // オプション削除
    // $stmt = $pdo->prepare(" DELETE  FROM t_d_morder_option WHERE odhm_No=? ");
    // $stmt->execute(array(
    //     $_POST["DB_delete"],
    // ));
}

// 注文済みから注文変更
if(isset($_GET["odh_No"])){
    $pdo = getDb();
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

    
    // オプションがない場合のodhm_No
    $opno_array  = array();
    $stmt = $pdo->prepare("SELECT odhm_No FROM `t_d_morder_handy` WHERE odh_No=? ORDER BY odhm_No");
    $stmt->execute(array($_GET["odh_No"]));

    while($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)){
        array_push($opno_array,$odh_Nos["odhm_No"]);
    }
 
    $stmt= $pdo->prepare("SELECT handy.odh_No,handy.odh_Tbl_No,handy.odh_Ninzu, 
                        menu.odhm_No,menu.mn_ID,menu.odhm_Quant,
                        op.opm_ID 
                        FROM t_d_order_handy as handy 
                        INNER JOIN t_d_morder_handy as menu 
                        ON handy.odh_No = menu.odh_No 
                        LEFT OUTER JOIN t_d_morder_option as op 
                        ON menu.odhm_No = op.odhm_No 
                        WHERE handy.odh_No = ?
                        ORDER BY menu.odhm_No;");
    $stmt->execute(array($_GET["odh_No"]));
    $i = 0;
    $quant = array();

    while($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)){
        // $_SESSION["odh_No"] = $odh_Nos["odh_No"];
        // $_SESSION["odh_Tbl_No"] = $odh_Nos["odh_Tbl_No"];
        // $_SESSION["odh_Ninzu"] = $odh_Nos["odh_Ninzu"];
        // $_SESSION["orders"][$i] = $odh_Nos["mn_ID"];
        print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
        print_r($odh_Nos);

        // 最小値を取る

        $_SESSION["odh_No"] = $odh_Nos["odh_No"];
        $_SESSION["odh_Tbl_No"] = $odh_Nos["odh_Tbl_No"];
        $_SESSION["odh_Ninzu"] = $odh_Nos["odh_Ninzu"];

        $menu[0][$i] = $odh_Nos["mn_ID"];
        // print_r($menu);
        array_push($quant,$odh_Nos["odhm_Quant"]);
        $i++;
    }
    //数量
    // $quants = json_encode($quant);

    $stmt = $pdo->prepare("SELECT d.opm_ID,d.odhm_No,m.opm_Name 
                            FROM t_d_morder_option d ,t_m_option_menu m 
                            WHERE d.opm_ID = m.opm_ID AND d.odh_No = ?;
                        ");
    $stmt->execute(array($_GET["odh_No"]));
    // テーブルの中身がなくなるまで
    $max_Odhm = 0;
    $odhm_No = array();

    // print("count:".$_POST["count"]."<br/>");

    // オプション
    while($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)){             
        // $_SESSION["update"] = true;
        if($max_Odhm < $odh_Nos["odhm_No"] ){
            // print("新しいメニューに入った<br/>");
            // print_r($odh_Nos["odhm_No"]);
            // print_r("<br/>");
            array_push($odhm_No,$odh_Nos["odhm_No"]);
                if($max_Odhm!==0){
                // print("2回目以降<br/>");
                    // $_SESSION["options"][$i]=$opt;
                    $option[0][$i]=$opt;
                    $opt = array();               
                    $i++;
                }else{
                    $i =$odhm_No[0]- $min_odh_No;
                    $opt = array();
                }
                array_push($opt,$odh_Nos["opm_Name"]);
                $max_Odhm = $odh_Nos["odhm_No"];
        }else{
            // print("同じメニュー<br/>");
            array_push($opt,$odh_Nos["opm_Name"]);
        }
    }
    // print_r($odhm_No);
    // print("<br/>");
    // print_r("<br/>");
    // 残った状態で終わってしまった場合
    if($opt!==array()){
        // $_SESSION["options"][$i] = $opt;
        // print($i."<br/>");
        $option[0][$i] = $opt;
    }
    // print("最後に渡す");
    // print_r($option[0]);

    
    $orders=$menu[0];
    $options=$option[0];
    // print_r($option);
}else{
    if(!isset($_SESSION["orders"])){
        print("<p class='none'>注文がありません</p>");
    }
}

$odh_No = $_SESSION['odh_No'];
$odh_Tbl_No = $_SESSION['odh_Tbl_No'];
$odh_Ninzu = $_SESSION['odh_Ninzu'];

if(isset($_SESSION['orders'])){
    $orders=$_SESSION['orders'];
}

if(isset($_SESSION['options'])){
    $options=$_SESSION['options'];
}

// print_r($_SESSION['orders'] );
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>カート</title>
        <link rel="stylesheet" href="css/cart.css">
    </head>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
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
            <div class="information">
                <div class="info_1">席番号<br><span style="font-size:2em;"><?php print $odh_Tbl_No; ?></span></div>
                <div class="info_1">人数<br><span style="font-size:2em;"><?php print $odh_Ninzu; ?>名</span></div>
                <div class="info_2">オーダーNo<br><?php print $odh_No; ?></div>
            </div>
        </header>

        <div class="widget">
                    
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
                foreach($orders as $value):
            ?>
                <script>
                    // 数量配列の初期化
                    cart.push(1)
                </script>

            <?php
                // 中身があるとき
                if($value):
            ?>
            <div class="list">
                <div class="menu">
                <?php print(ChangeName($value)); ?>

                <?php if(!isset($_GET["odh_No"])):?>
                <div class="amount">
                    <button class="down" onclick=down(<?php echo $count;?>)>ー</button>
                    <div class="number" id="<?php print $count ?>"></div>
                    <button class="up" onclick=up(<?php echo $count;?>)>＋</button>
                </div>
                <?php else: ?>
                    <div class="amount">
                        <div class="number" id="<?php print $count ?>">✕<?php print $quant[$count];?></div>
                    </div>
                <?php endif;?>
                
                        
            <!-- DBから持ってくる場合 -->
            <!-- <?php #if(isset($_GET["odh_No"])):?>
                <script>
                    cart = JSON.parse('<?php #echo $quants;?>');
                        $("#" +  i).html(cart[i]);
                        i++;
                </script>
            <?php #else:?>
                <script>
                        $("#" +  i).html(cart[i]);
                        i++;
                </script>
            <?php #endif;?> -->

            <!-- 数量の初期化 -->
            <?php if(!isset($_GET["odh_No"])):?>
                <script>
                    $(".number").html(1);
                </script>
            <?php endif;?>

                    <?php if(!empty($options)) {?>
                        <div class="option">
                            <?php 
                                $option_count = 0;
                            foreach($options as $value){
                                foreach($value as $val){
                                    if( $options[$count] == $value){
                                        // オプションを配列に格納
                                        $a[$option_count] = $val;
                                        // １つ目じゃないとき
                                        if($option_count !== 0){
                                            // 同じじゃなければ表示
                                            if($a[$option_count-1]!==$a[$option_count]){
                                                print_r($a[$option_count].",");
                                            }
                                        }else{
                                                print($val.",");
                                        }
                                        $option_count++;
                                    }
                                }
                            }
                        } ?>
                        </div>
                </div>
                <!-- <div class="quantity"></div> -->
                <div class="tool">
                    <?php if(!isset($_GET["odh_No"])):?>
                            <form action="option.php" method="post">
                                <input type="hidden" name="mode" value="<?php print($count)?>">
                                <input type="submit" value="変更" class="change">
                            </form>
                            
                            <form action="" method="post">
                                <input type="hidden" name="delete" value="<?php print($count)?>">
                                <input type="submit" value="削除" class="delete">
                            </form>
                    <?php else:?>
                        <?php if($_SESSION["situ"] !== "add"):?>
                            <!-- 注文済みを削除 -->
                            <form action="" method="post">
                                    <input type="hidden" name="count" value="<?php print($count)?>">
                                    <input type="hidden" name="DB_delete" value="<?php print($opno_array[$count])?>">
                                    <input type="submit" value="削除" class="order_delete">
                            </form>
                        <?php else:?>
                            <!-- 注文済みに追加 -->
                            <strong style="color:red;font-size:3rem;margin: 55px 0px 0 160px;">注文済</strong>
                        <?php endif;?>
                    <?php  endif;?>
                    
                    <!-- <button class="delete" onclick=Delete(<?php #echo $count?>)>
                        削除
                    </button> -->
                </div>
            </div>
            <?php 
                endif;
                $count++;
                endforeach;
                ?>


        </div>
        <footer style="text-align:center; font-size:20px;line-height: 0.5;" >
            <div class="button">

                <?php if(!isset($_GET["odh_No"])):?>
                <form action="order.php" method="post">
                    <input type="submit" value="続けて注文" class="add">
                </form>

                <form action="order_finish.php" method="post" id="post">
                    <input type="submit" value="注文を確定する" class="decision" >                
                </form>

                <?php else:?>
                    <?php if($_SESSION["situ"] !== "add"):?>
                    <form action="order_con.php?#tyumonzumi" method="post">
                        <input type="hidden" value="<?php print $_GET["odh_No"]; ?>" name="back"/>
                        <input type="submit" value="戻る" class="add">
                    </form>
                    <form action="order_finish.php" method="post" id="post">
                        <input type="submit" value="注文を追加する" class="decision" >                
                    </form>
                    <?php else:?>
                        <form action="order.php" method="post">
                            <input type="submit" value="戻る" class="add">
                        </form>
                    <form action="order_con.php?#tyumonzumi" method="post" id="post">
                        <input type="submit" value="追加を辞める" class="decision" >                
                    </form>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
        <script>
            const down = (value) =>{
                if(cart[value]>1){
                    cart[value]--;
                }
                $("#" + value).html(cart[value]);
            };

            const up = (value) =>{
                cart[value]++;
                $("#" + value).html(cart[value]);
            };

            // postで数量を送信
            $('.decision').on("click",function() {
                // POST先
                const url = "./order_finish.php";
                const inputs = '<input type="hidden" name="quant" value="' + cart + '" />';
                $("#post").append(inputs);
            });
                    </script>
                </body>
</html>