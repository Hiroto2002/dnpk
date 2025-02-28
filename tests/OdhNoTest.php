<?php

use PHPUnit\Framework\TestCase;
use Domain\Models\Order\OdhNo;

class OdhNoTest extends TestCase {
    /**
     * @test
     * 不正なOdhNoが生成された場合に例外が発生すること
     */
    public function testInvalidOdhNo() {
        $this->expectException(InvalidArgumentException::class);
        new OdhNo("24030101"); // 桁数が合わない
    }

    /**
     * @test
     * 初回のOdhNoが生成されること
     */
    public function testGenerateFirstOdhNo() {
        $OdhNo = OdhNo::generate(null);
        $expectedPrefix = date('y') . date('m') . date('d') . "001";
        $this->assertEquals($expectedPrefix, $OdhNo->getValue());
    }

    /**
     * @test
     * 日付が同じ時、次のOdhNoが生成されること
     */
    public function testGenerateNextOdhNo() {
        $maxOdhNo = date('y') . date('m') . date('d') . "015";
        $OdhNo = OdhNo::generate($maxOdhNo);
        $expectedNext = date('y') . date('m') . date('d') . "016";
        $this->assertEquals($expectedNext, $OdhNo->getValue());
    }

    /**
     * @test
     * 日付が違う時、初回のOdhNoが生成されること
     */
    public function testGenerateFirstOdhNoNextDay() {
        $maxOdhNo = date('y') . date('m') . date('d')-1 . "015";
        $OdhNo = OdhNo::generate($maxOdhNo);
        $expectedPrefix = date('y') . date('m') . date('d') . "001";
        $this->assertEquals($expectedPrefix, $OdhNo->getValue());
    }

    /**
     * @test
     * 桁が溢れた際に例外が発生すること
     */
    public function testGenerateNextOdhNoOverflow() {
        $this->expectException(InvalidArgumentException::class);
        $maxOdhNo = date('y') . date('m') . date('d') . "999";
        OdhNo::generate($maxOdhNo);
    }
}