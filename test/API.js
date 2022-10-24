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
    printer.addTextFont(printer.FONT_A); 
    printer.addText('')
    printer.addCut(printer.CUT_FEED); 
    printer.send();
}