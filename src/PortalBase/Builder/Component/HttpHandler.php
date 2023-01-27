<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HttpHandler extends HttpHandlerContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private string $path;

    private ?\Closure $runMethod;

    private ?\Closure $optionsMethod;

    private ?\Closure $getMethod;

    private ?\Closure $postMethod;

    private ?\Closure $putMethod;

    private ?\Closure $patchMethod;

    private ?\Closure $deleteMethod;

    public function __construct(HttpHandlerToken $token)
    {
        $this->path = $token->getPath();
        $this->runMethod = $token->getRun();
        $this->optionsMethod = $token->getOptions();
        $this->getMethod = $token->getGet();
        $this->postMethod = $token->getPost();
        $this->putMethod = $token->getPut();
        $this->patchMethod = $token->getPatch();
        $this->deleteMethod = $token->getDelete();
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod;
    }

    public function getOptionsMethod(): ?\Closure
    {
        return $this->optionsMethod;
    }

    public function getGetMethod(): ?\Closure
    {
        return $this->getMethod;
    }

    public function getPostMethod(): ?\Closure
    {
        return $this->postMethod;
    }

    public function getPatchMethod(): ?\Closure
    {
        return $this->patchMethod;
    }

    public function getPutMethod(): ?\Closure
    {
        return $this->putMethod;
    }

    public function getDeleteMethod(): ?\Closure
    {
        return $this->deleteMethod;
    }

    protected function supports(): string
    {
        return $this->path;
    }

    protected function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->runMethod, 'run', $request, $response, $context)
            ?? parent::run($request, $response, $context);
    }

    protected function options(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->optionsMethod, 'options', $request, $response, $context)
            ?? parent::options($request, $response, $context);
    }

    protected function get(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->getMethod, 'get', $request, $response, $context)
            ?? parent::get($request, $response, $context);
    }

    protected function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->postMethod, 'post', $request, $response, $context)
            ?? parent::post($request, $response, $context);
    }

    protected function patch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->patchMethod, 'patch', $request, $response, $context)
            ?? parent::patch($request, $response, $context);
    }

    protected function put(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->putMethod, 'put', $request, $response, $context)
            ?? parent::put($request, $response, $context);
    }

    protected function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->deleteMethod, 'delete', $request, $response, $context)
            ?? parent::delete($request, $response, $context);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    private function resolveAndRunClosure(
        ?\Closure $closure,
        string $methodName,
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ?ResponseInterface {
        if (!$closure instanceof \Closure) {
            return null;
        }

        $callable = $this->bindThis($closure);
        $arguments = $this->resolveArguments($callable, $context, function (
            int $_,
            string $propertyName,
            ?string $propertyType,
            ContainerInterface $container
        ) use ($context, $response, $request) {
            if (\is_string($propertyType) && (\class_exists($propertyType) || \interface_exists($propertyType))) {
                if (\is_a($request, $propertyType, false)) {
                    return $request;
                }

                if (\is_a($response, $propertyType, false)) {
                    return $response;
                }

                if (\is_a($context, $propertyType, false)) {
                    return $context;
                }
            }

            return $this->resolveFromContainer($container, $propertyType, $propertyName);
        });

        $result = $callable(...$arguments);

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        throw new InvalidResultException(1637440327, 'HttpHandler', $methodName, ResponseInterface::class);
    }
}
