<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

class ChildClass extends ParentClass
{
    public $publicChildProperty = 'publicChildProperty';

    protected $protectedChildProperty = 'protectedChildProperty';

    private $privateChildProperty = 'privateChildProperty';
}
