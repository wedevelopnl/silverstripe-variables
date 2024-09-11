<?php

declare(strict_types=1);

namespace Wedevelop\Variables\ORM;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ViewableData_Customised;
use WeDevelop\Variables\Factory\VariableFactory;

trait DBVariable
{
    private ?VariableFactory $factory = null;

    function forTemplate(): ?string
    {
        $data = parent::forTemplate();

        if (Controller::has_curr() && Controller::curr() instanceof LeftAndMain) {
            return $data;
        }

        $parent = SSViewer::topLevel();
        $parent = $parent instanceof ContentController ? $parent->data() : $parent;
//        if ($parent instanceof ViewableData_Customised) {
//            $parent = new $parent->ClassName(['ID' => $parent->ID]);
//        }

        $data = preg_replace_callback('/\[([A-Za-z0-9_]+::[A-Za-z0-9_:]+)\]/', function (array $matches) use ($parent) {
            $key = $matches[1];
            $factory = $this->getFactory();

            if (!$factory->has($key)) {
                $factory->create($key);
            }

            return $factory->get($key, $parent)->getValue();
        }, $data);

        return $data;
    }

    public function getFactory(): VariableFactory
    {
        if (!$this->factory) {
            $this->factory = VariableFactory::singleton();
        }

        return $this->factory;
    }
}
