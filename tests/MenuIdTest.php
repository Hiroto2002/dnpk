<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Menu\MenuId;

class MenuIdTest extends TestCase {
    /**
     * ✅ 正常な MenuId が作成できることを確認
     */
    public function testValidMenuId() {
        $menuId = new MenuId(10);
        $this->assertEquals(10, $menuId->getValue());
    }

    /**
     * ✅ `0` や負の値を渡した場合に例外が発生することを確認
     */
    public function testInvalidMenuId() {
        $this->expectException(InvalidArgumentException::class);
        new MenuId(0);
    }

    public function testNegativeMenuId() {
        $this->expectException(InvalidArgumentException::class);
        new MenuId(-5);
    }

    /**
     * ✅ `equals()` メソッドが正しく機能することを確認
     */
    public function testEquals() {
        $menuId1 = new MenuId(10);
        $menuId2 = new MenuId(10);
        $menuId3 = new MenuId(20);

        $this->assertTrue($menuId1->equals($menuId2)); // 同じ ID は等しい
        $this->assertFalse($menuId1->equals($menuId3)); // 違う ID は等しくない
    }
}