<?php

namespace Domain\Models\Menu;

use Domain\Models\ValueObject;
use InvalidArgumentException;

class Amount extends ValueObject {
    /**
     * ID のバリデーションを行う
     */
    protected function validate(mixed $value): void {
        if (!is_int($value) || $value <= 0) {
            throw new InvalidArgumentException("Amount must be a positive integer. Given: " . json_encode($value));
        }
    }
}