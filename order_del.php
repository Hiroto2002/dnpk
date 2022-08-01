<?php
require_once 'DbManager.php';
try {

    //データベース名、ユーザー名、パスワード
    $pdo = getDb();

    $stmt = $pdo->prepare('DELETE FROM t_d_order_handy WHERE odh_No = :odh_No');

    $stmt->execute(array(':odh_No' => $_GET["odh_No"]));

} catch (Exception $e) {
        echo 'エラーが発生しました。:' . $e->getMessage();
}

header('Location: order_con.php');
exit;
?>