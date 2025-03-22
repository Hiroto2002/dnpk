<?php

namespace Domain\Models\Menu;

use Domain\Models\ValueObject;
use InvalidArgumentException;

class MenuId extends ValueObject {
    /**
     * ID のバリデーションを行う
     */
    protected function validate(mixed $value): void {
        if (!is_int($value) || $value <= 0) {
            throw new InvalidArgumentException("MenuId must be a positive integer. Given: " . json_encode($value));
        }
    }
}