<?php
declare(strict_types=1);

namespace Domain\Models;

use JsonSerializable;

class Staff implements JsonSerializable
{
    /** @var mixed */
    private $id;
    /** @var string */
    private $name;

    /** @param mixed $id */
    public function __construct($id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /** @return mixed */
    public function id() { return $this->id; }
    public function name(): string { return $this->name; }

    public static function fromRow(array $row): self
    {
        $id = $row['stf_ID'] ?? ($row['stf_id'] ?? ($row['id'] ?? $row["stf_ID"] ?? null));
        $name = $row['stf_Name'] ?? ($row['stf_name'] ?? ($row['name'] ?? $row["stf_Name"] ?? null));
        return new self($id, (string)$name);
    }

    /** @return array{id:mixed,name:string} */
    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
