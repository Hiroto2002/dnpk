<?php
    require_once 'DbManager.php';
    getDb();

    class DB{

        // orderを持ってくる
        public function getOdh_No(){
            $pdo = getDb();
            $sql = $pdo->query('SELECT odh_No,odh_Tbl_No,odh_Ninzu FROM t_d_order_handy WHERE odh_situation = 2');
            while($product = $sql->fetch(PDO::FETCH_ASSOC)){
                $odh_No = $product["odh_No"];
                $odh_Tbl = $product["odh_Tbl_No"];
                $odh_Ninzu = $product["odh_Ninzu"];
                print ("<input type='checkbox' id='$odh_No'><label class='menu_box' for='$odh_No'>
                <div>席番号$odh_Tbl</div><br/>
                <div>人数$odh_Ninzu</div><br/>
                <div>オーダーNo.$odh_No</div><br/>
                <button>全削除</button>
                ");
                print($this->getOdhm_Name($odh_No));
                print("
                <div class='calc_result'>
                    計算結果エリア
                </div>");
                print("</label>");
                }
        }

        //menuを持ってくる
        public function getOdhm_Name($odh_No){
            $pdo = getDb();
            $sql = $pdo->prepare('SELECT menu.mn_Name,morder.odhm_No,odhm_Quant
                                FROM t_d_morder_handy morder
                                INNER JOIN t_m_menu menu ON menu.mn_ID = morder.mn_ID
                                WHERE odh_No = ?'
                                );
            $sql->execute(array($odh_No));
            while($product = $sql->fetch(PDO::FETCH_ASSOC)){
                $odhm_Name = $product["mn_Name"];
                $odhm_No = $product["odhm_No"];
                $odhm_Quant = $product["odhm_Quant"];
                print "<input type='checkbox' id='menu$odhm_No'><label class='menu' for='menu$odhm_No'><p class='menu_name'>$odhm_Name</p>";
                print "<span class='menu_name'>✕$odhm_Quant</span>";
                print ($this->getOdhm_Option($odhm_No));
                print("</label></input><br/>"); 
            }
        }
        
        // optionを持ってくる
        public function getOdhm_Option($odhm_No){
            $pdo = getDb();
            $sql = $pdo->prepare('SELECT opt.opm_Name
                                FROM t_d_morder_option morder
                                INNER JOIN t_m_option_menu opt ON morder.opm_ID = opt.opm_ID
                                WHERE odhm_No = ?;'
                                );
            $sql->execute(array($odhm_No));
            while($product = $sql->fetch(PDO::FETCH_ASSOC)){
                $opt_Name = $product["opm_Name"];
                print "<span class='option'>$opt_Name</span>";
            }
        }
    }
    $DBaction = new DB();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/administrator.css"/>
    <title>管理者</title>
</head>
<body>
    <header>
        <a href="./index.php">＜戻る</a>
    </header>
    <div class="container">
        
    <?php 
        $DBaction->getOdh_No();
        // $DBaction->getOdhm_Name(220915001);
    ?>
    </div>
    
</body>
</html>