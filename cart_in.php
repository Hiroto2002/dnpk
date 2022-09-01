<?php
session_start();

if(isset($_POST['mn_id'])) {
    $mn_id = $_POST['mn_id'];
}
$post_no  = $_POST["odh_No"];

$odh_No = $_SESSION['odh_No'];

//オプションデータを配列で受け取り
if(isset($_POST['option'])) {
    $options = $_POST['option'];
}

if($odh_No!=''&&$mn_id!=''){
    $count = 0;
    if(!isset($_POST["change"])){
        if(is_array($_SESSION['orders'])) {
        $count = count($_SESSION['orders']);
        }
        $_SESSION['orders'][$count] = $mn_id;
    }    
    // $_SESSION['orders'][$odh_No]=[
    //     'mn_id' => $mn_id
    // ];
    
if(!empty($options)) {
    // 変更の場合
    if(isset($_POST["change"])){
        $count = $_POST["change"];
        $_SESSION['options'][$count] = $options;
        print_r($_SESSION["options"][$count]);
        $_SESSION["count"] = $count;
        print("count".$_SESSION["count"]);

    }else{
        $_SESSION['options'][$count] = $options;
        $_SESSION["count"] = $count;
    }
}

}

if($_SESSION["situ"]==="add"){
    $_SESSION["situ"] = array();
}
// print_r($_SESSION['orders']);
// print_r($_SESSION['options']);
if(isset($_POST["change"])){
    header('Location: cart.php');
    exit();
}else{
    header('Location: order.php');
    exit();
}
?>
