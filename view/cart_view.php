<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>カート</title>
        <link rel="stylesheet" href="css/cart.css">
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
            <p class="title">カート</p> 
        </header>
        <?php
        if(isset($_GET['odh_No'])) {
            $odh_No = $_GET['odh_No'];
        }
        if(isset($_GET['odh_Tbl_No'])) {
            $odh_Tbl_No = $_GET['odh_Tbl_No'];
        }
        if(isset($_GET['odh_Ninzu'])) {
            $odh_Ninzu = $_GET['odh_Ninzu'];
        }
        ?>
        <div class="customer">
            <div class="information">No:<?php print $odh_No; ?></div>
            <div class="information">席番:<?php print $odh_Tbl_No; ?></div>
            <div class="information">人数:<?php print $odh_Ninzu; ?>名</div>
        </div>

  <!-- カート一覧 -->
  <div class="widget">
  <?php if(count($carts) > 0){ ?>
            <ol>
            <?php foreach($carts as $cart){ ?>
                <li class="widget-list">
                    <a class="widget-list-link1"><?php print($cart['mn_Name_sub']); ?></a>
                    <a href="option.html" class="widget-list-link2">変更</a>
                    <a class="widget-list-link2">削除</a>
                    <ul class="widget-list-link3">
                        <li class="widget-list-link3-option">ごはん大盛り</li>
                        <li class="widget-list-link3-option">ミニそばC</li>
                        <li class="widget-list-link3-option">天玉</li>
                    </ul>
                </li> 
            <?php } ?>
  <?php }else{ ?>
    <p>カートに商品はありません。</p>
  <?php } ?>
  </div>
    <!-- <tbody>
        <?php foreach($carts as $cart){ ?>
        <tr>
          <td><?php print($cart['name']); ?></td>
          <td><?php print($cart['price']); ?></td>
          <td><?php print($cart['amount']); ?></td>
          <td><?php print($cart['price'] * $cart['amount']); ?></td>
          <td>
            <form method="post" action="cart_delete.php">
              <input type="submit" value="削除">
              <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <p>合計金額：<?php print($total_price); ?></p>
    <form method="post" action="finish.php">
      <input type="submit" value="購入する">
    </form> -->
  <?php }else{ ?>
    <!-- <p>カートに商品はありません。</p> -->
  <?php } ?>
  </div>
        <footer style="text-align:center; font-size:20px;line-height: 11;" >
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
  </body>

</html>