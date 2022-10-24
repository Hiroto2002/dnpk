// var canvas = document.getElementById('canvas');
var printer = null;
var ePosDev = new epson.ePOSDevice();

ePosDev.connect('192.168.15.21', 8008, cbConnect);

function cbConnect(data) { 
    if(data == 'OK' || data == 'SSL_CONNECT_OK') { 
        ePosDev.createDevice('local_printer', ePosDev.DEVICE_TYPE_PRINTER, 
            {'crypto':false, 'buffer':false}, cbCreateDevice_printer
        );
    } else { 
        alert(data); 
    }
}

function cbCreateDevice_printer(devobj, retcode) { 
    if( retcode == 'OK' ) { 
        printer = devobj; 
        printer.timeout = 60000; 
        printer.onreceive = 
        function (res) { 
            alert(res.success); 
        }; 
        printer.oncoveropen = function () {
            alert('coveropen'); 
        }; 
        Print(); 
    } else { 
        alert(retcode); 
    }
}
function Print() { 
    printer.addTextLang('ja');
    printer.addTextSmooth(true);
    printer.addPageBegin();
    printer.addPageDirection(printer.DIRECTION_LEFT_TO_RIGHT);
    printer.addPageArea(0, 0, 288, 120);
    printer.addTextStyle(false, true, false, printer.COLOR_1);
    printer.addText('　　　　　　　　　　　　\n');
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addText('　ﾃｰﾌﾞﾙ\n\n');
    printer.addTextSize(2, 2);
    printer.addText('　D1～2\n');
    printer.addTextSize(1, 1);
    printer.addPageArea(288, 0, 288, 120);
    printer.addTextStyle(false, true, false, printer.COLOR_1);
    printer.addText('　　　　No. ');
    printer.addText('20221020001');
    printer.addText(' \n');
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addText('人数\n\n');
    printer.addTextSize(1, 2);
    printer.addText('3\n');
    printer.addTextSize(1, 1);
    printer.addPageEnd();
    printer.addTextLineSpace(24);
    printer.addText('┏━━━━━━━━━━━━━┯━━┯━━━━━┓\n');
    printer.addText('┃　　　品　　　　　名　　　│数量│　備　考　┃\n');
    printer.addText('┠─────────────┼──┼─────┨\n');
    printer.addTextDouble(false, true);
    printer.addText('┃');
    printer.addTextDouble(false, false);
    printer.addText(' ');
    printer.addTextDouble(true, true);
    printer.addText('天丼');
    printer.addTextPosition(336);
    printer.addTextDouble(false, true);
    printer.addText('│');
    printer.addTextDouble(true, true);
    printer.addText(' 2');
    printer.addTextDouble(false, true);
    printer.addText('│');
    printer.addText('　');
    printer.addTextPosition(552);
    printer.addText('┃');
    printer.addTextDouble(false, false);
    printer.addText('\n');
    printer.addTextDouble(false, true);
    printer.addText('┃');
    printer.addTextDouble(false, true);
    printer.addText('　');
    printer.addText('HﾐﾆS 天玉');
    printer.addTextDouble(false, true);
    printer.addTextPosition(336);
    printer.addText('│');
    printer.addTextPosition(408);
    printer.addText('│');
    printer.addTextPosition(552);
    printer.addText('┃');
    printer.addText('\n');
    printer.addTextDouble(false, false);
    printer.addText('┃　　　　　　　　　　　　　│　　│　　　　　┃\n');
    printer.addTextDouble(false, true);
    printer.addText('┃');
    printer.addTextDouble(false, false);
    printer.addText(' ');
    printer.addTextDouble(true, true);
    printer.addText('天丼');
    printer.addTextPosition(336);
    printer.addTextDouble(false, true);
    printer.addText('│');
    printer.addTextDouble(true, true);
    printer.addText(' 2');
    printer.addTextDouble(false, true);
    printer.addText('│');
    printer.addText('　');
    printer.addTextPosition(552);
    printer.addText('┃');
    printer.addTextDouble(false, false);
    printer.addText('\n');
    printer.addTextDouble(false, true);
    printer.addText('┃');
    printer.addTextDouble(false, true);
    printer.addText('　');
    printer.addText('HﾐﾆS 天玉');
    printer.addTextDouble(false, true);
    printer.addTextPosition(336);
    printer.addText('│');
    printer.addTextPosition(408);
    printer.addText('│');
    printer.addTextPosition(552);
    printer.addText('┃');
    printer.addText('\n');
    printer.addTextDouble(false, true);
    printer.addText('┃　　　　　　　　　　　　　│　　│　　　　　┃\n');
    printer.addText('┃　　　　　　　　　　　　　│　　│　　　　　┃\n');
    printer.addTextDouble(false, false);
    printer.addText('┣━━━━━━━━━━┯━━┷━━┷━━━━━┫\n');
    printer.addTextDouble(false, true);
    printer.addText('┃　合　計　　　      │　　　　　　');
    printer.addText('       700');
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
    printer.addBarcode('20221020001', printer.BARCODE_CODE39, printer.HRI_NONE, printer.FONT_A, 2, 64);
    printer.addTextAlign(printer.ALIGN_LEFT);
    printer.addText('\n');
    printer.addCut(printer.CUT_FEED);
    printer.addTextSize(1, 1);
    printer.addTextStyle(false, false, false, printer.COLOR_1);
    printer.addTextDouble(true, true);
    printer.addTextSize(1, 1);
    printer.send();
}









