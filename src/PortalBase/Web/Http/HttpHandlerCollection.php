<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract>
 */
class HttpHandlerCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract>
     */
    public function bySupport(string $path): iterable
    {
        return $this->filter(fn (HttpHandlerContract $httpHandler) => $path === $httpHandler->getPath());
    }

    protected function getT(): string
    {
        return HttpHandlerContract::class;
    }
}
