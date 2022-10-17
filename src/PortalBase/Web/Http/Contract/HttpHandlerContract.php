<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Base class for every HTTP handler implementation with various boilerplate-reducing entrypoints rapid fast development.
 */
abstract class HttpHandlerContract
{
    /**
     * First entrypoint to handle a web request in this flow component.
     * It allows direct stack handling manipulation. @see HttpHandlerStackInterface
     * You most likely want to implement @see run instead.
     */
    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context,
        HttpHandlerStackInterface $stack
    ): ResponseInterface {
        return $this->handleNext($request, $this->handleCurrent($request, $response, $context), $context, $stack);
    }

    /**
     * Returns a normalized variation of the supported HTTP path.
     */
    final public function getPath(): string
    {
        return \ltrim($this->supports(), '/');
    }

    /**
     * Must return the HTTP path to implement.
     */
    abstract protected function supports(): string;

    /**
     * Pre-implemented stack handling for processing the next handler in the stack.
     * Expected to only be called by @see handle
     */
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

    /**
     * Pre-implemented stack handling for processing this handler in the stack.
     * Expected to only be called by @see handle
     */
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

    /**
     * The most versatile entrypoint for handling a web request without to be expected to implement stack handling.
     * There are variations of this method for popular HTTP methods (@see options, get, post, put, patch, delete) that are used according to the incoming HTTP method.
     * This is executed when this handler on the stack is expected to act.
     * It can be skipped when @see handle is implemented accordingly.
     */
    protected function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        $method = \mb_strtolower($request->getMethod());

        return match ($method) {
            'options' => $this->options($request, $response, $context),
            'get' => $this->get($request, $response, $context),
            'post' => $this->post($request, $response, $context),
            'put' => $this->put($request, $response, $context),
            'patch' => $this->patch($request, $response, $context),
            'delete' => $this->delete($request, $response, $context),
            default => $response,
        };
    }

    /**
     * The entrypoint for handling an HTTP OPTION web request.
     * For further details @see run.
     */
    protected function options(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    /**
     * The entrypoint for handling an HTTP GET web request.
     * For further details @see run.
     */
    protected function get(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    /**
     * The entrypoint for handling an HTTP POST web request.
     * For further details @see run.
     */
    protected function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    /**
     * The entrypoint for handling an HTTP PUT web request.
     * For further details @see run.
     */
    protected function put(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    /**
     * The entrypoint for handling an HTTP PATCH web request.
     * For further details @see run.
     */
    protected function patch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }

    /**
     * The entrypoint for handling an HTTP DELETE web request.
     * For further details @see run.
     */
    protected function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $response;
    }
}
