<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class StructDetector
{
    public const STRUCT_INTERFACES = [
        \Stringable::class,
        \JsonSerializable::class,
        AttachmentAwareInterface::class,
        AttachableInterface::class,
        AuditableDataAwareInterface::class,
        ForeignKeyAwareInterface::class,
    ];

    public function isClassLikeAStruct(Class_ $class): bool
    {
        /** @var class-string[] $interfaces */
        $interfaces = $this->getNonStructInterfaces($class);

        if ($interfaces !== []) {
            return $this->hasStructInterfaces($class);
        }

        if ($class->extends !== null) {
            return false;
        }

        /** @var ClassMethod[] $setter */
        $setter = [];
        /** @var ClassMethod[] $getter */
        $getter = [];

        foreach ($class->getMethods() as $method) {
            if ($method->isMagic()) {
                continue;
            }

            if ($method->isPrivate()) {
                return false;
            }

            if ($method->isStatic()) {
                continue;
            }

            if (((string) $method->name) === 'jsonSerialize') {
                continue;
            }

            /* @see AuditableDataAwareInterface */
            if (((string) $method->name) === 'getAuditableData') {
                continue;
            }

            if (\str_starts_with((string) $method->name, 'with')) {
                $setter[] = \lcfirst(\mb_substr((string) $method->name, 4));

                continue;
            }

            if (\str_starts_with((string) $method->name, 'set')) {
                $setter[] = \lcfirst(\mb_substr((string) $method->name, 3));

                continue;
            }

            if (\str_starts_with((string) $method->name, 'get')) {
                $getter[] = \lcfirst(\mb_substr((string) $method->name, 3));

                continue;
            }

            return false;
        }

        $constructor = $class->getMethod('__construct');

        if ($constructor instanceof ClassMethod) {
            /** @var Param $param */
            foreach ($constructor->getParams() as $param) {
                $paramVar = $param->var;

                if ($paramVar instanceof Error) {
                    throw new \LogicException('Unexpected error type');
                }

                $paramName = $paramVar->name;

                if (!\is_string($paramName)) {
                    $setter[] = $paramName;
                }
            }
        }

        if ($getter === [] || $setter === []) {
            return false;
        }

        $getter = \array_unique($getter);
        $setter = \array_unique($setter);

        \sort($getter);
        \sort($setter);

        return $getter === $setter;
    }

    public function getNonStructInterfaces(Class_ $class): array
    {
        $interfaces = \array_map('strval', $class->implements);
        $interfaces = \array_diff($interfaces, self::STRUCT_INTERFACES);

        return \array_filter($interfaces, [self::class, 'classOrInterfaceHasNoMethods']);
    }

    public function hasStructInterfaces(Class_ $class): bool
    {
        $interfaces = \array_map('strval', $class->implements);
        $interfaces = \array_intersect($interfaces, self::STRUCT_INTERFACES);

        return $interfaces !== [];
    }

    /**
     * @param class-string $classOrInterface
     */
    private static function classOrInterfaceHasNoMethods(string $classOrInterface): bool
    {
        return (new \ReflectionClass($classOrInterface))->getMethods() !== [];
    }
}
