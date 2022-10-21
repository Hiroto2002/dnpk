 <?php
// session_start();
// session_regenerate_id();
// require_once "DbManager.php"; 


$mn_id  = $_REQUEST["id"];

require_once 'DbManager.php';
            $pdo = getDb();
            $sql = "SELECT t_d_option_menu.*, t_m_option_menu.opm_Name, t_m_option_menu.opm_Price,t_m_menu.mn_Name_sub
            FROM t_d_option_menu INNER JOIN t_m_option_menu ON t_d_option_menu.opm_ID = t_m_option_menu.opm_ID
            INNER JOIN t_m_menu ON t_d_option_menu.mn_ID = t_m_menu.mn_ID
            WHERE t_d_option_menu.mn_ID=".$mn_id."
            ORDER BY t_d_option_menu.mn_ID, t_d_option_menu.op_Sort;
            ";
            $products = fetch_all_query($pdo, $sql);

            echo(json_encode($products));

exit();