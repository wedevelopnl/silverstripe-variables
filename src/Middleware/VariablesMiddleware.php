<?php

declare(strict_types=1);

namespace Wedevelop\Variables\Middleware;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use WeDevelop\Variables\Factory\ReferenceFactory;

class VariablesMiddleware implements HTTPMiddleware
{
    public function process(HTTPRequest $request, callable $next): HTTPResponse
    {
        $response = $next($request);

        ReferenceFactory::singleton()->save();

        return $response;
    }
}
