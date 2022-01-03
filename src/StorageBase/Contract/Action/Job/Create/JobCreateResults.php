<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Create\JobCreateResult>
 */
class JobCreateResults extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return JobCreateResult::class;
    }
}
