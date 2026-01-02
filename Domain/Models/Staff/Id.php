<?php

namespace Domain\Models\Staff;

use Domain\Models\ValueObject;
use InvalidArgumentException;

class Id extends ValueObject {
    /**
     * ID のバリデーションを行う
     */
    protected function validate($value): void {
        if (!is_int($value) || $value <= 0) {
            throw new InvalidArgumentException("StaffId must be a positive integer. Given: " . json_encode($value));
        }
    }
}
