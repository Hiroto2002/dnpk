<?php
    session_start();
    $_SESSION = array();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>素材集計</title>
<!--    <link rel="stylesheet" href="css/order_con.css">-->
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
        <p class="back"><a href="./index.php">＜戻る</a></p>
        <p class="title">来店客状況</p> 
    </header>
    <?php
            // ブラウザでエラー確認が出来るようにします
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require_once 'DbManager.php';
            $pdo = getDb();
            $sql = "SELECT date_Format(`Pay_Time`,'%y%m%d') AS Mydate, t_d_payment.Pay_DorN, T_m_Sozai_Count.SozaiName, Sum(`Amount`*`odhm_Quant`) AS Mykazu FROM T_m_Sozai_Count INNER JOIN ((t_m_menu INNER JOIN (t_d_payment INNER JOIN t_d_morder_payment ON t_d_payment.Pay_No = t_d_morder_payment.Pay_No) ON t_m_menu.mn_ID = t_d_morder_payment.mn_ID) INNER JOIN T_Sozai_Count ON t_m_menu.mn_ID = T_Sozai_Count.mn_ID) ON T_m_Sozai_Count.SozaiNo = T_Sozai_Count.SozaiNo GROUP BY date_Format(`Pay_Time`,'%y%m%d') , t_d_payment.Pay_DorN, T_m_Sozai_Count.SozaiName HAVING Mydate=221113';"
            $products = fetch_all_query($pdo, $sql);
    ?>       
    <table border="1">
        <tr>
        <th>日付</th>
        <th>素材</th>
        <th>数量</th>
        </tr>
    <?php  foreach ($products as $product) { ?>
        <tr>
        <td><?php echo $product['Mydate'] ?></td>
        <td><?php echo $product['SozaiName'] ?></td>
        <td><?php echo $product['Mykazu'] ?></td>
        </tr>
    <?php  } ?>
    </table>

    <footer style="text-align:center; font-size:20px;line-height: 15.5;" >
        <p style="color: white;">Copyright © DNPK.JP All Rights Reserved.</p> 
    </footer>
</body>
</html>