<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Factory;

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBDatetime;
use WeDevelop\Variables\DTO\ReferenceDTO;
use WeDevelop\Variables\Model\Reference;

class ReferenceFactory
{
    use Injectable;

    /** @var array<ReferenceDTO>  */
    protected array $references = [];

    public function create(int $id, string $name, DataObject $parent): void
    {
        $this->references[] = ReferenceDTO::create($id, $name, $parent);
    }

    public function save(): void
    {
        if (!$this->references) {
            return;
        }

        $now = DBDatetime::now()->Rfc2822();
        $values = implode('),(', array_map(function (ReferenceDTO $reference) use ($now) {
            return sprintf("'%s'", implode("','", [
                DB::get_conn()->escapeString(Reference::class),
                $now,
                $now,
                DB::get_conn()->escapeString($reference->getParentClass()),
                $reference->getParentId(),
                $reference->getId(),
                $now,
            ]));
        }, $this->references));

        DB::query(<<<SQL
            INSERT INTO Variables_Reference (ClassName, LastEdited, Created, ParentClass, ParentID, VariableID, LastUsed)
            VALUES ($values)
            ON DUPLICATE KEY UPDATE LastUsed = '$now';
        SQL);
    }
}
