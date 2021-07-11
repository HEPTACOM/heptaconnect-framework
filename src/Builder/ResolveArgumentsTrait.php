<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Container\ContainerInterface;

trait ResolveArgumentsTrait
{
    private array $bindingKeyCache = [];

    protected function resolveArguments(
        callable $method,
        PortalNodeContextInterface $context,
        callable $resolveArgument
    ): array {
        /** @var ContainerInterface $container */
        $container = $context->getContainer();

        if (\is_array($method)) {
            $reflection = new \ReflectionMethod($method[0], $method[1]);
        } elseif (\is_object($method) && !$method instanceof \Closure) {
            $reflection = (new \ReflectionObject((object) $method))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($method);
        }

        $bindingKeys = $this->getBindingKeys($container);
        $arguments = [];

        foreach ($reflection->getParameters() as $key => $param) {
            $type = $this->getType($param, $reflection);
            $parameterName = $param->getName();

            if (\is_a($type, PortalNodeContextInterface::class, true)) {
                $arguments[] = $context;
            } elseif ($this->isParameterScalarish($param) && isset($bindingKeys[$parameterName])) {
                $arguments[] = $bindingKeys[$parameterName];
            } else {
                $arguments[] = $resolveArgument(
                    (int) $key,
                    $parameterName,
                    $type,
                    $container
                );
            }
        }

        return $arguments;
    }

    private function getType(\ReflectionParameter $parameter, \ReflectionFunctionAbstract $function): ?string
    {
        if (!$type = $parameter->getType()) {
            return null;
        }

        $name = $type instanceof \ReflectionNamedType ? $type->getName() : (string) $type;

        if ($function instanceof \ReflectionMethod) {
            $lcName = \strtolower($name);
            switch ($lcName) {
                case 'self':
                    return $function->getDeclaringClass()->name;
                case 'parent':
                    return ($parent = $function->getDeclaringClass()->getParentClass()) ? $parent->name : null;
            }
        }

        return $name;
    }

    private function resolveFromContainer(ContainerInterface $container, string $propertyType, string $propertyName)
    {
        if ($container->has($propertyType.' $'.$propertyName)) {
            return $container->get($propertyType.' $'.$propertyName);
        }

        return $container->get($propertyType);
    }

    private function getBindingKeys(ContainerInterface $container): array
    {
        /** @var PortalNodeKeyInterface $currentPortalNodeKey */
        $currentPortalNodeKey = $container->get(PortalNodeKeyInterface::class);

        foreach ($this->bindingKeyCache as $cacheItem) {
            $comparePortalNodeKey = $cacheItem['portalNodeKey'] ?? null;

            if (!$comparePortalNodeKey instanceof PortalNodeKeyInterface) {
                continue;
            }

            if (!$currentPortalNodeKey->equals($comparePortalNodeKey)) {
                continue;
            }

            $data = $cacheItem['data'] ?? null;

            if (\is_array($data)) {
                return $data;
            }
        }

        /** @var ConfigurationContract $config */
        $config = $container->get(ConfigurationContract::class);

        $configKeySeparators = '_.-';
        $bindingKeys = [];

        foreach ($config->keys() as $configurationName) {
            $parameterName = \str_replace(
                \str_split($configKeySeparators),
                '',
                \ucwords($configurationName, $configKeySeparators)
            );

            $bindingKeys['config'.$parameterName] = $config->get($configurationName);
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
            if (\class_exists($type)) {
                return false;
            }

            if (\in_array(\mb_strtolower($type), ['string', 'float', 'int', 'bool', 'array'], true)) {
                return true;
            }
        }

        return false;
    }

    private function getParameterTypes(?\ReflectionType $type): array
    {
        if ($type instanceof \ReflectionNamedType) {
            return [$type->getName()];
        }

        if ($type instanceof \ReflectionUnionType) {
            return \array_merge([], ...\array_map([$this, 'getParameterTypes'], $type->getTypes()));
        }

        return [];
    }
}
