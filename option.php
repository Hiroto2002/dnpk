 <?php
// session_start();
// session_regenerate_id();
// require_once "DbManager.php"; 


$mn_id  = $_POST["id"];

require_once 'DbManager.php';
            $pdo = getDb();
            $sql = "SELECT t_d_option_menu.*, t_m_option_menu.opm_Name, t_m_option_menu.opm_Price
            FROM t_d_option_menu INNER JOIN t_m_option_menu ON t_d_option_menu.opm_ID = t_m_option_menu.opm_ID
            WHERE t_d_option_menu.mn_ID=".$mn_id."
            ORDER BY t_d_option_menu.mn_ID, t_d_option_menu.op_Sort;
            ";
            $products = fetch_all_query($pdo, $sql);

            echo(json_encode($products));

exit();



//オプション変更 
if(isset($_POST["mode"])){
    $cart_id = $_POST["mode"];
    $mn_id = $_SESSION["orders"][$cart_id];
    $mn_Name_sub = ChangeName($mn_id);
}

if(isset($_SESSION['odh_No'])){
    if(isset($_POST['mn_id'])) {
        // めにゅーのid
        $mn_id = $_POST['mn_id'];
    }
    if(isset($_POST['mn_Name_sub'])) {
        // タップされたメニューの名前
        $mn_Name_sub = $_POST['mn_Name_sub'];
    }
    if(isset($_POST['my_i'])) {
        $my_i = $_POST['my_i'];
    }
    // 該当商品の数量を取得
    $counter = 'counter' . $my_i;
    if(isset($_POST[$counter])) {
        $suryo = $_POST[$counter];
    }
    $odh_No = $_SESSION['odh_No'];
    $odh_Tbl_No = $_SESSION['odh_Tbl_No'];
    $odh_Ninzu = $_SESSION['odh_Ninzu'];

    // $_SESSION['cart']['mn_id']=$suryo;
    // $_SESSION['mn_Name_sub']=$mn_Name_sub;a
}

?>

<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>オプション選択画面</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/option.css">
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
        <header >
            <div class="information">
                <div class="info_1">席番号<br><span style="font-size:2em;"><?php print $odh_Tbl_No; ?></span></div>
                <div class="info_1">人数<br><span style="font-size:2em;"><?php print $odh_Ninzu; ?>名</span></div>
                <div class="info_2">オーダーNo<br><?php print $odh_No; ?></div>
            </div>
        </header>

        <form method="post" action="./cart_in.php">
            <div class="menu"><?php echo $mn_Name_sub ?></div>
            <!-- ２列の場合 -->
            <!-- <div class="topping">
                <div class="toggle_button">
                    <input id="toggle" class="toggle_input" type='checkbox'>
                    <label for="toggle" class="toggle_label">大盛</label>
                </div>
                <div class="toggle_button">
                    <input id="toggle" class="toggle_input" type='checkbox'>
                    <label for="toggle" class="toggle_label">大盛</label>
                </div>
            </div> -->
            <!-- ３列の場合 -->
            <!-- <div class="topping">
                <div class="toggle_button2">
                    <input id="toggle" class="toggle_input" type='checkbox'>
                    <label for="toggle" class="toggle_label">大盛</label>
                </div>
                <div class="toggle_button2">
                    <input id="toggle" class="toggle_input" type='checkbox'>
                    <label for="toggle" class="toggle_label">大盛</label>
                </div>
                <div class="toggle_button2">
                    <input id="toggle" class="toggle_input" type='checkbox'>
                    <label for="toggle" class="toggle_label">大盛</label>
                </div>
            </div> -->
            <!-- ４列の場合 -->
            <div class="topping">
            <?php
            // ブラウザでエラー確認が出来るようにします
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require_once 'DbManager.php';
            $pdo = getDb();
            $sql = "SELECT t_d_option_menu.*, t_m_option_menu.opm_Name, t_m_option_menu.opm_Price
            FROM t_d_option_menu INNER JOIN t_m_option_menu ON t_d_option_menu.opm_ID = t_m_option_menu.opm_ID
            WHERE t_d_option_menu.mn_ID=".$mn_id."
            ORDER BY t_d_option_menu.mn_ID, t_d_option_menu.op_Sort;
            ";
            $products = fetch_all_query($pdo, $sql);
            $my_i=1;
            // 商品ごとのオプション値のチェックボックスを出力　4列にする仕様
            foreach ($products  as $index => $product) { ?>
                <div class="toggle_button3">
                    <input class="toggle_input" type='checkbox' id="Option<?php echo $my_i; ?>" name="option[]" value="<?php echo $product['opm_Name']; ?>">
                    <label class="toggle_label" for="toggle"><?php echo $product['opm_Name']; ?></label>
                </div>
            <?php $my_i=$my_i+1;
            }
            ?>
            </div>
            <footer style="text-align:center; font-size:20px;line-height: 0.5;" >
                <div class="button">
                    <!-- カートに入れる -->
                    <input type="hidden" name="mn_id" value="<?php echo $mn_id; ?>">
                    <!-- 変更 -->
                    <?php if(isset($_POST["mode"])):?>
                        <input type="hidden" name="change" value="<?php echo $cart_id; ?>">
                    <?php endif?>
                    <input type="hidden" value="<?php print $odh_No;?>" name ="odh_No">
                    <input type="submit" value="戻る" formaction="order.php" class="return">
                    <input type="submit" value="カートに追加" class="cart" >                
                </div>
                <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
            </footer>
        </form>
        <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
    </script>
    </body>
</html>