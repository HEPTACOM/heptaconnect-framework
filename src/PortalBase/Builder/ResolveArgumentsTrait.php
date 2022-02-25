<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait ResolveArgumentsTrait
{
    /**
     * @var array[]
     */
    private array $bindingKeyCache = [];

    /**
     * @param callable(int,string,?string,ContainerInterface):mixed $resolveArgument
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function resolveArguments(
        \Closure $method,
        PortalNodeContextInterface $context,
        callable $resolveArgument
    ): array {
        $container = $context->getContainer();
        $reflection = new \ReflectionFunction($method);
        $bindingKeys = $this->getBindingKeys($container);
        $arguments = [];

        foreach ($reflection->getParameters() as $key => $param) {
            $type = $this->getType($param, $reflection);
            $parameterName = $param->getName();

            if (\is_string($type) && \is_a($type, PortalNodeContextInterface::class, true)) {
                $arguments[] = $context;
            } elseif ($this->isParameterScalarish($param) && isset($bindingKeys[$parameterName])) {
                $arguments[] = $bindingKeys[$parameterName];
            } else {
                $arguments[] = $resolveArgument($key, $parameterName, $type, $container);
            }
        }

        return $arguments;
    }

    private function getType(\ReflectionParameter $parameter, \ReflectionFunctionAbstract $function): ?string
    {
        if (!($type = $parameter->getType()) instanceof \ReflectionType) {
            return null;
        }

        $name = $type instanceof \ReflectionNamedType ? $type->getName() : (string) $type;

        if ($function instanceof \ReflectionMethod) {
            switch (\mb_strtolower($name)) {
                case 'self':
                    return $function->getDeclaringClass()->name;
                case 'parent':
                    return ($parent = $function->getDeclaringClass()->getParentClass()) instanceof \ReflectionClass ? $parent->name : null;
            }
        }

        return $name;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @return mixed|null
     */
    private function resolveFromContainer(ContainerInterface $container, ?string $propertyType, string $propertyName)
    {
        if ($propertyType === null) {
            return null;
        }

        if ($container->has($propertyType . ' $' . $propertyName)) {
            return $container->get($propertyType . ' $' . $propertyName);
        }

        return $container->get($propertyType);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @return array<string, mixed>
     */
    private function getBindingKeys(ContainerInterface $container): array
    {
        /** @var PortalNodeKeyInterface $currentPortalNodeKey */
        $currentPortalNodeKey = $container->get(PortalNodeKeyInterface::class);

        foreach ($this->bindingKeyCache as $cacheItem) {
            /** @var PortalNodeKeyInterface|null $comparePortalNodeKey */
            $comparePortalNodeKey = $cacheItem['portalNodeKey'] ?? null;

            if (!$comparePortalNodeKey instanceof PortalNodeKeyInterface) {
                continue;
            }

            if (!$currentPortalNodeKey->equals($comparePortalNodeKey)) {
                continue;
            }

            /** @var array<string, mixed>|null $data */
            $data = $cacheItem['data'] ?? null;

            if (\is_array($data)) {
                return $data;
            }
        }

        /** @var ConfigurationContract $config */
        $config = $container->get(ConfigurationContract::class);

        $configKeySeparators = '_.-';
        /** @var array<string, mixed> $bindingKeys */
        $bindingKeys = [];

        foreach ($config->keys() as $configurationName) {
            $parameterName = \str_replace(
                \str_split($configKeySeparators),
                '',
                \ucwords($configurationName, $configKeySeparators)
            );

            /* @var array<string, mixed> */
            $bindingKeys['config' . $parameterName] = $config->get($configurationName);
        }

        $this->bindingKeyCache[] = [
            'portalNodeKey' => $currentPortalNodeKey,
            'data' => $bindingKeys,
        ];

        return $bindingKeys;
    }

    private function isParameterScalarish(\ReflectionParameter $parameter): bool
    {
        foreach ($this->getParameterTypes($parameter->getType()) as $type) {
            if (\class_exists($type) || \interface_exists($type)) {
                return false;
            }

            if (\in_array(\mb_strtolower($type), ['string', 'float', 'int', 'bool', 'array'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    private function getParameterTypes(?\ReflectionType $type): array
    {
        if ($type instanceof \ReflectionNamedType) {
            return [$type->getName()];
        }

        if (\class_exists(\ReflectionUnionType::class) && $type instanceof \ReflectionUnionType) {
            return \array_merge([], ...\array_map([$this, 'getParameterTypes'], $type->getTypes()));
        }

        return [];
    }
}
