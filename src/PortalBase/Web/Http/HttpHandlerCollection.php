<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;

/**
 * @extends AbstractObjectCollection<HttpHandlerContract>
 */
class HttpHandlerCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<int, HttpHandlerContract>
     */
    public function bySupport(string $path): iterable
    {
        return $this->filter(fn (HttpHandlerContract $httpHandler) => $path === $httpHandler->getPath());
    }

    /**
     * @psalm-return Contract\HttpHandlerContract::class
     */
    protected function getT(): string
    {
        return HttpHandlerContract::class;
    }
}
