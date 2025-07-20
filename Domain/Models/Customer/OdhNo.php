<?php

namespace Domain\Models\Customer;

use Domain\Models\ValueObject;
use InvalidArgumentException;

class OdhNo extends ValueObject {
    protected function validate($value): void {
        if (!preg_match('/^\d{6}\d{3}$/', $value)) {
            throw new InvalidArgumentException("Invalid OdhNo format: $value");
        }
    }

    public static function generate(?string $maxOdhNo = null): self {
        $year = date('y'); 
        $month = date('m');
        $day = date('d');
        $date = $year . $month . $day;
        $odhNo = $date . "001"; 

        if ($maxOdhNo && preg_match('/^(\d{6})(\d{3})$/', $maxOdhNo, $matches)) {
            $lastDate = $matches[1]; // `YYMMDD` 部分を取得
            $sequence = (int) $matches[2]; // `XXX` 部分を取得
        
            if ($lastDate === $date) {
                $sequence += 1; // 同じ日ならカウントアップ
            } else {
                $sequence = 1; // 日付が変わったらリセット
            }
        
            $odhNo = $date . str_pad($sequence, 3, "0", STR_PAD_LEFT);
        }

        return new self($odhNo);
    }
}