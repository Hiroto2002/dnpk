<?php
    require_once 'DbManager.php';
    
    session_start();

    if(!isset($_GET["odh_No"]) || 
        !isset($_GET["odh_Tbl_No"]) || 
        !isset($_GET["odh_Ninzu"]) || 
        !isset($_GET["odh_situation"]) 
    ){
        header("Location: order_con.php");
        return ; 
    } 

    //注文済みに追加する
    if(isset($_GET["situ"]) && $_GET["situ"] === "add" || isset($_POST["add"])) {
        $_SESSION["situ"] = "add";
    }
        $odh_No = $_GET['odh_No'];
        $odh_Tbl_No = $_GET['odh_Tbl_No'];
        $odh_Ninzu = $_GET['odh_Ninzu'];
        $odh_situation = $_GET['odh_situation'];
        
    //もし、sessionにodh_Noがなかったら
    if (!isset($_SESSION['odh_No'][$odh_No])) {
        $_SESSION['odh_No'] = $odh_No;
        $_SESSION['odh_Tbl_No'] = $odh_Tbl_No;
        $_SESSION['odh_Ninzu'] = $odh_Ninzu;
    }
    if (isset($_POST["odh_No"])) {
        $odh_No = $_POST["odh_No"];
    }      

    $pdo = getDb();
    $categories = [
        11 => "丼物",
        21 => "そば[冷]",
        22 => "そば[温]",
        23 => "そば[冷・温]",
        41 => "単品[天ぷら]",
        42 => "単品[その他]",
        51 => "ドリンク１",
        52 => "ドリンク２",
        0  => "その他",
        31 => "セット・定食"
    ];
    $placeholders = implode(',', array_fill(0, count($categories), '?'));
    $sql = "
        SELECT 
            m.mn_ID, 
            m.mn_Name_sub, 
            m.sec_CD_web, 
            o.opm_ID, 
            o.opm_Name, 
            o.opm_Price
        FROM t_m_menu AS m
        LEFT JOIN t_d_option_menu AS d ON m.mn_ID = d.mn_ID
        LEFT JOIN t_m_option_menu AS o ON d.opm_ID = o.opm_ID
        WHERE m.sec_CD_web IN (" . $placeholders . ")
        ORDER BY m.sec_CD_web, m.mn_Sort, d.op_Sort;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_keys($categories));
    // 取得したデータを sec_CD_web ごとに分類
    $all_products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $menu_id = $row['mn_ID'];
        $sec_CD_web = $row['sec_CD_web'];

        // メニュー情報を格納
        if (!isset($all_products[$sec_CD_web][$menu_id])) {
            $all_products[$sec_CD_web][$menu_id] = [
                'mn_id' => $menu_id,
                'mn_name_sub' => $row['mn_Name_sub'],
                'options' => []
            ];
        }
        // オプション情報がある場合は追加
        if (!empty($row['opm_ID'])) {
            $all_products[$sec_CD_web][$menu_id]['options'][] = [
                'opm_id' => $row['opm_ID'],
                'opm_name' => $row['opm_Name'],
                'opm_price' => $row['opm_Price']
            ];
        }
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
    <link rel="stylesheet" href="css/swiper-bundle.min.css" />
</head>

<body>
    <div class="print_overlay">
        <p>印刷中です。しばらくお待ち下さい。</p>
    </div>
    <header style="height:300px">
        <div class="information">
            <p class="title">来店客状況</p>
            <p class="back"><a href="./order_con.php">
                    < 戻る</a>
            </p>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php
                    foreach ($categories as $sec_CD_web => $category) {
                        echo "<div class='swiper-slide'>$category</div>";
                    }
                ?>
            </div>
        </div>
    </header>
    <!--メイン-->
    <div class="swiper mySwiper2" id="swiper">
        <div class="swiper-wrapper">
            <!-- slide -->
            <?php foreach ($categories as $sec_CD_web => $category_name) : ?>
            <?php if (!empty($all_products[$sec_CD_web])) : ?>
            <div class="swiper-slide">
                <div class="menu" id="slide-<?php echo $sec_CD_web; ?>">
                    <!-- menu -->
                    <?php foreach ($all_products[$sec_CD_web] as $product) :?>
                    <div class="menu-button" id="<?php echo $product['mn_id']; ?>"
                        data-id="<?php echo htmlspecialchars($product['mn_id']); ?>"
                        data-name="<?php echo htmlspecialchars($product['mn_name_sub']); ?>"
                        data-options='<?php echo json_encode($product["options"]); ?>'>
                        <p class="menu-box"><?php echo $product['mn_name_sub']  ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- option_modal -->
    <div class="modal-wrapper" id="modal-01">
        <div href="#!" class="modal-overlay"></div>
        <div class="modal-window">
            <div class="modal-content">
                <p class="modal-title"></p>
                <div class="modal-options"></div>
                <div class="u">
                    <button class="return_cart modal-close">戻る</button>
                    <button class="add-cart">カートに追加</button>
                </div>

            </div>
            <div id="modal-order-count">
                <p>個数</p>
                <form>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <input type="radio" id="quant-<?php echo $i; ?>" name="quant" value="<?php echo $i; ?>"
                        <?php echo $i === 1 ? 'checked' : ''; ?>>
                    <label for="quant-<?php echo $i; ?>"><?php echo $i; ?></label>
                    <?php endfor; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- 下のオーダー確認らん -->
    <div class="user under">
        <div>
            <dl>
                席番号
            </dl>
            <dt class="table_number"><?php print $_SESSION['odh_Tbl_No']; ?></dt>
        </div>
        <div>
            <dl>
                人数
            </dl>
            <dt class="people_number"><?php print $_SESSION['odh_Ninzu']; ?></dt>
        </div>
        <div>
            <dl>
                オーダーNo.
            </dl>
            <dt class="order_number"><?php print $_SESSION['odh_No']; ?></dt>
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
            <main class="body fill column" id="cart-body">
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
                    <!-- <button id="cart">
            カートを見る
          </button> -->
                    <div></div>
                </div>

                <div class="cart_under">
                    <button class="return">戻る</button>
                    <button id="order-decide-button" class="decide"
                        data-user="<?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?>">
                        注文決定
                    </button>
                </div>
            </main>
            <div id="canvas"></div>
        </div>
    </div>

    <script src="./frontend/public/js/JQuery.js" type="text/javascript"></script>
    <script src="./frontend/public/js/swiper-bundle.min.js" type="text/javascript"></script>
    <script src="./test/epos-2.17.0.js"></script>
    <script src="./frontend/public/js/page/printer.js" type="module"></script>
    <script src="./frontend/public/js/page/order.js" type="module"></script>
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
        setSheetHeight(92)
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
            setSheetHeight(92)
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


    //メイン
    const slider = new Swiper(".mySwiper", {
        slidesPerView: 4,
        centeredSlides: true,
        loop: true,
        slideToClickedSlide: true,
        loopedSlides: 10, //スライドの枚数と同じ値を指定
        freemode: true,
    });

    //サムネイル

    const thumbs = new Swiper(".mySwiper2", {
        slidesPerView: "auto",
        spaceBetween: 10,
        centeredSlides: true,
        loop: true,
        slideToClickedSlide: true,
        freemode: true,
        slideActiveClass: "swiper-slide-active",
        autoHeight: true,
    })

    //4系～
    //メインとサムネイルを紐づける
    slider.controller.control = thumbs;
    thumbs.controller.control = slider;

    /* ピッチインピッチアウトによる拡大縮小を禁止 */
    document.documentElement.addEventListener(
        "touchstart",
        function(e) {
            if (e.touches.length >= 2) {
                e.preventDefault();
            }
        }, {
            passive: false,
        }
    );

    /* ダブルタップによる拡大を禁止 */
    var t = 0;
    document.documentElement.addEventListener(
        "touchend",
        function(e) {
            var now = new Date().getTime();
            if (now - t < 350) {
                e.preventDefault();
            }
            t = now;
        },
        false
    );
    </script>
</body>

</html>