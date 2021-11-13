<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpHandler extends HttpHandlerContract
{
    use ResolveArgumentsTrait;

    private string $path;

    private ?SerializableClosure $runMethod;

    private ?SerializableClosure $optionsMethod;

    private ?SerializableClosure $getMethod;

    private ?SerializableClosure $postMethod;

    private ?SerializableClosure $putMethod;

    private ?SerializableClosure $patchMethod;

    private ?SerializableClosure $deleteMethod;

    public function __construct(HttpHandlerToken $token)
    {
        $this->path = $token->getPath();
        $this->runMethod = \is_callable($token->getRun()) ? new SerializableClosure($token->getRun()) : null;
        $this->optionsMethod = \is_callable($token->getOptions()) ? new SerializableClosure($token->getOptions()) : null;
        $this->getMethod = \is_callable($token->getGet()) ? new SerializableClosure($token->getGet()) : null;
        $this->postMethod = \is_callable($token->getPost()) ? new SerializableClosure($token->getPost()) : null;
        $this->putMethod = \is_callable($token->getPut()) ? new SerializableClosure($token->getPut()) : null;
        $this->patchMethod = \is_callable($token->getPatch()) ? new SerializableClosure($token->getPatch()) : null;
        $this->deleteMethod = \is_callable($token->getDelete()) ? new SerializableClosure($token->getDelete()) : null;
    }

    public function supports(): string
    {
        return $this->path;
    }

    protected function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->runMethod, $request, $response, $context)
            ?? parent::run($request, $response, $context);
    }

    protected function options(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->optionsMethod, $request, $response, $context)
            ?? parent::options($request, $response, $context);
    }

    protected function get(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->getMethod, $request, $response, $context)
            ?? parent::get($request, $response, $context);
    }

    protected function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->postMethod, $request, $response, $context)
            ?? parent::post($request, $response, $context);
    }

    protected function patch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->patchMethod, $request, $response, $context)
            ?? parent::patch($request, $response, $context);
    }

    protected function put(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->putMethod, $request, $response, $context)
            ?? parent::put($request, $response, $context);
    }

    protected function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface {
        return $this->resolveAndRunClosure($this->deleteMethod, $request, $response, $context)
            ?? parent::delete($request, $response, $context);
    }

    private function resolveAndRunClosure(
        ?SerializableClosure $closure,
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ?ResponseInterface {
        if (!$closure instanceof SerializableClosure) {
            return null;
        }

        $callable = $closure->getClosure();

        if (!\is_callable($callable)) {
            return null;
        }

        $arguments = $this->resolveArguments($callable, $context, function (
            int $_,
            string $propertyName,
            string $propertyType,
            ContainerInterface $container
        ) use ($context, $response, $request) {
            if (\is_a($request, $propertyType, false)) {
                return $request;
            }

            if (\is_a($response, $propertyType, false)) {
                return $response;
            }

            if (\is_a($context, $propertyType, false)) {
                return $context;
            }

            return $this->resolveFromContainer($container, $propertyType, $propertyName);
        });

        return $callable(...$arguments);
    }
}
