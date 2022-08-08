<?php
 // ブラウザでエラー確認が出来るようにします
 ini_set('display_errors', 0);
 error_reporting(E_ALL & ~E_NOTICE);
session_start();
session_regenerate_id();

if(isset($_GET['odh_No'])) {
    $odh_No = $_GET['odh_No'];
}
if(isset($_GET['odh_Tbl_No'])) {
    $odh_Tbl_No = $_GET['odh_Tbl_No'];
}
if(isset($_GET['odh_Ninzu'])) {
    $odh_Ninzu = $_GET['odh_Ninzu'];
}
if(isset($_GET['odh_situation'])) {
    $odh_situation = $_GET['odh_situation'];
}
//もし、sessionにodh_Noがなかったら
if(!isset($_SESSION['odh_No'])){
    $_SESSION['odh_No']=$odh_No;
    $_SESSION['odh_Tbl_No']=$odh_Tbl_No;
    $_SESSION['odh_Ninzu']=$odh_Ninzu;
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>注文画面</title>
        <link rel="stylesheet" href="css/order.css" >
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
        <script type="text/javascript" src="./js/JQuery.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
        <script type="text/javascript" src="./js/spinner.js"></script>
        

    </head>

    <script>
        /* ピッチインピッチアウトによる拡大縮小を禁止 */
        document.documentElement.addEventListener('touchstart', function (e) {
        if (e.touches.length >= 2) {e.preventDefault();}
        }, {passive: false});
        /* ダブルタップによる拡大を禁止 */
        var t = 0;
        document.documentElement.addEventListener('touchend', function (e) {
        var now = new Date().getTime();
        if ((now - t) < 350){
            e.preventDefault();
        }
        t = now;
        }, false);
    </script>

    <body>
        <header style="text-align:center;line-height: 1.5;">
            <div class="information">
              <p class="back"><a href="./order_con.php">＜戻る</a></p>
              <div class="info_1">席番号<br><span style="font-size:2em;"><?php print $_SESSION['odh_Tbl_No']; ?></span></div>
              <div class="info_1">人数<br><span style="font-size:2em;"><?php print $_SESSION['odh_Ninzu']; ?>名</span></div>
              <div class="info_2">オーダーNo<br><?php print $_SESSION['odh_No']; ?></div>
              <form method="post" action="cart.php">
                <input type="submit" value="カート確認" class="cart" style="position: absolute; left: 70%; top: 20%">
              </form>
            </div>
            <div class="swiper mySwiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide">丼物</div>
                <div class="swiper-slide">そば[冷]</div>
                <div class="swiper-slide">そば[温]</div>
                <div class="swiper-slide">そば[冷・温]</div>
                <div class="swiper-slide">セット・定食</div>
                <div class="swiper-slide">単品[天ぷら]</div>
                <div class="swiper-slide">単品[その他]</div>
                <div class="swiper-slide">ドリンク１</div>
                <div class="swiper-slide">ドリンク２</div>
              </div>
            </div>
        </header>
<!--メイン-->
  <div class="swiper mySwiper2">
    <div class="swiper-wrapper">
  <?php
    // データベース呼び出し
    require_once 'DbManager.php';
    $pdo = getDb();
    for ( $i = 1; $i <= 9; $i++ ) {
        switch ($i) {
            case 1:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=11 ORDER BY mn_Sort ASC';
                break;
            case 2:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=21 ORDER BY mn_Sort ASC';
                break;
            case 3:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=22 ORDER BY mn_Sort ASC';
                break;
            case 4:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=23 ORDER BY mn_Sort ASC';
                break;
            case 5:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=31 ORDER BY mn_Sort ASC';
                break;
            case 6:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=41 ORDER BY mn_Sort ASC';
                break;
            case 7:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=42 ORDER BY mn_Sort ASC';
                break;
            case 8:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=51 ORDER BY mn_Sort ASC';
                break;
            case 9:
                $sql = 'SELECT mn_id,mn_Name_sub FROM t_m_menu where sec_CD_web=52 ORDER BY mn_Sort ASC';
                break;
        }
        // SQL文を実行
        $products = fetch_all_query($pdo, $sql);
    ?>
      <div class="swiper-slide">
        <div class="menu">
<?php
// セット・定食
$my_i=$i."01";
foreach ($products as $product) {
?>
          <div class="menu-img">
            <form method="post" action="option.php">
              <input type="hidden" name="mn_id" value="<?php echo $product['mn_id']; ?>">
              <input type="hidden" name="mn_Name_sub" value="<?php echo $product['mn_Name_sub']; ?>">
              <input type="hidden" name="my_i" value="<?php echo $product['my_i']; ?>">
              <input type="image" src="./img/<?php print $product['mn_id']; ?>.jpg" name="<?php print $product['mn_id']; ?>">
            </form>
          </div>
<?php
  $my_i=$my_i+1;
}
if($my_i % 2 == 0) {
  //奇数の場合>
  echo "          <div class='menu-img'>\n";
  echo "              <input type='image' src='./img/null.jpg'>\n";
  echo "          </div>\n";
}
?>
        </div>
      </div>
<?php } ?>
    </div>
  </div>

        <footer style="text-align:center; font-size:20px; line-height: 0.5;" >
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
        <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
        <script>
          //メイン
          var slider = new Swiper ('.mySwiper', {
              slidesPerView: 4,
              centeredSlides: true,
              loop: true,
              loopedSlides: 9, //スライドの枚数と同じ値を指定
              freemode:true,
          });
          
          //サムネイル
          var thumbs = new Swiper ('.mySwiper2', {
              slidesPerView: 'auto',
              spaceBetween: 10,
              centeredSlides: true,
              loop: true,
              slideToClickedSlide: true,
              freemode:true,
              slideActiveClass: 'swiper-slide-active'
          });
          
          //4系～
          //メインとサムネイルを紐づける
          slider.controller.control = thumbs;
          thumbs.controller.control = slider;
  </script>
    </body>

</html>