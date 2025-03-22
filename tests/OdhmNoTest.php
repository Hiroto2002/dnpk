<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Order\OdhmNo;

class OdhmNoTest extends TestCase {
    /**
     * @test
     * 不正なOdhmNoが生成された場合に例外が発生すること
     */
    public function testInvalidOdhmNo() {
        $this->expectException(InvalidArgumentException::class);
        new OdhmNo("24030101"); // 桁数が合わない
    }

    /**
     * @test
     * 初回のOdhmNoが生成されること
     */
    public function testGenerateFirstOdhmNo() {
        $odhmNo = OdhmNo::generate(null);
        $expectedPrefix = date('y') . date('m') . date('d') . "001";
        $this->assertEquals($expectedPrefix, $odhmNo->getValue());
    }

    /**
     * @test
     * 日付が同じ時、次のOdhmNoが生成されること
     */
    public function testGenerateNextOdhmNo() {
        $maxOdhmNo = date('y') . date('m') . date('d') . "015";
        $odhmNo = OdhmNo::generate($maxOdhmNo);
        $expectedNext = date('y') . date('m') . date('d') . "016";
        $this->assertEquals($expectedNext, $odhmNo->getValue());
    }

    /**
     * @test
     * 日付が違う時、初回のOdhmNoが生成されること
     */
    public function testGenerateFirstOdhmNoNextDay() {
        $maxOdhmNo = date('y') . date('m') . date('d')-1 . "015";
        $odhmNo = OdhmNo::generate($maxOdhmNo);
        $expectedPrefix = date('y') . date('m') . date('d') . "001";
        $this->assertEquals($expectedPrefix, $odhmNo->getValue());
    }

    /**
     * @test
     * 桁が溢れた際に例外が発生すること
     */
    public function testGenerateNextOdhNoOverflow() {
        $this->expectException(InvalidArgumentException::class);
        $maxOdhmNo = date('y') . date('m') . date('d') . "999";
        OdhmNo::generate($maxOdhmNo);
    }
}