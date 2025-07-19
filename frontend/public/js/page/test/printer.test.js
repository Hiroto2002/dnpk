import { Printer } from "../printer";
describe("Printer", () => {
    let PrinterClass;
    const ipAddress = "192.168.15.21";
    const port = 8008;
    const printer = null;
    beforeEach(() => {
        PrinterClass = new Printer(ipAddress, port, printer);
    });
    it("should initialize the printer", () => {
        expect(PrinterClass).toBeDefined();
    });
    it("should print text to the printer", () => {
        PrinterClass.print(123, 1000, "Test User", "D1", 4, [
            {
                menuId: 1,
                menuName: "Test Item",
                options: [],
                quant: 2,
                allOptions: [],
            },
        ]);
    });
});
