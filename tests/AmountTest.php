<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Menu\Amount;

class AmountTest extends TestCase {
    /**
     * ✅ 正常な Amount が作成できることを確認
     */
    public function testValidAmount() {
        $amount = new Amount(10);
        $this->assertEquals(10, $amount->getValue());
    }

    /**
     * ✅ `0` や負の値を渡した場合に例外が発生することを確認
     */
    public function testInvalidAmount() {
        $this->expectException(InvalidArgumentException::class);
        new Amount(0);
    }

    public function testNegativeAmount() {
        $this->expectException(InvalidArgumentException::class);
        new Amount(-5);
    }

    /**
     * ✅ `equals()` メソッドが正しく機能することを確認
     */
    public function testEquals() {
        $amount1 = new Amount(10);
        $amount2 = new Amount(10);
        $amount3 = new Amount(20);

        $this->assertTrue($amount1->equals($amount2)); // 同じ ID は等しい
        $this->assertFalse($amount1->equals($amount3)); // 違う ID は等しくない
    }
}