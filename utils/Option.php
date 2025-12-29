<?php
declare(strict_types=1);

namespace Utils;

use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Scala風のOption型（Some/None）。
 * - チェーン可能: map -> flatMap -> filter -> getOrElse など
 * - nullセーフ: from(null) は None、from(value) は Some(value)
 *
 * 使用例:
 *   Option::from($user)
 *     ->map(fn($u) => $u["name"])    // Option<string>
 *     ->filter(fn($n) => $n !== '')
 *     ->getOrElse('guest');           // string
 */
abstract class Option implements IteratorAggregate, JsonSerializable
{
    /** @return bool */
    abstract public function isDefined(): bool;

    /** @return bool */
    public function isEmpty(): bool
    {
        return !$this->isDefined();
    }

    /** @return mixed */
    abstract public function get();

    /**
     * 値があればそれを、なければデフォルトを返す。デフォルトはcallable可。
     * @param mixed $default
     * @return mixed
     */
    public function getOrElse($default)
    {
        if ($this->isDefined()) {
            return $this->get();
        }
        return is_callable($default) ? $default() : $default;
    }

    /**
     * 値を変換して Option に包む。
     * @param callable $f function(mixed): mixed
     */
    public function map(callable $f): self
    {
        if ($this->isDefined()) {
            return self::some($f($this->get()));
        }
        return self::none();
    }

    /**
     * 値を変換し、戻り値は Option を期待。
     * @param callable $f function(mixed): Option
     */
    public function flatMap(callable $f): self
    {
        if ($this->isDefined()) {
            $res = $f($this->get());
            if ($res instanceof self) {
                return $res;
            }
            throw new InvalidArgumentException('flatMap must return Option');
        }
        return self::none();
    }

    /**
     * プレディケートに合致しない場合は None を返す。
     * @param callable $p function(mixed): bool
     */
    public function filter(callable $p): self
    {
        if ($this->isDefined() && $p($this->get())) {
            return $this;
        }
        return self::none();
    }

    /**
     * 値があれば副作用を実行。
     * @param callable $f function(mixed): void
     */
    public function foreach(callable $f): void
    {
        if ($this->isDefined()) {
            $f($this->get());
        }
    }

    /** Optionが空なら代替Optionを返す。 */
    public function orElse(self $alt): self
    {
        return $this->isDefined() ? $this : $alt;
    }

    /** 値をそのまま、またはnullを返す。 */
    public function orNull()
    {
        return $this->getOrElse(null);
    }

    /** 空時は $ifEmpty（またはcallable）を、値があれば関数適用の結果を返す。 */
    public function fold($ifEmpty, callable $f)
    {
        if ($this->isDefined()) {
            return $f($this->get());
        }
        return is_callable($ifEmpty) ? $ifEmpty() : $ifEmpty;
    }

    /** 値を配列化（Someは[値]、Noneは[]）。 */
    public function toArray(): array
    {
        return $this->isDefined() ? [$this->get()] : [];
    }

    /** イテレーションでSomeなら1要素、Noneなら0要素。 */
    public function getIterator(): Traversable
    {
        foreach ($this->toArray() as $v) {
            yield $v;
        }
    }

    /** JSONは値またはnullとして表現。 */
    public function jsonSerialize(): mixed
    {
        return $this->orNull();
    }

    /** 値から Option を作る。null の場合は None。 */
    public static function from($value): self
    {
        return $value === null ? self::none() : self::some($value);
    }

    /** Some を生成。 */
    public static function some($value): self
    {
        return new Some($value);
    }

    /** None（シングルトン）を返す。 */
    public static function none(): self
    {
        return None::instance();
    }

    /** パターンマッチ風：値があれば $onSome、なければ $onNone を返す。 */
    public function match(callable $onSome, callable $onNone)
    {
        return $this->isDefined() ? $onSome($this->get()) : $onNone();
    }
}

final class Some extends Option
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isDefined(): bool
    {
        return true;
    }

    public function get()
    {
        return $this->value;
    }
}

final class None extends Option
{
    private static ?self $instance = null;

    private function __construct() {}

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isDefined(): bool
    {
        return false;
    }

    public function get()
    {
        throw new InvalidArgumentException('None.get');
    }
}

