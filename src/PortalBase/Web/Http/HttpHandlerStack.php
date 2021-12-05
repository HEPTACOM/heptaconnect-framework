<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerStackInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class HttpHandlerStack implements HttpHandlerStackInterface, LoggerAwareInterface
{
    /**
     * @var array<array-key, \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract>
     */
    private array $handlers;

    private LoggerInterface $logger;

    /**
     * @param iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract> $handlers
     */
    public function __construct(iterable $handlers)
    {
        /** @var array<array-key, \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract> $arrayHandlers */
        $arrayHandlers = \iterable_to_array($handlers);
        $this->handlers = $arrayHandlers;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function next(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        $handler = \array_shift($this->handlers);

        if (!$handler instanceof HttpHandlerContract) {
            return $response;
        }

        $this->logger->debug(\sprintf('Execute FlowComponent web http handler: %s', \get_class($handler)));

        return $handler->handle($request, $response, $context, $this);
    }
}
