<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Order\OdhmQuant;

class OdhmQuantTest extends TestCase {
    /**
     * ✅ 正常な OdhmQuant が作成できることを確認
     */
    public function testValidOdhmQuant() {
        $odhmQuant = new OdhmQuant(10);
        $this->assertEquals(10, $odhmQuant->getValue());
    }

    /**
     * ✅ `0` や負の値を渡した場合に例外が発生することを確認
     */
    public function testInvalidOdhmQuant() {
        $this->expectException(InvalidArgumentException::class);
        new OdhmQuant(0);
    }

    public function testNegativeOdhmQuant() {
        $this->expectException(InvalidArgumentException::class);
        new OdhmQuant(-5);
    }

    /**
     * ✅ `equals()` メソッドが正しく機能することを確認
     */
    public function testEquals() {
        $odhmQuant1 = new OdhmQuant(10);
        $odhmQuant2 = new OdhmQuant(10);
        $odhmQuant3 = new OdhmQuant(20);

        $this->assertTrue($odhmQuant1->equals($odhmQuant2)); // 同じ ID は等しい
        $this->assertFalse($odhmQuant1->equals($odhmQuant3)); // 違う ID は等しくない
    }
}