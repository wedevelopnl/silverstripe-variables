<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Admin;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataQuery;
use WeDevelop\Variables\Model\Reference;
use WeDevelop\Variables\Model\Variable;

class VariableAdmin extends ModelAdmin
{
    private static string $url_segment = 'variables';

    private static string $menu_title = 'Variables';

    private static array $managed_models = [
        Variable::class,
    ];

    public function getList(): DataList
    {
        return parent::getList()->alterDataQuery(static function (DataQuery $query) {
            $query
                ->leftJoin('Variables_Reference', '"Variables_Variable"."ID" = "Variables_Reference"."VariableID"')
                ->groupBy('"Variables_Variable"."ID"')
                ->selectField('MAX("Variables_Reference"."LastUsed")', 'LastUsed');
        });
    }
}
