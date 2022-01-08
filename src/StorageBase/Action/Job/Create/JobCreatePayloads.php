<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayload>
 */
class JobCreatePayloads extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return JobCreatePayload::class;
    }
}
