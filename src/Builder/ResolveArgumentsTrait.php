<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Psr\Container\ContainerInterface;

trait ResolveArgumentsTrait
{
    protected function resolveArguments(
        callable $method,
        PortalNodeContextInterface $context,
        callable $resolveArgument
    ): array {
        $container = $context->getContainer();

        if (\is_array($method)) {
            $reflection = new \ReflectionMethod($method[0], $method[1]);
        } elseif (\is_object($method) && !$method instanceof \Closure) {
            $reflection = (new \ReflectionObject((object) $method))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($method);
        }

        $arguments = [];

        foreach ($reflection->getParameters() as $key => $param) {
            $type = $this->getType($param, $reflection);

            if (\is_a($type, PortalNodeContextInterface::class, true)) {
                $arguments[] = $context;
            } else {
                $arguments[] = $resolveArgument(
                    (int) $key,
                    $param->getName(),
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
}
