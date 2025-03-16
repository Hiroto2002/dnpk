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
                    <button class="decide" onclick="order_finish()">注文決定</button>
                </div>

            </main>
            <div id="canvas"></div>
        </div>
    </div>

    <script src="./frontend/public/js/JQuery.js" type="text/javascript"></script>
    <script src="./frontend/public/js/swiper-bundle.min.js" type="text/javascript"></script>
    <script src="./frontend/public/js/page/order.js" type="text/javascript"></script>
    <script src="./test/epos-2.17.0.js"></script>
    <script>
    let table_number = String(document.querySelector(".table_number").textContent)
    let people_number = String(document.querySelector(".people_number").textContent)
    let order_number = String(document.querySelector(".order_number").textContent)

    // console.log(quant_list);

    const order_finish = () => {


        const result = window.confirm("注文を確定しますか？")

        if (result) {
            // 別のページに飛ぶ、などの操作
            const overlay = document.querySelector(".print_overlay")
            // console.log(overlay);
            overlay.style.display = ("flex");

            // 注文がない場合
            if (!Object.keys(mn_IDs).length) {
                alert("注文がありません!")
                return;
            }


            let mn_name_obj = new Object();
            let opm_name_obj = new Object();
            let mn_names = document.querySelectorAll(".menu_name")
            let opm_names = document.querySelectorAll(".option_name")
            const user = <?php echo json_encode($_SESSION["user"]); ?>;


            // 表示されていない番号を取得
            // console.log("消された番号：" + delete_list);     
            let quant_list = new Object();
            let quants = document.querySelectorAll(".quant");

            for (let i = 1; i < quants.length + 1; i++) {
                quant_list[i] = quants[i - 1].value
                mn_name_obj[i] = mn_names[i - 1].textContent
                opm_name_obj[i] = opm_names[i - 1].textContent
            }
            // 削除されているもの
            for (value of delete_list) {
                delete mn_IDs[value]
                delete opm_IDs[value]
                delete quant_list[value]
                delete mn_name_obj[value]
                delete opm_name_obj[value]
            }

            // 削除後注文があるか
            let order_flg = true
            Object.keys(mn_IDs).forEach(function(key) {
                if (mn_IDs[key]) {
                    order_flg = false
                }
            })
            if (order_flg) {
                alert("注文がありません")
                return
            }



            // // print
            let printer = null;
            let ePosDev = new epson.ePOSDevice();

            ePosDev.connect('192.168.15.21', 8008, cbConnect);

            function cbConnect(data) {
                if (data == 'OK' || data == 'SSL_CONNECT_OK') {
                    ePosDev.createDevice('local_printer', ePosDev.DEVICE_TYPE_PRINTER, {
                        'crypto': false,
                        'buffer': false
                    }, cbCreateDevice_printer);
                } else {
                    const overlay = document.querySelector(".print_overlay")

                    alert("エラーが発生しました。再読込されます。");
                    location.href = "./order.php"

                }
            }

            function cbCreateDevice_printer(devobj, retcode) {
                if (retcode == 'OK') {
                    printer = devobj;
                    printer.timeout = 60000;
                    printer.onreceive =
                        function(res) {
                            // alert(res.success);
                            alert("印刷が完了しました！");
                            location.href = "./order_con.php#tyumonmati"
                        };
                    printer.oncoveropen = function() {
                        // alert('coveropen'); 
                    };
                    // insertDB(quant_list);

                } else {
                    alert(retcode);
                }
            }


            const ChangeNumber = (number) => {
                switch (number) {
                    case 1:
                        return "１"
                        break;
                    case 2:
                        return "②"
                        break;
                    case 3:
                        return "③"
                        break;
                    case 4:
                        return "④"
                        break;
                    case 5:
                        return "⑤"
                        break;
                    case 6:
                        return "⑥"
                        break;
                    case 7:
                        return "⑦"
                        break;
                    case 8:
                        return "⑧"
                        break;
                    case 9:
                        return "⑨"
                        break;
                    case 10:
                        return "⑩"
                        break;
                }
            }
            // console.log(ChangeNumber(2)); 

            function Print(price, user_name) {
                printer.addTextLang('ja');
                printer.addTextSmooth(true);
                printer.addPageBegin();
                printer.addPageDirection(printer.DIRECTION_LEFT_TO_RIGHT);
                printer.addPageArea(0, 0, 288, 120);
                printer.addTextStyle(false, true, false, printer.COLOR_1);
                printer.addText('　No. ');
                printer.addText(order_number);
                printer.addText('　　　　\n');
                printer.addTextStyle(false, false, false, printer.COLOR_1);
                printer.addText('　ﾃｰﾌﾞﾙ\n\n');
                printer.addTextSize(2, 2);
                printer.addText(`　${table_number}　\n`);
                printer.addTextSize(1, 1);
                printer.addPageArea(288, 0, 288, 120);
                printer.addTextStyle(false, true, false, printer.COLOR_1);
                printer.addText(`　　　　　　　　　${user_name}　\n`);
                printer.addTextStyle(false, false, false, printer.COLOR_1);
                printer.addText('人数\n\n');
                printer.addTextStyle(false, false, false, printer.COLOR_1);
                printer.addTextSize(2, 2);
                printer.addText(`${people_number}　　　\n`);
                printer.addTextSize(1, 1);
                printer.addPageEnd();
                printer.addTextLineSpace(24);
                printer.addText('┏━━┯━━━━━━━━━━━━━┯━━━━━┓\n');
                printer.addText('┃数量│　　　品　　　　　名　　　│　備　考　┃\n');
                printer.addText('┠──┼─────────────┼─────┨\n');
                Object.keys(mn_name_obj).forEach((key) => {

                    printer.addTextDouble(false, true);
                    printer.addText('┃');
                    printer.addTextDouble(true, true);
                    printer.addText(`${ChangeNumber(Number(quant_list[key]))}`);
                    printer.addTextDouble(false, true);
                    printer.addText('│');
                    printer.addText(' ');
                    printer.addTextDouble(true, true);
                    printer.addText(mn_name_obj[key]);
                    printer.addTextPosition(408);
                    printer.addTextDouble(false, true);
                    printer.addText('│');
                    printer.addTextDouble(false, true);
                    printer.addText('　');
                    printer.addTextPosition(552);
                    printer.addText('┃');
                    printer.addTextDouble(false, false);
                    printer.addText('\n');
                    if (opm_name_obj[key]) {
                        printer.addTextDouble(false, true);
                        printer.addText('┃');
                        printer.addTextDouble(false, true);
                        printer.addTextPosition(72);
                        printer.addText('│');
                        printer.addTextDouble(false, true);
                        printer.addText('　');
                        printer.addText(opm_name_obj[key]);
                        printer.addTextPosition(408);
                        printer.addText('│');
                        printer.addTextPosition(552);
                        printer.addText('┃');
                        printer.addText('\n');
                    }
                    printer.addTextDouble(false, false);
                    printer.addText('┃　　│　　　　　　　　　　　　　│　　　　　┃\n');
                    // printer.addTextDouble(false, true);
                    // printer.addText('┃');
                    // printer.addTextDouble(false, false);
                    // printer.addText(' ');
                    // printer.addTextDouble(true, true);
                    // printer.addText(mn_name_obj[key]);
                    // printer.addTextPosition(336);
                    // printer.addTextDouble(false, true);
                    // printer.addText('│');
                    // printer.addTextDouble(true, true);
                    // printer.addText(` ${quant_list[key]}`);
                    // printer.addTextDouble(false, true);
                    // printer.addText('│');
                    // printer.addText('　');
                    // printer.addTextPosition(552);
                    // printer.addText('┃');
                    // printer.addTextDouble(false, false);
                    // printer.addText('\n');
                    // printer.addTextDouble(false, true);
                    // printer.addText('┃');
                    // printer.addTextDouble(false, true);
                    // printer.addText('　');
                    // printer.addText(opm_name_obj[key]);
                    // printer.addTextDouble(false, true);
                    // printer.addTextPosition(336);
                    // printer.addText('│');
                    // printer.addTextPosition(408);
                    // printer.addText('│');
                    // printer.addTextPosition(552);
                    // printer.addText('┃');
                    // printer.addText('\n');
                    // printer.addTextDouble(false, false);
                    // printer.addText('┃　　　　　　　　　　　　　│　　│　　　　　┃\n');
                })
                //printer.addTextDouble(false, true);
                //printer.addText('┃　　│　　　　　　　　　　　　　│　　　　　┃\n');
                printer.addTextDouble(false, false);
                printer.addText('┣━━┷━━━━━━━┯━━━━━┷━━━━━┫\n');
                printer.addTextDouble(false, true);
                printer.addText('┃　合　計　　　      │　　　　　　');
                printer.addText(`  ${price}  `);
                printer.addText('┃\n');
                printer.addTextDouble(false, false);
                printer.addText('┗━━━━━━━━━━┷━━━━━━━━━━━┛\n');
                printer.addTextLineSpace(30);
                printer.addTextAlign(printer.ALIGN_CENTER);
                printer.addText('毎度ありがとうございます\n');
                printer.addText('またのご来店をお待ちしております\n');
                printer.addTextAlign(printer.ALIGN_LEFT);
                printer.addText('\n');
                printer.addTextAlign(printer.ALIGN_CENTER);
                printer.addBarcode(`${order_number}`, printer.BARCODE_CODE39, printer.HRI_NONE, printer.FONT_A, 2,
                    64);
                printer.addTextAlign(printer.ALIGN_LEFT);
                printer.addText('\n');
                printer.addCut(printer.CUT_FEED);
                printer.addTextSize(1, 1);
                printer.addTextStyle(false, false, false, printer.COLOR_1);
                printer.addTextDouble(true, true);
                printer.addTextSize(1, 1);
                printer.send();
            }
            const insertDB = (quant_list) => {
                fetch(`order_finish.php?
        mn_IDs=${JSON.stringify(mn_IDs)}&
        opm_IDs=${JSON.stringify(opm_IDs)}&
        quant_list=${JSON.stringify(quant_list)}&
        order_num=${order_number}&
        stf_ID=${user}
        `)
                    // 第一引数、Promise:成功か失敗か状態を表す
                    .then(response => {
                        return response.json()
                    })
                    .then(data => {
                        console.log(data);
                        // Object.keys(data).forEach(function (key) {
                        //   console.log('key:', key);
                        //   console.log('json_parse:', data.family);
                        // });

                        flg = 1
                        let price = data.toLocaleString('ja-JP')
                        let user_name = <?php echo json_encode($_SESSION["user"]); ?>;
                        price = price.padStart(6, " "); // "00123"
                        Print(price, user_name)
                        // complete(()=>{

                        // })
                        // let total_price  = data
                        // return total_price
                        // console.dir(data);

                    })
                    .catch(error => {
                        console.error("失敗しました", error);
                    });

            }

            // function complete(_callback){
            //   _callback();
            // }
            // insertDB(quant_list);
        } else {
            // そのページにとどまる、などの操作
        }
    }

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

    // 更新を防ぐ
    let flg = 0;
    window.addEventListener("beforeunload", function(event) {
        if (flg === 0) {
            event.preventDefault();
            event.returnValue = "Check";
        }
    });
    </script>
</body>

</html>