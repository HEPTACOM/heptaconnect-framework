<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler as ShorthandHttpHandler;
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

    public function listOrigins(): array
    {
        $origins = [];
        foreach ($this->handlers as $httpHandler) {
            $origins[] = $this->getOrigin($httpHandler);
        }

        return $origins;
    }

    protected function getOrigin(HttpHandlerContract $httpHandler): string
    {
        if ($httpHandler instanceof ShorthandHttpHandler) {
            $runMethod = $httpHandler->getRunMethod();

            if ($runMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($runMethod);

                return $reflection->getFileName() . '::run:' . $reflection->getStartLine();
            }

            $optionsMethod = $httpHandler->getOptionsMethod();

            if ($optionsMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($optionsMethod);

                return $reflection->getFileName() . '::options:' . $reflection->getStartLine();
            }

            $getMethod = $httpHandler->getGetMethod();

            if ($getMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($getMethod);

                return $reflection->getFileName() . '::get:' . $reflection->getStartLine();
            }

            $postMethod = $httpHandler->getPostMethod();

            if ($postMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($postMethod);

                return $reflection->getFileName() . '::post:' . $reflection->getStartLine();
            }

            $putMethod = $httpHandler->getPutMethod();

            if ($putMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($putMethod);

                return $reflection->getFileName() . '::put:' . $reflection->getStartLine();
            }

            $patchMethod = $httpHandler->getPatchMethod();

            if ($patchMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($patchMethod);

                return $reflection->getFileName() . '::patch:' . $reflection->getStartLine();
            }

            $deleteMethod = $httpHandler->getDeleteMethod();

            if ($deleteMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($deleteMethod);

                return $reflection->getFileName() . '::delete:' . $reflection->getStartLine();
            }

            $this->logger->warning('HttpHandlerStack contains unconfigured short-notation explorer', [
                'code' => 1637607699,
            ]);
        }

        $reflection = new \ReflectionClass($httpHandler);

        return $reflection->getFileName() . ':' . $reflection->getStartLine();
    }
}
