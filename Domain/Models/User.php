<?php

declare(strict_types=1);

namespace Domain\Models;

use JsonSerializable;

class User implements JsonSerializable
{
    /** @var string */
    private $id;
    /** @var string|null */
    private $name;

    public function __construct(string $id, string $name)
    {
        $this->id = trim($id);
        $this->name = trim($name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = trim($name);
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}