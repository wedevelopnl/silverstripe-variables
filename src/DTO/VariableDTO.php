<?php

declare(strict_types=1);

namespace WeDevelop\Variables\DTO;

use SilverStripe\ORM\DataObject;

final class VariableDTO
{
    private int $id;
    private string $name;
    private ?string $value;

    public function __construct(
        int $id,
        string $name,
        ?string $value
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    public static function create(int $id, string $name, ?string $value): self
    {
        return new self($id, $name, $value);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
