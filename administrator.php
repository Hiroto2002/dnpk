<?php
    require_once 'DbManager.php';
    getDb();

    class DB{

        // orderを持ってくる
        public function getOdh_No(){
            $pdo = getDb();
            $sql = $pdo->query('SELECT odh_No FROM t_d_order_handy');
            while($product = $sql->fetch(PDO::FETCH_ASSOC)){
                $odh_No = $product["odh_No"];
                print ("<div class='menu_box'>$odh_No<br/>");
                    print($this->getOdhm_Name($odh_No));
                print("</div>");
            }
        }

        //menuを持ってくる
        public function getOdhm_Name($odh_No){
            $pdo = getDb();
            $sql = $pdo->prepare('SELECT menu.mn_Name 
                                FROM t_d_morder_handy morder
                                INNER JOIN t_m_menu menu ON menu.mn_ID = morder.mn_ID
                                WHERE odh_No = ?'
                                );
            $sql->execute(array($odh_No));
            while($product = $sql->fetch(PDO::FETCH_ASSOC)){
                $odhm_Name = $product["mn_Name"];
                print "<p class='menu'>$odhm_Name</p>";
                print $this->getOdhm_Option(#ここにNoが必要
            );
                print("<br/>"); 
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
                print "<p class='menu'>$opt_Name</p>";
                print("<br/>"); 
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