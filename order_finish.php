<?php
require_once 'DbManager.php';
require_once './Domain/Models/ValueObject.php';
require_once './Domain/Models/Order/OdhmNo.php';

use Domain\Models\Order\OdhmNo;


// JSON形式で送られたリクエストボディを取得
$rawData = file_get_contents("php://input");

// JSONを連想配列としてデコード
$data = json_decode($rawData, true);




$request_cart = $data['cart'];
$odh_no = $data["odh_no"];

$filtered_cart = array_filter($request_cart, function($item) {
    return !is_null($item);
});

$cart = array_values($filtered_cart);

$stf_ID = $data["user"];

$pdo = getDb();
$stmt = $pdo->query("SELECT MAX(odhm_No) FROM t_d_morder_handy");
$max_array  =$stmt->fetch();
$odhm_No =  (string)OdhmNo::generate($max_array[0]); 


$total_price = 0;

foreach($cart as $value){
    if(!$cart){
        echo json_encode("カートが空です");
        exit();
    }

    $menu_price = ChangePrice($value["menuId"],$value["quant"]);    
    $option_total_price = 0;    
    $offered_time = date('Y-m-d H:i:s');
    $end_flg = pack('C', 0);

    if (!empty($value["options"])) {
        foreach ($value["options"] as $option) {
            $option_total_price += $option["optionPrice"];
        }
    }
    $stmt= $pdo->prepare("INSERT INTO t_d_morder_handy (odhm_No,odh_No,mn_ID,odhm_Quant,odhm_Amount,Edittime,stf_ID,odh_Offered_time,End_FLG) VALUES(?,?,?,?,?,NOW(),?,?,?)");
    $stmt->bindValue(1, $odhm_No);
    $stmt->bindValue(2, $odh_no);
    $stmt->bindValue(3, $value["menuId"]);
    $stmt->bindValue(4, $value["quant"]);
    $stmt->bindValue(5, $menu_price + $option_total_price);
    $stmt->bindValue(6, $stf_ID);
    $stmt->bindValue(7, $offered_time);
    $stmt->bindValue(8, $end_flg, PDO::PARAM_LOB); // ← ビット列として渡す！

    $stmt->execute();

    $total_price += $menu_price + $option_total_price;
    if(!empty($value["options"])){
        foreach($value["options"] as $option){
            $stmt= $pdo->prepare("INSERT INTO t_d_morder_option (odhm_No,opm_ID,odh_No,odhm_LineNo) VALUES(?,?,?,?)");
            $stmt->execute(array(
                $odhm_No,
                $option["optionId"],
                $odh_no,
                0
            ));
        }
    }
    $odhm_No++;
}

$stmt = $pdo->prepare("UPDATE t_d_order_handy SET odh_situation=2 WHERE odh_No=?;");
$stmt->execute(array(
    $odh_no
));

echo json_encode($total_price);
exit();    

?>