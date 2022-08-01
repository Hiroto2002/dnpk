<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>登録データ情報</title>
</head>
<body>
<h1>登録データ情報</h1>
<?php
 // ブラウザでエラー確認が出来るようにします
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

try {   //データベース名、ユーザー名、パスワード
    $dsn = 'mysql:dbname=dnpk_dnpk_oes; host=mysql57.dnpk.sakura.ne.jp; charset=utf8';
    $user = 'dnpk';
    $password = '7ujmnhy6';

    //MySQLのデータベースに接続
    $pdo = new PDO($dsn, $user, $password);
    //PDOのエラーレポートを表示
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // print '接続に成功しました！';
    if ($statement = $pdo->prepare("SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD=3 ORDER BY mn_Sort ASC")) {
        $statement->execute();

        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            echo "{$product['mn_id']} : {$product['mn_Name_sub']}<br>\n"; 
        }

    }    
    // if ($statement = $pdo->prepare("SELECT * FROM t_d_order_handy")) {
    //     $statement->execute();

    //     $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    //     foreach ($products as $product) {
    //         echo "{$product['odh_No']} : {$product['odh_Tbl_No']}<br>\n"; 
    //     }

    // }    
print 'The End';
} catch (PDOException $e) {
    die('データベースに接続できませんでした。' . $e->getMessage());
} finally {
    $pdo = null;  
}

?>

</body>
</html>