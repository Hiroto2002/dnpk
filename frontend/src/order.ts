type OptionElement = {
  opm_id: string;
  opm_name: string;
  opm_price: string;
};

type Order = {
  menuId: number;
  menuName: string;
  options: Option[];
  allOptions: Option[];
  quant: number;
} | null;

type Option = {
  optionId: number;
  optionName: string;
  optionPrice: number;
};

const cart: Order[] = [];

document.addEventListener("DOMContentLoaded", () => {
  const optionButtons = document.querySelector(".modal-options") as HTMLElement;
  const modalTitle = document.querySelector(".modal-title") as HTMLElement;
  const menuButtons = document.querySelectorAll(
    ".menu-button"
  ) as NodeListOf<HTMLElement>;
  const modalWrapper = document.querySelector(".modal-wrapper") as HTMLElement;
  const cartBody = document.querySelector("#cart-body") as HTMLElement;
  const body = document.querySelector("body") as HTMLElement;
  const params = new URLSearchParams(location.search);

  const sheetElement = document.querySelector("#sheet") as HTMLElement;

  menuButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const menuId = this.dataset.id || "";
      const menuName = this.dataset.name || "";
      const options: OptionElement[] = this.dataset.options
        ? JSON.parse(this.dataset.options)
        : [];

      modalTitle.textContent = menuName;
      modalTitle.dataset.id = menuId;

      if (options.length > 0) {
        options.forEach((option) => {
          optionButtons.insertAdjacentHTML(
            "beforeend",
            `<div>
                <input type="checkbox" id="option_${option.opm_id}" name="options" 
                    value="${option.opm_id}" 
                    data-name="${option.opm_name}" 
                    data-price="${option.opm_price}"
                    style="display:none;"
                >
                <label for="option_${option.opm_id}">${option.opm_name}</label>
            </div>`
          );
        });
      } else {
        optionButtons.innerHTML = "<p>オプションなし</p>";
      }

      // モーダルを表示
      modalWrapper.style.display = "block";
    });
  });

  const closeModal = () => {
    const quantElements = document.querySelectorAll(
      'input[name="quant"]'
    ) as NodeListOf<HTMLInputElement>;
    const quantFirstElement = document.getElementById(
      "quant-1"
    ) as HTMLInputElement;
    quantElements.forEach((element) => {
      element.checked = false;
    });

    // デフォルトの1を再選択
    quantFirstElement.checked = true;

    modalWrapper.style.display = "none";
    modalTitle.textContent = "";
    modalTitle.dataset.id = "";
    optionButtons.innerHTML = "";
    params.delete("cart_index");
    history.pushState(null, "", "?" + params.toString());
  };

  // モーダルを閉じる
  document
    .querySelector(".modal-overlay")
    ?.addEventListener("click", function () {
      closeModal();
    });

  document
    .querySelector(".modal-close")
    ?.addEventListener("click", function () {
      closeModal();
    });

  // カートに追加する
  document.querySelector(".add-cart")?.addEventListener("click", function () {
    if (!modalTitle.textContent || !modalTitle.dataset.id) {
      return;
    }

    const menuName = modalTitle.textContent;
    const menuId = modalTitle.dataset.id;
    const allOptionBoxes = document.querySelectorAll(
      "input[name=options]"
    ) as NodeListOf<HTMLInputElement>;
    const selectedOptionBoxes = document.querySelectorAll(
      "input[name=options]:checked"
    ) as NodeListOf<HTMLInputElement>;
    const quantElement = document.querySelector(
      'input[name="quant"]:checked'
    ) as HTMLInputElement;

    const allOptions: Option[] = Array.from(allOptionBoxes).map((box) => {
      return {
        optionId: Number(box.value),
        optionName: box.dataset.name || "",
        optionPrice: Number(box.dataset.price),
      };
    });
    const options: Option[] =
      selectedOptionBoxes.length > 0
        ? Array.from(selectedOptionBoxes).map((box) => {
            return {
              optionId: Number(box.value),
              optionName: box.dataset.name || "",
              optionPrice: Number(box.dataset.price),
            };
          })
        : [];

    const order: Order = {
      menuId: Number(menuId),
      menuName: menuName,
      allOptions: allOptions,
      options: options,
      quant: Number(quantElement.value),
    };

    const stringOptions = options.map((option) => option.optionName).join("、");

    // カートに追加
    if (!order) {
      return;
    }

    //カート編集
    if (params.has("cart_index")) {
      const index = Number(params.get("cart_index"));
      cart[index] = order;
      const thisOrder = document.querySelector(`#cart${index}`) as HTMLElement;
      const selectElement = thisOrder.querySelector(
        ".quant"
      ) as HTMLSelectElement;
      if (!thisOrder || !selectElement) {
        return;
      }
      thisOrder.querySelector(".menu_name")!.textContent = order.menuName;
      thisOrder.querySelector(".option_name")!.textContent = stringOptions;
      selectElement.value = quantElement.value;
      closeModal();

      sheetElement.setAttribute("aria-hidden", String(false));
      const isScroll = "hidden";
      body.style.overflowY = isScroll;
      return;
    }

    cart.push(order);

    // カートに追加
    cartBody.insertAdjacentHTML(
      "beforeend",
      `<div class="incart" id="cart${cart.length - 1}"
        data
      >
                <button class="delete" data-index="${
                  cart.length - 1
                }">✕</button>
                <div>
                    <dl>
                        <dt class="menu_name">${order.menuName}</dt>
                        <dd class="option_name">${stringOptions}</dd>
                    </dl>
                </div>
                <select class="quant">
                    <option value="1" ${
                      quantElement.value == "1" ? "selected" : ""
                    }>1</option>
                    <option value="2" ${
                      quantElement.value == "2" ? "selected" : ""
                    }>2</option>
                    <option value="3" ${
                      quantElement.value == "3" ? "selected" : ""
                    }>3</option>
                    <option value="4" ${
                      quantElement.value == "4" ? "selected" : ""
                    }>4</option>
                    <option value="5" ${
                      quantElement.value == "5" ? "selected" : ""
                    }>5</option>
                </select>
                <button class="edit" data-index="${
                  cart.length - 1
                }">変更</button>
                
            </div>`
    );
    closeModal();
  });

  // カートの削除処理
  const cartDelete = (index: number) => {
    const thisOrder = document.querySelector(`#cart${index}`) as HTMLElement;
    if (!thisOrder) {
      return;
    }
    thisOrder.style.display = "none";
    cart[index] = null;
  };

  cartBody.addEventListener("click", (event) => {
    const target = event.target as HTMLElement;
    if (target.classList.contains("delete")) {
      const index = Number(target.dataset.index);
      cartDelete(index);
    }
  });

  // カートの編集処理
  const cartEdit = (index: number) => {
    const thisOrder = document.querySelector(`#cart${index}`) as HTMLElement;
    if (!thisOrder) {
      return;
    }
    const order = cart[index];
    if (!order) {
      return;
    }
    const menuId = order.menuId;
    const menuName = order.menuName;
    const allOptions = order.allOptions;
    const selectedOptions = order.options;
    const quant = order.quant;

    modalTitle.textContent = menuName;
    modalTitle.dataset.id = menuId.toString();

    if (allOptions.length > 0) {
      allOptions.forEach((allOption) => {
        optionButtons.insertAdjacentHTML(
          "beforeend",
          `<div>
                <input type="checkbox" id="option_${
                  allOption.optionId
                }" name="options" 
                            value="${allOption.optionId}" 
                            data-name="${allOption.optionName}" 
                            data-price="${allOption.optionPrice}"
                            style="display:none;"
                            ${
                              selectedOptions.some(
                                (option) =>
                                  option.optionId === allOption.optionId
                              )
                                ? "checked"
                                : ""
                            }
                            >
                <label for="option_${allOption.optionId}">${
            allOption.optionName
          }</label>
            </div>`
        );
      });
    } else {
      optionButtons.innerHTML = "<p>オプションなし</p>";
    }

    // デフォルトの数量を選択
    const quantElements = document.querySelectorAll(
      'input[name="quant"]'
    ) as NodeListOf<HTMLInputElement>;
    quantElements.forEach((element) => {
      element.checked = false;
      if (element.value === quant.toString()) {
        element.checked = true;
      }
    });

    sheetElement.setAttribute("aria-hidden", String(true));
    const isScroll = "scroll";
    body.style.overflowY = isScroll;

    // モーダルを表示
    modalWrapper.style.display = "block";

    // modalにデータを渡す
    params.set("cart_index", index.toString());
    history.pushState(null, "", "?" + params.toString());
  };

  cartBody.addEventListener("click", (event) => {
    const target = event.target as HTMLElement;
    if (target.classList.contains("edit")) {
      const index = Number(target.dataset.index);
      cartEdit(index);
    }
  });
});

const order_finish = (user_name: string) => {
  const table_number_element = document.querySelector(
    ".table_number"
  ) as HTMLElement;
  const people_number_element = document.querySelector(
    ".people_number"
  ) as HTMLElement;
  const order_number_element = document.querySelector(
    ".order_number"
  ) as HTMLElement;
  const order_number = order_number_element.textContent;
  const table_number = table_number_element.textContent;
  const people_number = people_number_element.textContent;
  const result = window.confirm("注文を確定しますか？");
  if (!result) {
    return;
  }
  if (cart.length === 0) {
    alert("注文がありません!");
    return;
  }
  // print ui
  const overlay = document.querySelector(".print_overlay") as HTMLElement;
  overlay.style.display = "flex";

  let printer: any = null;
  let ePosDev = new epson.ePOSDevice();
  ePosDev.connect("192.168.15.21", 8008, cbConnect);

  function cbConnect(data: string) {
    if (data == "OK" || data == "SSL_CONNECT_OK") {
      ePosDev.createDevice(
        "local_printer",
        ePosDev.DEVICE_TYPE_PRINTER,
        {
          crypto: false,
          buffer: false,
        },
        cbCreateDevice_printer
      );
    } else {
      const overlay = document.querySelector(".print_overlay");
      alert("エラーが発生しました");
    }
  }

  function cbCreateDevice_printer(devobj: any, retcode: any) {
    if (retcode == "OK") {
      printer = devobj;
      printer.timeout = 60000;
      printer.onreceive = function (res: any) {
        // alert(res.success);
        alert("印刷が完了しました！");
        location.href = "./order_con.php#tyumonmati";
      };
      printer.oncoveropen = function () {
        // alert('coveropen');
      };
      insertDB(cart);
    } else {
      alert(retcode);
    }
  }

  const ChangeNumber = (number: number) => {
    switch (number) {
      case 1:
        return "１";
        break;
      case 2:
        return "②";
        break;
      case 3:
        return "③";
        break;
      case 4:
        return "④";
        break;
      case 5:
        return "⑤";
        break;
      case 6:
        return "⑥";
        break;
      case 7:
        return "⑦";
        break;
      case 8:
        return "⑧";
        break;
      case 9:
        return "⑨";
        break;
      case 10:
        return "⑩";
        break;
    }
  };

  function Print(price: number, user_name: string) {
    printer.addTextLang("ja");
    printer.addTextSmooth(true);
    printer.addPageBegin();
    printer.addPageDirection(printer.DIRECTION_LEFT_TO_RIGHT);
    printer.addPageArea(0, 0, 288, 120);
    printer.addTextStyle(false, true, false, printer.COLOR_1);
    printer.addText("　No. ");
    printer.addText(order_number);
    printer.addText("　　　　\n");
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addText("　ﾃｰﾌﾞﾙ\n\n");
    printer.addTextSize(2, 2);
    printer.addText(`　${table_number}　\n`);
    printer.addTextSize(1, 1);
    printer.addPageArea(288, 0, 288, 120);
    printer.addTextStyle(false, true, false, printer.COLOR_1);
    printer.addText(`　　　　　　　　　${user_name}　\n`);
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addText("人数\n\n");
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addTextSize(2, 2);
    printer.addText(`${people_number}　　　\n`);
    printer.addTextSize(1, 1);
    printer.addPageEnd();
    printer.addTextLineSpace(24);
    printer.addText("┏━━┯━━━━━━━━━━━━━┯━━━━━┓\n");
    printer.addText("┃数量│　　　品　　　　　名　　　│　備　考　┃\n");
    printer.addText("┠──┼─────────────┼─────┨\n");
    cart.forEach((item) => {
      printer.addTextDouble(false, true);
      printer.addText("┃");
      printer.addTextDouble(true, true);
      printer.addText(`${ChangeNumber(Number(item?.quant))}`);
      printer.addTextDouble(false, true);
      printer.addText("│");
      printer.addText(" ");
      printer.addTextDouble(true, true);
      printer.addText(`${item?.menuName}`);
      printer.addTextPosition(408);
      printer.addTextDouble(false, true);
      printer.addText("│");
      printer.addTextDouble(false, true);
      printer.addText("　");
      printer.addTextPosition(552);
      printer.addText("┃");
      printer.addTextDouble(false, false);
      printer.addText("\n");
      if (item?.options && item?.options.length > 0) {
        printer.addTextDouble(false, true);
        printer.addText("┃");
        printer.addTextDouble(false, true);
        printer.addTextPosition(72);
        printer.addText("│");
        printer.addTextDouble(false, true);
        printer.addText("　");
        printer.addText(
          item?.options.map((option) => option.optionName).join("、")
        );
        printer.addTextPosition(408);
        printer.addText("│");
        printer.addTextPosition(552);
        printer.addText("┃");
        printer.addText("\n");
      }
      printer.addTextDouble(false, false);
      printer.addText("┃　　│　　　　　　　　　　　　　│　　　　　┃\n");
    });
    printer.addTextDouble(false, false);
    printer.addText("┣━━┷━━━━━━━┯━━━━━┷━━━━━┫\n");
    printer.addTextDouble(false, true);
    printer.addText("┃　合　計　　　      │　　　　　　");
    printer.addText(`  ${price}  `);
    printer.addText("┃\n");
    printer.addTextDouble(false, false);
    printer.addText("┗━━━━━━━━━━┷━━━━━━━━━━━┛\n");
    printer.addTextLineSpace(30);
    printer.addTextAlign(printer.ALIGN_CENTER);
    printer.addText("毎度ありがとうございます\n");
    printer.addText("またのご来店をお待ちしております\n");
    printer.addTextAlign(printer.ALIGN_LEFT);
    printer.addText("\n");
    printer.addTextAlign(printer.ALIGN_CENTER);
    printer.addBarcode(
      `${order_number}`,
      printer.BARCODE_CODE39,
      printer.HRI_NONE,
      printer.FONT_A,
      2,
      64
    );
    printer.addTextAlign(printer.ALIGN_LEFT);
    printer.addText("\n");
    printer.addCut(printer.CUT_FEED);
    printer.addTextSize(1, 1);
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addTextDouble(true, true);
    printer.addTextSize(1, 1);
    printer.send();
  }

  const insertDB = async (cart: Order[]) => {
    const res = await fetch("./order_finish.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        cart: cart,
        odh_no: order_number,
        user: user_name ? user_name : "ゲスト",
      }),
    });
    if (!res.ok) {
      alert("エラーが発生しました");
      return;
    }
    const data = await res.json();
    flg = 1;
    const price = data.toLocaleString("ja-JP").padStart(6, " ");
    //   Print(price, user_name);
    location.href = "./order_con.php#tyumonmati";
  };

  insertDB(cart);

  let flg = 0;
  window.addEventListener("beforeunload", function (event) {
    if (flg === 0) {
      event.preventDefault();
      event.returnValue = "Check";
    }
  });
};
