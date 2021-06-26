<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Psr\Container\ContainerInterface;

trait ResolveArgumentsTrait
{
    protected function resolveArguments(
        callable $method,
        ContainerInterface $container,
        callable $resolveArgument
    ): array {
        if (\is_array($method)) {
            $reflection = new \ReflectionMethod($method[0], $method[1]);
        } elseif (\is_object($method) && !$method instanceof \Closure) {
            $reflection = (new \ReflectionObject((object) $method))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($method);
        }

        $arguments = [];

        foreach ($reflection->getParameters() as $key => $param) {
            $arguments[] = $resolveArgument(
                (int) $key,
                $param->getName(),
                $this->getType($param, $reflection),
                $container
            );
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
}
