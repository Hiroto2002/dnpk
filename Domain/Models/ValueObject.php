<?php

namespace Domain\Models;

abstract class ValueObject {
/**
 * @var mixed
 */
protected $value;

    public function __construct($value) {
        $this->validate($value);
        $this->value = $value;
    }

    abstract protected function validate($value): void;

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