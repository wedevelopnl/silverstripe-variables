<?php

declare(strict_types=1);

namespace WeDevelop\Variables\DTO;

use SilverStripe\ORM\DataObject;

final class ReferenceDTO
{
    private int $id;
    private string $name;
    private string $parentClass;
    private int $parentId;

    public function __construct(
        int $id,
        string $name,
        string $parentClass,
        int $parentId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->parentClass = $parentClass;
        $this->parentId = $parentId;
    }

    public static function create(int $id, string $name, DataObject $parent): self
    {
        return new self($id, $name, $parent->ClassName, $parent->ID);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParentClass(): string
    {
        return $this->parentClass;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }
}
