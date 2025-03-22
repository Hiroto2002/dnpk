<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Staff\Id;

class StaffIdTest extends TestCase {
    /**
     * ✅ 正常な Id が作成できることを確認
     */
    public function testValidId() {
        $Id = new Id(10);
        $this->assertEquals(10, $Id->getValue());
    }

    /**
     * ✅ `0` や負の値を渡した場合に例外が発生することを確認
     */
    public function testInvalidId() {
        $this->expectException(InvalidArgumentException::class);
        new Id(0);
    }

    public function testNegativeId() {
        $this->expectException(InvalidArgumentException::class);
        new Id(-5);
    }

    /**
     * ✅ `equals()` メソッドが正しく機能することを確認
     */
    public function testEquals() {
        $Id1 = new Id(10);
        $Id2 = new Id(10);
        $Id3 = new Id(20);

        $this->assertTrue($Id1->equals($Id2)); // 同じ ID は等しい
        $this->assertFalse($Id1->equals($Id3)); // 違う ID は等しくない
    }
}