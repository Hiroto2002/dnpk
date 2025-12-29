<?php
declare(strict_types=1);

namespace Utils;

use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Scala風のEither型。
 * - Right: 成功の値
 * - Left:  エラーや代替の値
 *
 * チェーン例:
 *   Either::right($user)
 *     ->map(fn($u) => $u['name'] ?? null)
 *     ->flatMap(fn($name) => $name ? Either::right(strtoupper($name)) : Either::left('no-name'))
 *     ->getOrElse('guest');
 */
abstract class Either implements IteratorAggregate, JsonSerializable
{
    /** @return bool */
    abstract public function isRight(): bool;

    /** @return bool */
    public function isLeft(): bool
    {
        return !$this->isRight();
    }

    /** 右値を返す（Left の場合は例外）。 */
    abstract public function get();

    /** 左値を返す（Right の場合は例外）。 */
    abstract public function getLeft();

    /** Right のときに変換、Left はそのまま。 */
    public function map(callable $f): self
    {
        if ($this->isRight()) {
            return self::right($f($this->get()));
        }
        return $this;
    }

    /** Right のときに変換し、戻り値は Either を期待。 */
    public function flatMap(callable $f): self
    {
        if ($this->isRight()) {
            $res = $f($this->get());
            if ($res instanceof self) {
                return $res;
            }
            throw new InvalidArgumentException('flatMap must return Either');
        }
        return $this;
    }

    /** Left のときだけ左値を変換。 */
    public function mapLeft(callable $f): self
    {
        if ($this->isLeft()) {
            return self::left($f($this->getLeft()));
        }
        return $this;
    }

    /** Right/Left の両方に対して同時に変換。 */
    public function bimap(callable $fl, callable $fr): self
    {
        return $this->isRight() ? self::right($fr($this->get())) : self::left($fl($this->getLeft()));
    }

    /** Right の値、またはデフォルト（callable可）。 */
    public function getOrElse($default)
    {
        if ($this->isRight()) {
            return $this->get();
        }
        return is_callable($default) ? $default() : $default;
    }

    /** Left の場合に代替Eitherを返す。 */
    public function orElse(self $alt): self
    {
        return $this->isRight() ? $this : $alt;
    }

    /** パターンマッチ風。 */
    public function fold(callable $onLeft, callable $onRight)
    {
        return $this->isRight() ? $onRight($this->get()) : $onLeft($this->getLeft());
    }

    /** Right/Left を入れ替える。 */
    public function swap(): self
    {
        return $this->isRight() ? self::left($this->get()) : self::right($this->getLeft());
    }

    /** Right を Option::some に、Left を Option::none に。 */
    public function toOption(): Option
    {
        return $this->isRight() ? Option::some($this->get()) : Option::none();
    }

    /** 反復：Right は1要素、Left は0要素。 */
    public function getIterator(): Traversable
    {
        if ($this->isRight()) {
            yield $this->get();
        }
    }

    /** JSON表現：{ right: value } または { left: value } */
    public function jsonSerialize(): mixed
    {
        return $this->isRight() ? ['right' => $this->get()] : ['left' => $this->getLeft()];
    }

    /** Right を構築。 */
    public static function right($value): self
    {
        return new Right($value);
    }

    /** Left を構築。 */
    public static function left($value): self
    {
        return new Left($value);
    }

    /**
     * try/catch を Either に変換。
     * $fn 実行が成功すれば Right、例外なら $onException($e) を Left に包む。
     */
    public static function fromCallable(callable $fn, callable $onException = null): self
    {
        try {
            return self::right($fn());
        } catch (\Throwable $e) {
            $val = $onException ? $onException($e) : $e->getMessage();
            return self::left($val);
        }
    }

    /** null/非null を Either に変換（nullならLeft($left), 非nullならRight(value)）。 */
    public static function fromNullable($value, $left)
    {
        return $value === null ? self::left(is_callable($left) ? $left() : $left) : self::right($value);
    }
}

final class Right extends Either
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isRight(): bool
    {
        return true;
    }

    public function get()
    {
        return $this->value;
    }

    public function getLeft()
    {
        throw new InvalidArgumentException('Right.getLeft');
    }
}

final class Left extends Either
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isRight(): bool
    {
        return false;
    }

    public function get()
    {
        throw new InvalidArgumentException('Left.get');
    }

    public function getLeft()
    {
        return $this->value;
    }
}

