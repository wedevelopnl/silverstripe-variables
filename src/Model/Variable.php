<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Model;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBField;
use WeDevelop\Variables\Admin\VariableAdmin;
use WeDevelop\Variables\Parser\LocalParser;
use WeDevelop\Variables\Parser\ParserInterface;

/**
 * @property string $Name
 * @property string $Value
 * @property bool $ValueException
 */
class Variable extends DataObject
{
    public const SEPERATOR = '::';

    private static string $table_name = 'Variables_Variable';

    private static array $db = [
        'Name' => 'Varchar',
        'Value' => 'Text',
        'ValueException' => 'Boolean',
    ];

    private static array $has_many = [
        'References' => Reference::class,
    ];

    private static array $indexes = [
        'UniqueName' => [
            'type' => 'unique',
            'columns' => ['Name'],
        ],
    ];

    private static array $cascade_deletes = [
        'References',
    ];

    private static array $summary_fields = [
        'Name',
        'Value',
        'ValueException' => 'Exception',
        'LastUsed',
    ];

    private static array $parsers = [
        'Local' => LocalParser::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['References']);

        $fields->replaceField('ValueException', $fields->dataFieldByName('ValueException')->performReadonlyTransformation());
        $fields->addFieldsToTab('Root.Main', $this->getVariableParser() ? $this->getVariableParser()->getCMSFields() : []);

        $fields->addFieldsToTab('Root.References', [
            GridField::create('References', 'References', $this->References(), GridFieldConfig_RecordViewer::create()),
        ]);

        return $fields;
    }

    public function getLastUsed(): ?DBDatetime
    {
        if (!$this->References() instanceof DataList) {
            return null;
        }

        return DBField::create_field(DBDatetime::class, $this->References()->Max('LastUsed'));
    }

    public function parse(): ?string
    {
        [$parser, $args] = explode(self::SEPERATOR, $this->Name, 2);

        if (!array_key_exists($parser, self::config()->parsers)) {
            throw new \Exception(sprintf('Parser %s not found', $parser));
        }

        /** @var ParserInterface $parser */
        $parser = Injector::inst()->create(self::config()->parsers[$parser]);

        try {
            $value = $parser->process(explode(self::SEPERATOR, $args));
        } catch (\Exception $e) {
            $this->ValueException = true;
            $this->write();

            return null;
        }

        return $value;
    }

    public function getVariableParser(): ?ParserInterface
    {
        if ($this->Name === null) {
            return null;
        }

        [$parser, $args] = explode(self::SEPERATOR, $this->Name, 2);

        if (!array_key_exists($parser, self::config()->parsers)) {
            throw new \Exception(sprintf('Parser %s not found', $parser));
        }

        return Injector::inst()->get(self::config()->parsers[$parser]);
    }

    public function CMSEditLink(): string
    {
        return VariableAdmin::singleton()->getCMSEditLinkForManagedDataObject($this);
    }
}
