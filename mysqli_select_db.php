<?php
// mysqliクラスのオブジェクトを作成
$mysqli = new mysqli('mysql57.dnpk.sakura.ne.jp', 'dnpk', '7ujmnhy6', 'dnpk_dnpk_oes');
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset("utf8");
}

// ここにDB処理いろいろ書く（後述）

// DB接続を閉じる
$mysqli->close();
?>