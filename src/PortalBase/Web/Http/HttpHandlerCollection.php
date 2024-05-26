<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<HttpHandlerContract>
 */
class HttpHandlerCollection extends AbstractObjectCollection
{
    public function bySupport(string $path): static
    {
        return $this->filter(fn (HttpHandlerContract $httpHandler) => $path === $httpHandler->getPath());
    }

    protected function getT(): string
    {
        return HttpHandlerContract::class;
    }
}
