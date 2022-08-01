<?php
session_start();
if(isset($_POST['mn_id'])) {
    $mn_id = $_POST['mn_id'];
}
if(isset($_POST['odh_No'])) {
    $odh_No = $_POST['odh_No'];
}
if(isset($_POST['odh_Tbl_No'])) {
    $odh_Tbl_No = $_POST['odh_Tbl_No'];
}
if(isset($_POST['odh_Ninzu'])) {
    $odh_Ninzu = $_POST['odh_Ninzu'];
}
if(isset($_POST['mn_Name_sub'])) {
    $mn_Name_sub = $_POST['mn_Name_sub'];
}
if(isset($_POST['my_i'])) {
    $my_i = $_POST['my_i'];
}
$counter = 'counter' . $my_i;
if(isset($_POST[$counter])) {
    $suryo = $_POST[$counter];
}


// ブラウザでエラー確認が出来るようにします
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'DbManager.php';


if($p==2){
    // UPDATE文を変数に格納
    // あらかじめMySQL内にテーブルとカラムを作成しておく必要がある
    $sql = "UPDATE t_d_order_handy set odh_Tbl_No=:odh_Tbl_No,odh_Ninzu=:odh_Ninzu,odh_Time=:odh_Time,odh_situation=:odh_situation where odh_No=:odh_No";
}elseif($p==1){
    // INSERT文を変数に格納
    // あらかじめMySQL内にテーブルとカラムを作成しておく必要がある
    $sql = "INSERT INTO t_d_order_handy (odh_No, odh_Tbl_No, odh_Ninzu,odh_Time,odh_situation) VALUES (:odh_No, :odh_Tbl_No, :odh_Ninzu, :odh_Time, :odh_situation)";
// }else{
}
//挿入する値は空のまま、SQL実行の準備をする
$stmt = $pdo->prepare($sql);
// 挿入する値を配列に格納する
$params = array(':odh_No' => $odh_No, ':odh_Tbl_No' => $tableno, ':odh_Ninzu' => $visitors, ':odh_Time' => $InTime, ':odh_situation' => $situation);
//挿入する値が入った変数をexecuteにセットしてSQLを実行
$stmt->execute($params);
$sql = 'SELECT * FROM t_d_order_handy where odh_situation=1';
$pdo = getDb();
$products = fetch_all_query($pdo, $sql);



header('Location: complete.html');
exit;
?>