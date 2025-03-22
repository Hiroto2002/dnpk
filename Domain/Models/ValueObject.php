<?php

namespace Domain\Models;

abstract class ValueObject {
    protected mixed $value;

    public function __construct(mixed $value) {
        $this->validate($value);
        $this->value = $value;
    }

    abstract protected function validate(mixed $value): void;

    public function getValue(): mixed {
        return $this->value;
    }
    
    public function __toString(): string {
        return (string)$this->value;
    }

    public function equals(ValueObject $other): bool {
        return get_class($this) === get_class($other) && json_encode($this->value) === json_encode($other->getValue());
    }
}

// 抽象メソッドの役割はinterfaceのように、
// このような形のメソッドが必要で具体的な実装は継承先に任せるというもので、
// abstractは別に具体的なメソッドを持たせることもできるという認識
// protectedメソッド: そのクラス自身とそのサブクラスからのみアクセス可能