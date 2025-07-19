import { Order } from "./order.js"; // .js を忘れずに

export class Printer {
  private readonly ePosDev = new epson.ePOSDevice();
  private printer: any;

  public ready: Promise<void>;
  private resolveReady!: () => void;
  private rejectReady!: (reason?: any) => void;

  constructor(ipAddress: string, port: number) {
    this.ready = new Promise((resolve, reject) => {
      this.resolveReady = resolve;
      this.rejectReady = reject;
    });

    this.ePosDev.connect(ipAddress, port, (data: string) => {
      if (data === "OK" || data === "SSL_CONNECT_OK") {
        this.ePosDev.createDevice(
          "local_printer",
          this.ePosDev.DEVICE_TYPE_PRINTER,
          { crypto: false, buffer: false },
          this.cbCreateDevice_printer
        );
      } else {
        alert("プリンター接続に失敗しました。");
        this.rejectReady("Connection failed"); // 失敗時にPromiseをreject
      }
    });
  }

  private cbCreateDevice_printer = (devobj: any, retcode: any) => {
    if (retcode === "OK") {
      this.printer = devobj;
      this.printer.timeout = 60000;
      this.printer.onreceive = (res: any) => {
        alert("印刷が完了しました！");
        location.href = "./order_con.php#tyumonmati";
      };
      devobj.oncoveropen = () => {
        // alert('coveropen');
      };
      this.resolveReady(); // 成功時にPromiseをresolve
    } else {
      alert(retcode);
      this.rejectReady(retcode); // 失敗時にPromiseをreject
    }
  };

  public print(
    order_number: number,
    price: number,
    user_name: string,
    table_number: string,
    people_number: number,
    cart: Order[]
  ) {
    this.printer.addTextLang("ja");
    this.printer.addTextSmooth(true);
    this.printer.addPageBegin();
    this.printer.addPageDirection(this.printer.DIRECTION_LEFT_TO_RIGHT);
    this.printer.addPageArea(0, 0, 288, 120);
    this.printer.addTextStyle(false, true, false, this.printer.COLOR_1);
    this.printer.addText("　No. ");
    this.printer.addText(order_number);
    this.printer.addText("　　　　\n");
    this.printer.addTextStyle(false, false, false, this.printer.COLOR_1);
    this.printer.addText("　ﾃｰﾌﾞﾙ\n\n");
    this.printer.addTextSize(2, 2);
    this.printer.addText(`　${table_number}　\n`);
    this.printer.addTextSize(1, 1);
    this.printer.addPageArea(288, 0, 288, 120);
    this.printer.addTextStyle(false, true, false, this.printer.COLOR_1);
    this.printer.addText(`　　　　　　　　　${user_name}　\n`);
    this.printer.addTextStyle(false, false, false, this.printer.COLOR_1);
    this.printer.addText("人数\n\n");
    this.printer.addTextStyle(false, false, false, this.printer.COLOR_1);
    this.printer.addTextSize(2, 2);
    this.printer.addText(`${people_number}　　　\n`);
    this.printer.addTextSize(1, 1);
    this.printer.addPageEnd();
    this.printer.addTextLineSpace(24);
    this.printer.addText("┏━━┯━━━━━━━━━━━━━┯━━━━━┓\n");
    this.printer.addText("┃数量│　　　品　　　　　名　　　│　備　考　┃\n");
    this.printer.addText("┠──┼─────────────┼─────┨\n");
    cart.forEach((item) => {
      const Quant = new PrintQuantity(Number(item?.quant));
      this.printer.addTextDouble(false, true);
      this.printer.addText("┃");
      this.printer.addTextDouble(true, true);
      this.printer.addText(Quant.getQuantity());
      this.printer.addTextDouble(false, true);
      this.printer.addText("│");
      this.printer.addText(" ");
      this.printer.addTextDouble(true, true);
      this.printer.addText(`${item?.menuName}`);
      this.printer.addTextPosition(408);
      this.printer.addTextDouble(false, true);
      this.printer.addText("│");
      this.printer.addTextDouble(false, true);
      this.printer.addText("　");
      this.printer.addTextPosition(552);
      this.printer.addText("┃");
      this.printer.addTextDouble(false, false);
      this.printer.addText("\n");
      if (item?.options && item?.options.length > 0) {
        this.printer.addTextDouble(false, true);
        this.printer.addText("┃");
        this.printer.addTextDouble(false, true);
        this.printer.addTextPosition(72);
        this.printer.addText("│");
        this.printer.addTextDouble(false, true);
        this.printer.addText("　");
        this.printer.addText(
          item?.options.map((option) => option.optionName).join("、")
        );
        this.printer.addTextPosition(408);
        this.printer.addText("│");
        this.printer.addTextPosition(552);
        this.printer.addText("┃");
        this.printer.addText("\n");
      }
      this.printer.addTextDouble(false, false);
      this.printer.addText("┃　　│　　　　　　　　　　　　　│　　　　　┃\n");
    });
    this.printer.addTextDouble(false, false);
    this.printer.addText("┣━━┷━━━━━━━┯━━━━━┷━━━━━┫\n");
    this.printer.addTextDouble(false, true);
    this.printer.addText("┃　合　計　　　      │　　　　　　");
    this.printer.addText(`  ${price}  `);
    this.printer.addText("┃\n");
    this.printer.addTextDouble(false, false);
    this.printer.addText("┗━━━━━━━━━━┷━━━━━━━━━━━┛\n");
    this.printer.addTextLineSpace(30);
    this.printer.addTextAlign(this.printer.ALIGN_CENTER);
    this.printer.addText("毎度ありがとうございます\n");
    this.printer.addText("またのご来店をお待ちしております\n");
    this.printer.addTextAlign(this.printer.ALIGN_LEFT);
    this.printer.addText("\n");
    this.printer.addTextAlign(this.printer.ALIGN_CENTER);
    this.printer.addBarcode(
      `${order_number}`,
      this.printer.BARCODE_CODE39,
      this.printer.HRI_NONE,
      this.printer.FONT_A,
      2,
      64
    );
    this.printer.addTextAlign(this.printer.ALIGN_LEFT);
    this.printer.addText("\n");
    this.printer.addCut(this.printer.CUT_FEED);
    this.printer.addTextSize(1, 1);
    this.printer.addTextStyle(false, false, false, this.printer.COLOR_1);
    this.printer.addTextDouble(true, true);
    this.printer.addTextSize(1, 1);
    this.printer.send();
  }
}

class PrintQuantity {
  private readonly quantity: number;

  constructor(quantity: number) {
    this.quantity = quantity;
  }

  public getQuantity(): string {
    switch (this.quantity) {
      case 1:
        return "１";
      case 2:
        return "②";
      case 3:
        return "③";
      case 4:
        return "④";
      case 5:
        return "⑤";
      case 6:
        return "⑥";
      case 7:
        return "⑦";
      case 8:
        return "⑧";
      case 9:
        return "⑨";
      case 10:
        return "⑩";
      default:
        return "";
    }
  }
}
