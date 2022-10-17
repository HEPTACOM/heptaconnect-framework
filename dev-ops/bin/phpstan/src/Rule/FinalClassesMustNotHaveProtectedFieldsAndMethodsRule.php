<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
final class FinalClassesMustNotHaveProtectedFieldsAndMethodsRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->isFinal()) {
            return [];
        }

        $reflectionClass = $this->reflectionProvider->getClass($scope->getNamespace() . '\\' . $node->name);
        $parentMethods = [];
        $parentConsts = [];
        $parentProperties = [];

        foreach ($reflectionClass->getInterfaces() as $interface) {
            $interfaceRefl = $interface->getNativeReflection();

            foreach ($interfaceRefl->getMethods() as $method) {
                $name = $method->getName();
                $parentMethods[$name] = $name;
            }

            foreach (\array_keys($interfaceRefl->getReflectionConstants()) as $constant) {
                $parentConsts[$constant] = $constant;
            }

            foreach ($interfaceRefl->getProperties() as $property) {
                $name = $property->getName();
                $parentProperties[$name] = $name;
            }
        }

        foreach ($reflectionClass->getParents() as $parentClass) {
            $classRefl = $parentClass->getNativeReflection();

            foreach ($classRefl->getMethods() as $method) {
                $name = $method->getName();
                $parentMethods[$name] = $name;
            }

            foreach (\array_keys($classRefl->getReflectionConstants()) as $constant) {
                $parentConsts[$constant] = $constant;
            }

            foreach ($classRefl->getProperties() as $property) {
                $name = $property->getName();
                $parentProperties[$name] = $name;
            }
        }

        $methods = $node->getMethods();
        $methods = \array_filter($methods, static fn (ClassMethod $cm): bool => !\in_array($cm->name->toString(), $parentMethods, true));

        $constants = $node->getConstants();
        $constants = \array_filter($constants, static fn (ClassConst $const): bool => !\in_array($const->consts[0]->name->toString(), $parentConsts, true));

        $properties = $node->getProperties();
        $properties = \array_filter($properties, static fn (Property $prop): bool => !\in_array($prop->props[0]->name->toString(), $parentProperties, true));

        $result = [];

        foreach ($methods as $method) {
            if ($method->isPrivate()) {
                continue;
            }

            if ($method->isPublic()) {
                continue;
            }

            $result[] = RuleErrorBuilder::message(\sprintf(
                'Class "%s" is final but has method "%s" that is neither public nor private',
                $node->namespacedName,
                $method->name->toString()
            ))
                ->line($method->getStartLine())
                ->file($scope->getFile())
                ->build();
        }

        foreach ($constants as $constant) {
            if ($constant->isPrivate()) {
                continue;
            }

            if ($constant->isPublic()) {
                continue;
            }

            $result[] = RuleErrorBuilder::message(\sprintf(
                'Class "%s" is final but has constant "%s" that is neither public nor private',
                $node->namespacedName,
                $constant->consts[0]->name->toString()
            ))
                ->line($constant->getStartLine())
                ->file($scope->getFile())
                ->build();
        }

        foreach ($properties as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            if ($property->isPublic()) {
                continue;
            }

            $result[] = RuleErrorBuilder::message(\sprintf(
                'Class "%s" is final but has property "%s" that is neither public nor private',
                $node->namespacedName,
                $property->props[0]->name->toString()
            ))
                ->line($property->getStartLine())
                ->file($scope->getFile())
                ->build();
        }

        return $result;
    }
}
