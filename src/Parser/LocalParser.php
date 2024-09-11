<?php

declare(strict_types=1);

namespace WeDevelop\Variables\Parser;

use WeDevelop\Variables\Model\Variable;

class LocalParser implements ParserInterface
{
    public function getCMSFields(): array
    {
        return [];
    }

    public function process(array $args): string
    {
        [$name] = $args;
        $name = sprintf('Local::%s', $name);

        $variable = Variable::get()->find('Name', $name);
        if (!$variable) {
            throw new \Exception("Variable '$name' not found");
        }

        return $variable->Value;
    }
}
