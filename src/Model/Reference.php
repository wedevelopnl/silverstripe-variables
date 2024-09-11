<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Model;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLReadonlyField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\HTMLValue;

class Reference extends DataObject
{
    private static string $table_name = 'Variables_Reference';

    private static array $db = [
        'ParentClass' => 'Varchar',
        'ParentID' => 'Int',
        'LastUsed' => 'Datetime',
    ];

    private static array $has_one = [
        'Variable' => Variable::class,
    ];

    private static array $indexes = [
        'UniqueKey' => [
            'type' => 'unique',
            'columns' => ['VariableID', 'ParentClass', 'ParentID'],
        ],
    ];

    private static array $summary_fields = [
        'ParentClass',
        'ParentID',
        'LastUsed',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['VariableID']);
        $fields->dataFieldByName('LastUsed')->setDescription(null);

        $fields->addFieldsToTab('Root.Main', [
            HTMLReadonlyField::create('ParentLink', 'Parent', sprintf(
                '<a href="%s">View parent</a>',
                $this->getParent()->CMSEditLink(),
            )),
        ], 'LastUsed');

        return $fields;
    }

    public function getParent(): DataObject
    {
        return DataObject::get_one($this->ParentClass, $this->ParentID);
    }

    public function canCreate($member = null, $context = []): bool
    {
        return false;
    }

    public function canEdit($member = null): bool
    {
        return false;
    }

    public function canDelete($member = null): bool
    {
        return false;
    }
}
