<?php


// ブラウザでエラー確認が出来るようにします
ini_set('display_errors', 1);
// error_reporting(0);
session_start();
require_once 'DbManager.php';


// $mn_ids = file_get_contents('php://input');
$mn_ids = json_decode($_GET["mn_IDs"]);
$opm_ids = json_decode($_GET["opm_IDs"]);
$quant_list = json_decode($_GET["quant_list"]);



if($_SESSION["odh_No"]){
    $order_num = $_SESSION['odh_No'];
}

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


$mn_IDs=[];
$opm_IDs=[];
$quant_List=[];
// echo $opm_ids;
// exit();
// menu

foreach($mn_ids as $value){
    if($value){
        array_push($mn_IDs,$value);
        // array_push($opm_IDs,$opm_ids[$i]);
        // array_push($quant_List,$quant_list[$i]);
    }
}

// option
foreach($opm_ids as $value){
        array_push($opm_IDs,$value);
        // array_push($opm_IDs,$opm_ids[$i]);
        // array_push($quant_List,$quant_list[$i]);
}

//quant
foreach($quant_list as $value){
    if($value){
        array_push($quant_List,$value);
        // array_push($opm_IDs,$opm_ids[$i]);
        // array_push($quant_List,$quant_list[$i]);
    }
}


$total_price = 0;
/**
 * 値段計算
 */

    while(count($mn_IDs) > $count){
        if($mn_IDs[$count]){
        if(empty($opm_IDs[$count])){
            $price1 = ChangePrice($mn_IDs[$count],$quant_List[$count]);
            $total_price = $total_price + $price1;    
        }else{
            $price2 = ChangeTotalPrice($mn_IDs[$count],$opm_IDs[$count],$quant_List[$count]);
            $total_price = $total_price + $price2;
        }        
            // price
            $price = ChangePrice($mn_IDs[$count],$quant_List[$count]);
            // if(!isset($_SESSION["update"])){
            $stmt= $pdo->prepare("INSERT INTO t_d_morder_handy (odhm_No,odh_No,mn_ID,odhm_Quant,odhm_Amount,Edittime) VALUES(?,?,?,?,?,NOW())");
            $stmt->execute(array(
                $odhm_No,
                $order_num,
                $mn_IDs[$count],
                $quant_List[$count],
                $price,
            ));
        }
    
 
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
        if($opm_IDs[$count]){
            foreach($opm_IDs[$count] as $val){
                // if(!isset($_SESSION["update"])){              
                $stmt= $pdo->prepare("INSERT INTO t_d_morder_option (odhm_No,opm_ID,odh_No) VALUES(?,?,?)");
                // }else{
                //     $stmt= $pdo->prepare("UPDATE t_d_morder_option SET odhm_No=?,opm_ID=?,odh_No=?");
                // }
                $stmt->execute(array(
                    $odhm_No,
                    $val,
                    $order_num,
                ));
            }
        }
        $odhm_No++;
        $count++;
    }
    

        // $odhm_No++;
    // }


$stmt = $pdo->prepare("UPDATE t_d_order_handy SET odh_situation=2 WHERE odh_No=?;");
$stmt->execute(array(
    $order_num
));

$_SESSION = array();
    echo json_encode($total_price);
    exit();    

// header('Location: order_con.php');
?>