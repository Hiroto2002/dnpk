<?php

// 必要なファイルを読み込む


// GETパラメータを受け取る
$odhNo  = $_GET["odhNo"];
$situNo  = $_GET["situNo"];


require_once '../DbManager.php';

try {
    // データベース接続
    $pdo = getDb();
    

    // SQL文を準備（プリペアドステートメント）
    $sql = "SELECT odh_situation FROM `t_d_order_handy` WHERE odh_No = :odhNo;";
    $stmt = $pdo->prepare($sql);
    
    // パラメータをバインド
    $stmt->bindParam(':odhNo', $odhNo, PDO::PARAM_INT);

    // SQLを実行
    $stmt->execute();
    
    // 結果を取得
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
     // 結果が存在し、situNoが一致するかを確認
     if ($product && $product[0]['odh_situation'] == $situNo) {
        echo(json_encode(true));
    } else {
        echo(json_encode(false));
    }

} catch (PDOException $e) {
    // エラー処理
    echo(json_encode(array("error" => $e->getMessage())));
}

exit();
