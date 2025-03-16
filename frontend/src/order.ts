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
  const cart: Order[] = [];

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
    const sheetElement = document.querySelector("#sheet") as HTMLElement;

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

    // シートを閉じる

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
