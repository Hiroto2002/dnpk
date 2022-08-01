<?php
session_start();
session_regenerate_id();

$odh_No = $_SESSION['odh_No'];
$odh_Tbl_No = $_SESSION['odh_Tbl_No'];
$odh_Ninzu = $_SESSION['odh_Ninzu'];

$orders=array();
$options=array();
if(isset($_SESION['orders'])){
    $orders=$_SESSION['orders'];
}
if(isset($_SESION['options'])){
    $options=$_SESSION['options'];
}
var_dump($_SESSION['orders']);
?>

<!DOCTYPE html>
<html>
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
            <div class="list">
                <div class="menu">
                    <div><?php print $mn_Name_sub; ?></div>
                    <?php if(!empty($options)) {?>
                        <div class="option">
                            <?php foreach ($options as $value){ ?>
                                <?php print $value; ?>,
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <!-- <div class="quantity"></div> -->
                <div class="tool">
                    <form action="option.php" method="post">
                            <input type="hidden" name="mode" value="change">
                            <input type="button" value="変更" class="change">
                    </form>
                    <form action="cart.php" method="post">
                        <input type="hidden" name="mode" value="delete">
                        <input type="button" value="削除" class="delete">
                    </form>
                </div>
            </div>
        </div>
        <footer style="text-align:center; font-size:20px;line-height: 0.5;" >
            <div class="button">
                <form action="order.php" method="post">
                    <input type="hidden" name="order_flg" value="2">
                    <input type="button" value="続けて注文" class="add">
                </form>
                <form action="order_finish.php" method="post">
                    <input type="submit" value="注文を確定する" class="decision" >                
                </form>
            </div>
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
    </body>
</html>