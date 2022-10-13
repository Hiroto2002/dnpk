<?php
require_once 'DbManager.php';
getDb();

class DB
{

    // orderを持ってくる
    public function getOdh_No()
    {
        $pdo = getDb();
        $sql = $pdo->query('SELECT odh_No,odh_Tbl_No,odh_Ninzu FROM t_d_order_handy WHERE odh_situation = 2');
        while ($product = $sql->fetch(PDO::FETCH_ASSOC)) {
            $odh_No = $product["odh_No"];
            $odh_Tbl = $product["odh_Tbl_No"];
            $odh_Ninzu = $product["odh_Ninzu"];
            print("<input type='checkbox' id='$odh_No'><label class='menu_box' for='$odh_No'>
                <div class='menu_head'>
                    <div class='dt'>席番号
                        <div class='dl'>$odh_Tbl</div>
                    </div>
                    <div class='dt'>人数
                        <div class='dl'>$odh_Ninzu</div>
                    </div>
                    <div class='dt'>オーダーNo.
                        <div class='dl'>$odh_No</div>
                    </div>
                    <button>全削除</button>
                </div>
                    ");
                    print("<div class='menus'>");
                    print($this->getOdhm_Name($odh_No));
                    print("</div>");
                    print("
                <div class='calc_result'>
                    計算結果エリア
                </div>");
            print("</label>");
        }
    }

    //menuを持ってくる
    public function getOdhm_Name($odh_No)
    {
        $pdo = getDb();
        $sql = $pdo->prepare(
            'SELECT menu.mn_Name,morder.odhm_No,odhm_Quant
                                FROM t_d_morder_handy morder
                                INNER JOIN t_m_menu menu ON menu.mn_ID = morder.mn_ID
                                WHERE odh_No = ?'
        );
        $sql->execute(array($odh_No));
        while ($product = $sql->fetch(PDO::FETCH_ASSOC)) {
            $odhm_Name = $product["mn_Name"];
            $odhm_No = $product["odhm_No"];
            $odhm_Quant = $product["odhm_Quant"];
            print "<input type='checkbox' id='menu$odhm_No'><label class='menu' for='menu$odhm_No'><dl><dt class='menu_name'>$odhm_Name</dt>";
            print($this->getOdhm_Option($odhm_No));
            print "</dl>";
            print("<div class='menu_name'>✕$odhm_Quant</div>");
            print("</label></input>");
        }
    }

    // optionを持ってくる
    public function getOdhm_Option($odhm_No)
    {
        $pdo = getDb();
        $sql = $pdo->prepare(
            'SELECT opt.opm_Name
                                FROM t_d_morder_option morder
                                INNER JOIN t_m_option_menu opt ON morder.opm_ID = opt.opm_ID
                                WHERE odhm_No = ?;'
        );
        $sql->execute(array($odhm_No));
        print("<dd class='option'>");
        while ($product = $sql->fetch(PDO::FETCH_ASSOC)) {
            $opt_Name = $product["opm_Name"];
            // print "<span class='option'>$opt_Name</span>";
            print $opt_Name .", ";
        }
        print("</dd>");
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
    <link rel="stylesheet" href="./css/administrator.css" />
    <title>管理者</title>
</head>

<body>
    <header>
        <div class="information">
            <p class="title">来店客状況</p>

            <p class="back"><a href="./index.php">＜戻る</a></p>
        </div>
    </header>
    <div class="container">

        <?php
        $DBaction->getOdh_No();

        // $DBaction->getOdhm_Name(220915001);
        ?>

    </div>

</body>

</html>