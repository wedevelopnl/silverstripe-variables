<?php

declare(strict_types=1);

namespace WeDevelop\Variables\ORM;

use SilverStripe\Core\Extension;

class DBText extends \SilverStripe\ORM\FieldType\DBText
{
    use DBVariable;
}
