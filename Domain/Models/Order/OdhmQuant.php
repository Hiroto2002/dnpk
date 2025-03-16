<?php

namespace Domain\Models\Order;

use Domain\Models\ValueObject;
use InvalidArgumentException;

class OdhmQuant extends ValueObject {
    protected function validate(mixed $value): void {
        if (!is_int($value) || $value <= 0) {
            throw new InvalidArgumentException("MenuId must be a positive integer. Given: " . json_encode($value));
        }
    }
}