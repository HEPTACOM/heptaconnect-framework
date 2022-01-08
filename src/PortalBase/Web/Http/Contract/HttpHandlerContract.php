<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

abstract class HttpHandlerContract
{
    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context,
        HttpHandlerStackInterface $stack
    ): ResponseInterface {
        return $this->handleNext($request, $this->handleCurrent($request, $response, $context), $context, $stack);
    }

    final public function getPath(): string
    {
        return \ltrim($this->supports(), '/');
    }

    abstract protected function supports(): string;

    final protected function handleNext(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context,
        HttpHandlerStackInterface $stack
    ): ResponseInterface {
        try {
            return $stack->next($request, $response, $context);
        } catch (\Throwable $throwable) {
            /** @var LoggerInterface|null $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);

            if ($logger instanceof LoggerInterface) {
                $logger->error('handleNext failed', [
                    'code' => 1636735335,
                    'path' => $this->getPath(),
                    'request' => $request,
                    'response' => $response,
                    'exception' => $throwable,
                ]);
            }

            throw $throwable;
        }
    }

    final protected function handleCurrent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        try {
            return $this->run($request, $response, $context);
        } catch (\Throwable $throwable) {
            /** @var LoggerInterface|null $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);

            if ($logger instanceof LoggerInterface) {
                $logger->error('handleCurrent failed', [
                    'code' => 1636735336,
                    'path' => $this->getPath(),
                    'request' => $request,
                    'response' => $response,
                    'exception' => $throwable,
                ]);
            }

            throw $throwable;
        }
    }

    protected function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        $method = \mb_strtolower($request->getMethod());

        switch ($method) {
            case 'options':
                return $this->options($request, $response, $context);
            case 'get':
                return $this->get($request, $response, $context);
            case 'post':
                return $this->post($request, $response, $context);
            case 'put':
                return $this->put($request, $response, $context);
            case 'patch':
                return $this->patch($request, $response, $context);
            case 'delete':
                return $this->delete($request, $response, $context);
        }

        return $response;
    }

    protected function options(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    protected function get(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    protected function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    protected function put(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    protected function patch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    protected function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }
}
