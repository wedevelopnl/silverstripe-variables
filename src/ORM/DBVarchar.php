<?php

declare(strict_types=1);

namespace WeDevelop\Variables\ORM;

use SilverStripe\Core\Extension;

class DBVarchar extends \SilverStripe\ORM\FieldType\DBVarchar
{
    use DBVariable;
}
