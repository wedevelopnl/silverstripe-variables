<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Factory;

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBDatetime;
use WeDevelop\Variables\DTO\VariableDTO;
use WeDevelop\Variables\Model\Reference;
use WeDevelop\Variables\Model\Variable;

class VariableFactory
{
    use Injectable;

    protected ReferenceFactory $references;

    protected array $variables;

    public function __construct()
    {
        $this->references = ReferenceFactory::singleton();
        $this->variables = [];

        foreach (DB::query('SELECT "ID", "Name", "Value" FROM "Variables_Variable"') as $variable) {
            $this->variables[$variable['Name']] = VariableDTO::create(
                $variable['ID'],
                $variable['Name'],
                $variable['Value'],
            );
        }
    }

    public function create(string $name): void
    {
        $variable = Variable::create();
        $variable->Name = $name;
        $variable->Value = $variable->parse();
        $variable->write();

        $this->variables[$variable->Name] = VariableDTO::create(
            $variable->ID,
            $variable->Name,
            $variable->Value,
        );
    }

    public function get(string $name, DataObject $parent): VariableDTO
    {
        $this->references->create($this->variables[$name]->getId(), $name, $parent);

        return $this->variables[$name];
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->variables);
    }
}
