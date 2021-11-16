<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Closure;
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
        $run = $token->getRun();
        $options = $token->getOptions();
        $get = $token->getGet();
        $post = $token->getPost();
        $put = $token->getPut();
        $patch = $token->getPatch();
        $delete = $token->getDelete();

        $this->path = $token->getPath();
        $this->runMethod = $run instanceof Closure ? new SerializableClosure($run) : null;
        $this->optionsMethod = $options instanceof Closure ? new SerializableClosure($options) : null;
        $this->getMethod = $get instanceof Closure ? new SerializableClosure($get) : null;
        $this->postMethod = $post instanceof Closure ? new SerializableClosure($post) : null;
        $this->putMethod = $put instanceof Closure ? new SerializableClosure($put) : null;
        $this->patchMethod = $patch instanceof Closure ? new SerializableClosure($patch) : null;
        $this->deleteMethod = $delete instanceof Closure ? new SerializableClosure($delete) : null;
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

        return $callable(...$arguments);
    }
}
