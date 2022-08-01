<?php
// ブラウザでエラー確認が出来るようにします
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'DbManager.php';

if(isset($_GET['p'])) {
    $p=$_GET['p'];
    echo $p;
}
exit;
try {
        $pdo = getDb();
    if(isset($_GET['p'])) {
            $p=$_GET['p'];
        if($p==2){
            $odh_No = $_GET['odh_No'];
        // }else{
        }elseif($p==1){
            //パラメータpが2でなかったらこっちの処理
            //データベース名、ユーザー名、パスワード
            $year = date('y'); 
            $month  = date('m');
            $day =  date('d');
            $date = $year.$month.$day;
            $date1 = $year."/".$month."/".$day;

            $sql = $pdo->prepare('SELECT * FROM t_d_order_handy where CAST(odh_Time AS DATE )=?  ORDER BY odh_No ASC');
            $sql->bindValue(1, $date1, PDO::PARAM_STR);
            $sql->execute();
            $data = $sql->fetchAll();
            $id = array_column($data, 'odh_No');

            // $sql = 'SELECT * FROM t_d_order_handy WHERE CAST(odh_Time AS DATE) = '.'.$date1.'.' order by odh_No';
            // $res = $pdo->query($sql);
            // $data = $res->fetchAll();
            // $id = array_column($data, 'odh_No');
        
            function issueNo($id){
                $year = date('y'); 
                $month  = date('m');
                $day =  date('d');
                $date = $year.$month.$day;
                if (!$id) {
                    return $date."001";
                }else {
                    $maxIdx = count($id)-1;
                    return $id[$maxIdx] + 1;
                }
            }
            // あらかじめMySQL内にテーブルとカラムを作成しておく必要がある
            $odh_No = issueNo($id);
        }   
        $tableno = $_POST['tableno'];
        $visitors = $_POST['visitors'];
        $InTime = date("Y-m-d H:i:s");
        $situation=1;  //新規登録時は1
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

    }
} catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
}

echo $p;
echo $tableno;
echo $visitors;
echo $InTime;
echo $situation;
echo "ABC";

// header('Location: order_con.php#tyumonmati');
exit;
?>

