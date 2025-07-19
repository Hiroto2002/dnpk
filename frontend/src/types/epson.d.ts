// このファイルで、グローバルに存在する epson オブジェクトの型を宣言する

declare namespace epson {
  class ePOSDevice {
    constructor();
    readonly DEVICE_TYPE_SCANNER: string;
    readonly DEVICE_TYPE_KEYBOARD: string;
    readonly DEVICE_TYPE_POSKEYBOARD: string;
    readonly DEVICE_TYPE_MSR: string;
    readonly DEVICE_TYPE_CAT: string;
    readonly DEVICE_TYPE_CASH_CHANGER: string;
    readonly DEVICE_TYPE_PRINTER: string;
    readonly DEVICE_TYPE_DISPLAY: string;
    readonly DEVICE_TYPE_SIMPLE_SERIAL: string;
    readonly DEVICE_TYPE_HYBRID_PRINTER: string;
    readonly DEVICE_TYPE_HYBRID_PRINTER2: string;
    readonly DEVICE_TYPE_DT: string;
    readonly DEVICE_TYPE_OTHER_PERIPHERAL: string;
    readonly DEVICE_TYPE_GFE: string;
    readonly RESULT_OK: string;
    readonly ERROR_SYSTEM: string;
    readonly ERROR_DEVICE_IN_USE: string;
    readonly ERROR_DEVICE_OPEN: string;
    readonly ERROR_DEVICE_CLOSE: string;
    readonly ERROR_DEVICE_NOT_OPEN: string;
    readonly ERROR_DEVICE_NOT_FOUND: string;
    readonly ERROR_PARAMETER: string;
    readonly IFPORT_EPOSDEVICE: number;
    readonly IFPORT_EPOSDEVICE_S: number;
    readonly CONNECT_TIMEOUT: number;
    readonly RECONNECT_TIMEOUT: number;
    readonly MAX_RECONNECT_RETRY: number;
    connect(
      ipAddress: string,
      port: number,
      callback: (result: string) => void
    ): void;
    createDevice(
      deviceId: string,
      deviceType: string,
      options: any,
      callback: (deviceObj: any, returnCode: string) => void
    ): void;
  }
}
