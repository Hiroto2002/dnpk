<?php

// ブラウザでエラー確認が出来るようにします
ini_set('display_errors', 1);
error_reporting(0);
session_start();
require_once 'DbManager.php';

$order_num = $_SESSION['odh_No'];
$quantList = explode (",",$_POST["quant"]);


$count = 0;
$year = date('y'); 
$month  = date('m');
$day =  date('d');
$date = $year.$month.$day;
$odhm_No = $date ."001"; 
$pdo = getDb();
$stmt = $pdo->query("SELECT MAX(odhm_No) FROM t_d_morder_handy");
$Max  =$stmt->fetch();
// 今日の日付である場合
if($odhm_No <= $Max[0] && $Max[0]){
    $odhm_No = $Max[0] + 1;
}

foreach($_SESSION["orders"] as $value){
    if($value){
                
        // order
        $price = ChangePrice($_SESSION["orders"][$count]);

        // if(!isset($_SESSION["update"])){
        $stmt= $pdo->prepare("INSERT INTO t_d_morder_handy (odhm_No,odh_No,mn_ID,odhm_Quant,odhm_Amount,Edittime) VALUES(?,?,?,?,?,NOW())");
        $stmt->execute(array(
            $odhm_No,
            $order_num,
            $_SESSION["orders"][$count],
            $quantList[$count],
            $price,
        ));
        // }else{
            // $stmt=$pdo->prepare("SELECT odhm_No FROM t_d_morder_handy WHERE odh_No=?");
            // $stmt->execute(array(
            //     $_SESSION["odh_No"]
            // ));
            // while($odh_Nos = $stmt->fetch(PDO::FETCH_ASSOC)){
            //     print_r($odh_Nos["odhm_No"]);
            //     print("<br/>");
                // print_r($_SESSION["orders"]);
                // $statement= $pdo->prepare("UPDATE t_d_morder_handy SET odh_No=?,mn_ID=?,odhm_Quant=?,odhm_Amount=?,Edittime=NOW() WHERE odh_No=?");
                // $statement->execute(array(
                //     $order_num,
                //     $_SESSION["orders"][$count],
                //     $quantList[$count],
                //     $price,
                    // $odh_Nos["odhm_No"]
                //     $_SESSION["odh_No"]
                // ));
            // }
            // exit();
        // }

        // option
        foreach($_SESSION["options"][$count] as $val){
            $opm_ID = ChangeOptionID($val);        
            // if(!isset($_SESSION["update"])){        
            $stmt= $pdo->prepare("INSERT INTO t_d_morder_option (odhm_No,opm_ID,odh_No) VALUES(?,?,?)");
            // }else{
            //     $stmt= $pdo->prepare("UPDATE t_d_morder_option SET odhm_No=?,opm_ID=?,odh_No=?");
            // }
            $stmt->execute(array(
                $odhm_No,
                $opm_ID,
                $order_num,
            ));
        }

        $odhm_No++;
    }
    $count ++;
}

$stmt = $pdo->prepare("UPDATE t_d_order_handy SET odh_situation=2 WHERE odh_No=?;");
$stmt->execute(array(
    $order_num
));
$_SESSION = array();
header('Location: order_con.php');
exit();
?>