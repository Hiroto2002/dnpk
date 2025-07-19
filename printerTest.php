<button id="printButton">Print Print()Test</button>
<script src="./test/epos-2.17.0.js"></script>
<script src="./frontend/public/js/page/printer.js" type="module"></script>
<script src="./frontend/public/js/page/order.js" type="module"></script>
<script type="module">
import {
    Printer
} from "./frontend/public/js/page/printer.js";
const ipAddress = "192.168.15.21";
const port = 8008;
const PrinterClass = new Printer(ipAddress, port);

const Print = () => {
    console.log("Print function called");
    PrinterClass.print(123, 1000, "Test User", "D1", 4, [{
        menuId: 1,
        menuName: "Test Item",
        options: [],
        quant: 2,
        allOptions: [],
    }, ]);
}

const printButton = document.getElementById('printButton');

if (printButton) {
    console.log("printButton:", printButton);
    printButton.addEventListener('click', () => {
        // Print関数を呼び出す。価格やユーザー名は適宜渡してください。
        Print()
    });
}
</script>