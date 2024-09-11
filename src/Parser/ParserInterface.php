<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Parser;

use SilverStripe\Forms\FieldList;

interface ParserInterface
{
    public function getCMSFields(): array;

    public function process(array $args): ?string;
}
