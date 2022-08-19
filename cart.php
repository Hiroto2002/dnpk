<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require_once "DbManager.php";
// 注文済みから注文変更
if(isset($_GET["odh_No"])){
    $pdo = getDb();
    $stmt= $pdo->prepare("SELECT DISTINCT t_d_order_handy.odh_No,t_d_order_handy.odh_Tbl_No,t_d_order_handy.odh_Ninzu,
                            t_d_morder_handy.odhm_No,t_d_morder_handy.mn_ID,t_d_morder_handy.odhm_Quant,
                            t_d_morder_option.opm_ID FROM t_d_order_handy 
                            INNER JOIN t_d_morder_handy on t_d_order_handy.odh_No = t_d_morder_handy.odh_No 
                            INNER JOIN t_d_morder_option on t_d_order_handy.odh_No = t_d_morder_option.odh_No 
                            WHERE t_d_order_handy.odh_No = ? ORDER BY t_d_morder_handy.odhm_No;");
    $stmt->execute(array($_GET["odh_No"]));
    $odh_Nos = $stmt->fetch();
    print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
    print_r($odh_Nos);
    print("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
    print($odh_Nos["odh_No"]);
    // $_SESSION["$odh_No"]["odh_No"] = $odh_Nos["odh_No"];
    // $_SESSION["$odh_No"]["odh_Tbl_No"] = $odh_Nos["odh_Tbl_No"];
    // $_SESSION["$odh_No"]["odh_Ninzu"] = $odh_Nos["odh_Ninzu"];
    // $_SESSION["orders"] = $odh_Nos["odh_Ninzu"];

}
$odh_No = $_SESSION['odh_No'];
$odh_Tbl_No = $_SESSION['odh_Tbl_No'];
$odh_Ninzu = $_SESSION['odh_Ninzu'];

$orders=array();
$options=array();

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
                const cart = [];
            </script>
            <?php 
            if(!isset($_SESSION["orders"])){
                print("<p class='none'>注文がありません</p>");
            }
            foreach($orders as $value):

            ?>
            <!-- メニュー分枠を作る -->
            <script>
                cart.push(1)
                console.log(cart);
            </script>

            <?php
                // 中身があるとき
                if($value):
                
            ?>
            <div class="list">
                <div class="menu">
                <?php print(ChangeName($value)); ?>
                <div class="amount">
                    <button class="down" onclick=down(<?php echo $count;?>)>ー</button>
                    <div class="number" id="<?php print $count ?>"></div>
                    <button class="up" onclick=up(<?php echo $count;?>)>＋</button>
                </div>
                
                        
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
                    <form action="option.php" method="post">
                            <input type="hidden" name="mode" value="<?php print($count)?>">
                            <input type="submit" value="変更" class="change">
                    </form>

                    <form action="" method="post">
                            <input type="hidden" name="delete" value="<?php print($count)?>">
                            <input type="submit" value="削除" class="delete">
                    </form>
                    
                    <!-- <button class="delete" onclick=Delete(<?php #echo $count?>)>
                        削除
                    </button> -->
                </div>
            </div>
            <?php endif;
            // 削除
            if(isset($_POST["delete"])){
                $delete_id=$_POST["delete"] ;
                print_r($_SESSION['orders']);
                print("<br/>");
                $_SESSION['orders'][$delete_id] = array();
                // print_r($_SESSION['orders'][$count]);
                // print("<br/>");
                // print_r($_SESSION['options']);
                // print("<br/>");
                $_SESSION['options'][$delete_id] = array();
                print_r($_SESSION['options']);
                header("Location: cart.php");
                // exit();

            }
                $count++;
                endforeach;
                ?>


        </div>
        <footer style="text-align:center; font-size:20px;line-height: 0.5;" >
            <div class="button">
                <form action="order.php" method="post">
                    <input type="submit" value="続けて注文" class="add">
                </form>

                <form action="order_finish.php" method="post" id="post">
                    <input type="submit" value="注文を確定する" class="decision" >                
                </form>
            </div>
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
         <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
        <script>
            // 見かけの削除
            // const Delete = ($count) =>{
            //     console.log($count);
            //         $("#" + $count).css("display","none");
            //     }
            // console.log($cart);

            $(".number").html(cart[0]);

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
                const a = 0;       
                const inputs = '<input type="hidden" name="quant" value="' + cart + '" />';
                $("#post").append(inputs);
            });
                    </script>
                </body>
</html>