<?php
session_start();
session_regenerate_id();

if(isset($_POST['mn_id'])) {
    $mn_id = $_POST['mn_id'];
}
$odh_No = $_SESSION['odh_No'];

//オプションデータを配列で受け取り
if(isset($_POST['option'])) {
    $options = $_POST['option'];
}

if($odh_No!=''&&$mn_id!=''){
    $_SESSION['orders'][$odh_No]=[
        'mn_id' => $mn_id
    ];
}
if(!empty($options)) {
    $_SESSION['options']=$options;
}

var_dump($_SESSION['orders']);
var_dump($_SESSION['options']);
// header('Location: order.php');
?>
