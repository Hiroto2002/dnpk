<?php
// ブラウザでエラー確認が出来るようにします
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);
session_start();
// 新しいIDに置き換える
// session_regenerate_id();

//注文済みに追加する
if ($_GET["situ"] === "add" || $_POST["add"]) {
  $_SESSION["situ"] = "add";
}


if (isset($_GET['odh_No'])) {
  $odh_No = $_GET['odh_No'];
}
if (isset($_GET['odh_Tbl_No'])) {
  $odh_Tbl_No = $_GET['odh_Tbl_No'];
}
if (isset($_GET['odh_Ninzu'])) {
  $odh_Ninzu = $_GET['odh_Ninzu'];
}
if (isset($_GET['odh_situation'])) {
  $odh_situation = $_GET['odh_situation'];
}

//もし、sessionにodh_Noがなかったら
if (!isset($_SESSION['odh_No'][$odh_No])) {
  $_SESSION['odh_No'] = $odh_No;
  $_SESSION['odh_Tbl_No'] = $odh_Tbl_No;
  $_SESSION['odh_Ninzu'] = $odh_Ninzu;
}

if (isset($_POST["odh_No"])) {
  $odh_No = $_POST["odh_No"];
}





















?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>注文画面</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/order.css">
  <link rel="stylesheet" href="css/bottomsheet.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
  <!-- <script type="text/javascript" src="./js/spinner.js"></script> -->


</head>

<script>
  /* ピッチインピッチアウトによる拡大縮小を禁止 */
  document.documentElement.addEventListener('touchstart', function(e) {
    if (e.touches.length >= 2) {
      e.preventDefault();
    }
  }, {
    passive: false
  });
  /* ダブルタップによる拡大を禁止 */
  var t = 0;
  document.documentElement.addEventListener('touchend', function(e) {
    var now = new Date().getTime();
    if ((now - t) < 350) {
      e.preventDefault();
    }
    t = now;
  }, false);
</script>

<body>
  <header style="height:300px">
    <div class="information">
      <p class="title">来店客状況</p>

      <p class="back"><a href="./order_con.php">
          < 戻る</a>
      </p>
      <form method="post" action="cart.php">
        <input type="hidden" value="<?php print $_SESSION["odh_No"]; ?>" name="odh_No">

        <!-- カートへの遷移はなし -->
        <!-- <input type="submit" value="カート確認" class="cart" style="position: absolute; left: 70%; top: 20%"> -->
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
      for ($i = 1; $i <= 9; $i++) {
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
            $my_i = $i . "01";
            foreach ($products as $product) {
            ?>

              <a href="#modal-01" class="modal-button">
                <div class="menu-img" id="<?php echo $product['mn_id']; ?>">
                  <form method="post" action="option.php">
                    <input type="hidden" name="mn_id" value="<?php echo $product['mn_id']; ?>">
                    <!-- <input type="hidden" name="mn_Name_sub" value="<?php # echo $product['mn_Name_sub']; 
                                                                        ?>"> -->
                    <!-- <input type="hidden" name="my_i" value="<?php # echo $product['my_i']; 
                                                                  ?>"> -->
                    <img src="./img/<?php print $product['mn_id']; ?>.jpg" name="<?php print $product['mn_id']; ?>" />
                    <div><?php echo $product['mn_Name_sub']; ?></div>
                  </form>
                </div>
              </a>

            <?php
              $my_i = $my_i + 1;
            }
            print_r($menu_ids);
            if ($my_i % 2 == 0) {
              //奇数の場合>
              echo "          <div class='menu-img modal-button' style='background-color:white;'>\n";
              echo "              <img src='./img/null.jpg'>\n";
              echo "          </div>\n";
            }

            ?>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
  <script>

    let menu_ID = 1
    function menuID(menuID) {
      const option_buttons = document.querySelector(".modal_options")
      const modal_title = document.querySelector(".modal_title")
      // アンカーからモーダルの判定
      if(location.hash === "#!"){
        // optionをリセット
        while( option_buttons.firstChild ){
          option_buttons.removeChild( option_buttons.firstChild );
        }
      };

      // const option_max = option_buttons.childElementCount
      // GET送信の場合は、
      // Formタグをシリアライズ化する

      // $.ajax({
      //   type: "POST",
      //   url: "./option.php",
      //   data: {
      //     id: 1
      //   },
      //   dataType: "JSON",
      //   success: function(response) {
      //     console.log(response);
      //   }
      // });
        
      fetch(`option.php?id=${menuID}`)
      // 第一引数、Promise:成功か失敗か状態を表す
        .then(response => {
          return response.json()
        })
        .then(data => {
          // console.log(data);
            for(let i = 0;data.length>i;i++){
              option_buttons.insertAdjacentHTML(
                "beforeend",
              `<input type='checkbox' id="option_check${i}" style="display:none;" name="options" value="${data[i]["opm_Name"]}">
                <label for="option_check${i}" id="option_button" >${data[i]["opm_Name"]}</label>
              `);
              
              modal_title.innerHTML = data[i]["mn_Name_sub"];
            }
          // }
        })
        .catch(error =>{
          console.log("失敗しました");
        });

      // $.ajax({
      //     url: 'option.php',
      //     type: 'post',
      //     datatype: 'text',
      //     async: true,
      //     data: {
      //       "id": menuID
      //     },
      //     dataType: "json"
      //   })

      //   .done(function(response) {
      //     //通信成功した時の処理
      //     console.log(response);
      //   })
      //   .fail(function(xhr) {
      //     //通信失敗した時の処理
      //     console.log(xhr);
      //   })
    }

    // オプションを表示
    $(document).on("click",".menu-img", function() {
      // console.log(this.id);
      const option = menuID(this.id)
    });

    //オプションを確定
    function add_cart(){
      let menu_name = document.querySelector(".modal_title").textContent
      let checkbox = document.querySelectorAll("input[name=options]:checked");
      let cart_body = document.querySelector("#cart_body");
      let menu_count = document.querySelectorAll(".incart");

      if(0 < menu_count.length){
          menu_ID = menu_count.length + 1
      }else{
          menu_ID = 1
      }

      // optionのなかみ
      if( 0 < checkbox.length ) {
        let options = []
        for(let checked_data of checkbox) {
          options.push(checked_data.value); // 1, 2, 5
        }
        string_options = options.join("、");
      }else{
        console.log("ありません");
      }

      // カートに追加
      cart_body.insertAdjacentHTML(
                "beforeend",
        `<div class="incart" id="menu_ID${menu_ID}">
          <div>
            <dl>
              <dt>
                ${menu_name}
              </dt>
              <dd>${string_options}</dd>

            </dl>
          </div>
          <button class="henkou" onclick="cart_change(${menu_ID})">変更</button>
          <button class="delete" onclick="cart_delete(${menu_ID})">削除</button>
          <div class="number">
            <button class="value" onclick="count_minus(${menu_ID})">-</button>
            <span id="count${menu_ID}">1</span>
            <button class="value" onclick="count_plus(${menu_ID})">+</button>
          </div>
        </div>`
      )
      location.hash = "#!"
    }

    // 削除（見かけのみ）
    const cart_delete = (menu_ID) =>{
      document.querySelector(`#menu_ID${menu_ID}`).style.display = "none";
    }

    // 変更
    const cart_change = (menu_ID) =>{
      document.querySelector("#sheet").setAttribute('aria-hidden',"true") 
    }

    // 数量プラス
    const count_plus = (menu_ID) =>{
      let count_ID = document.querySelector(`#count${menu_ID}`) 
      let number = Number(count_ID.textContent)
      console.log(number);
      count_ID.innerHTML = number+1
    }

    // 数量マイナス
    const count_minus = (menu_ID) =>{
      let count_ID = document.querySelector(`#count${menu_ID}`) 
      let number = Number(count_ID.textContent)
      console.log(number);
      if(number>1){
        count_ID.innerHTML = number-1
      }
    }
    // menus.each((i,menu)=>{
    // console.log(menu.value);
    // })



    //メイン
    var slider = new Swiper('.mySwiper', {
      slidesPerView: 4,
      centeredSlides: true,
      loop: true,
      slideToClickedSlide: true,
      loopedSlides: 9, //スライドの枚数と同じ値を指定
      freemode: true,

    });

    //サムネイル
    var thumbs = new Swiper('.mySwiper2', {
      slidesPerView: 'auto',
      spaceBetween: 10,
      centeredSlides: true,
      loop: true,
      slideToClickedSlide: true,
      freemode: true,
      slideActiveClass: 'swiper-slide-active',
      autoHeight: true

    });

    //4系～
    //メインとサムネイルを紐づける
    slider.controller.control = thumbs;
    thumbs.controller.control = slider;
  </script>

  <!-- 下のオーダー確認らん -->
  <div class="user under">

    <div>
      <dl>
        席番号
      </dl>
      <dt>
        <?php print $_SESSION['odh_Tbl_No']; ?>
      </dt>
    </div>
    <div>
      <dl>
        人数
      </dl>
      <dt>
        <?php print $_SESSION['odh_Ninzu']; ?>
      </dt>
    </div>
    <div>
      <dl>
        オーダーNo.
      </dl>
      <dt>
        <?php print $_SESSION['odh_No']; ?>
      </dt>
    </div>
    <button id="cart">
      カートを見る
    </button>

    <div class="draggable-area">
      <!-- <div class="draggable-thumb"></div> -->
    </div>

  </div>


  <!-- The sheet component -->
  <div id="sheet" class="column items-center justify-end" aria-hidden="true">
    <!-- Dark background for the sheet -->
    <div class="overlay"></div>
    <!-- The sheet itself -->
    <div class="contents column">
      <!-- Sheet controls -->
      <div class="controls">
        <!-- The thing to drag if you want to resize the sheet -->
        <div class="draggable-area">
          <div class="draggable-thumb"></div>
        </div>
        <!-- Button to close the sheet -->
        <button class="close-sheet" type="button" title="Close the sheet">&times;</button>
      </div>
      <!-- Body of the sheet -->
      <main class="body fill column" id="cart_body">
        <div class="user">

          <div>
            <dl>
              席番号
            </dl>
            <dt>
              <?php print $_SESSION['odh_Tbl_No']; ?>
            </dt>
          </div>
          <div>
            <dl>
              人数
            </dl>
            <dt>
              <?php print $_SESSION['odh_Ninzu']; ?>
            </dt>
          </div>
          <div>
            <dl>
              オーダーNo.
            </dl>
            <dt>
              <?php print $_SESSION['odh_No']; ?>
            </dt>
          </div>
          <button id="cart">
            カートを見る
          </button>
        </div>

        <div class="cart_under">

          <button class="return">戻る</button>
          <button class="decide">注文決定</button>
        </div>

      </main>
    </div>
  </div>
  <script>
    const $ = document.querySelector.bind(document)
    const sheet = $("#sheet")
    const sheetContents = sheet.querySelector(".contents")
    const draggableArea = sheet.querySelector(".draggable-area")
    let sheetHeight // in vh
    const setSheetHeight = (value) => {
      sheetHeight = Math.max(0, Math.min(100, value))
      sheetContents.style.height = `${sheetHeight}vh`
      if (sheetHeight === 100) {
        sheetContents.classList.add("fullscreen")
      } else {
        sheetContents.classList.remove("fullscreen")
      }
    }
    // スクロールさせない
    const body = document.querySelector('body')
    const setIsSheetShown = (value) => {
      sheet.setAttribute("aria-hidden", String(!value))
      const isScroll = !value ? "scroll" : "hidden"
      body.style.overflowY = isScroll;
    }
    // Open the sheet when clicking the 'open sheet' button
    // setSheetHeight(Math.min(50, 720 / window.innerHeight * 100))
    // setIsSheetShown(true)
    const user = document.querySelector(".user")
    user.addEventListener("click", () => {
      // setSheetHeight(Math.min(50, 720 / window.innerHeight * 100))
      setSheetHeight(50)
      setIsSheetShown(true)

    })

    // Hide the sheet when clicking the 'close' button
    sheet.querySelector(".close-sheet").addEventListener("click", () => {
      setIsSheetShown(false)
    })
    document.querySelector('.cart_under .return').addEventListener('click', () => {
      setIsSheetShown(false)

    })

    // Hide the sheet when clicking the background
    sheet.querySelector(".overlay").addEventListener("click", () => {
      setIsSheetShown(false)
    })
    const touchPosition = (event) =>
      event.touches ? event.touches[0] : event
    let dragPosition
    const onDragStart = (event) => {
      dragPosition = touchPosition(event).pageY
      sheetContents.classList.add("not-selectable")
      draggableArea.style.cursor = document.body.style.cursor = "grabbing"
    }
    const onDragMove = (event) => {
      if (dragPosition === undefined) return
      const y = touchPosition(event).pageY
      const deltaY = dragPosition - y
      const deltaHeight = deltaY / window.innerHeight * 100
      setSheetHeight(sheetHeight + deltaHeight)
      dragPosition = y
    }
    const onDragEnd = () => {
      dragPosition = undefined
      sheetContents.classList.remove("not-selectable")
      draggableArea.style.cursor = document.body.style.cursor = ""
      if (sheetHeight < 25) {
        setIsSheetShown(false)
      } else if (sheetHeight > 75) {
        setSheetHeight(100)
      } else {
        setSheetHeight(50)
      }
    }
    draggableArea.addEventListener("mousedown", onDragStart)
    draggableArea.addEventListener("touchstart", onDragStart)
    window.addEventListener("mousemove", onDragMove)
    window.addEventListener("touchmove", onDragMove)
    window.addEventListener("mouseup", onDragEnd)
    window.addEventListener("touchend", onDragEnd)
  </script>


  <!-- モーダル -->

  <div class="modal-wrapper" id="modal-01">
    <a href="#!" class="modal-overlay"></a>
    <div class="modal-window">
      <div class="modal-content">
        <p class="modal_title">海老天</p>
        <div class="modal_options">

          <div class="topping">
            <?php
            // ブラウザでエラー確認が出来るようにします
            // $pdo = getDb();
            // $sql = "SELECT t_d_option_menu.*, t_m_option_menu.opm_Name, t_m_option_menu.opm_Price
            // FROM t_d_option_menu INNER JOIN t_m_option_menu ON t_d_option_menu.opm_ID = t_m_option_menu.opm_ID
            // WHERE t_d_option_menu.mn_ID=".$mn_id."
            // ORDER BY t_d_option_menu.mn_ID, t_d_option_menu.op_Sort;
            // ";
            // $products = fetch_all_query($pdo, $sql);
            // $my_i=1;
            // // 商品ごとのオプション値のチェックボックスを出力　4列にする仕様
            # foreach ($products  as $index => $product) { 
            ?>
            <!-- <div class="toggle_button3">
                    <input class="toggle_input" type='checkbox' id="Option<?php echo $my_i; ?>" name="option[]" value="<?php echo $product['opm_Name']; ?>">
                    <label class="toggle_label" for="toggle"><?php echo $product['opm_Name']; ?></label>
                </div> -->
            <?php #$my_i=$my_i+1;
            // }
            ?>
          </div>
        </div>

        <div class="u">
          <button class="return_cart .modal-close" onclick="location.href='#!'">戻る</button>
          <button class="add_cart" onclick="add_cart()">カートに追加</button>
        </div>

      </div>
      <a href="#!" class="modal-close"><i class="far fa-times-circle"></i></a>
    </div>
  </div>
</body>

</html>