<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class ImplementationsMustBeFinalRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAnonymous()) {
            return [];
        }

        if ($node->isAbstract()) {
            return [];
        }

        if ($node->isFinal()) {
            return [];
        }

        if ($node->extends !== null) {
            $extends = (string) $node->extends;

            if (\str_ends_with($extends, 'Contract')) {
                return [
                    RuleErrorBuilder::message('Classes that extend a contract must be final or abstract')
                        ->line($node->getStartLine())
                        ->file($scope->getFile())
                        ->build(),
                ];
            }
        }

        if ($node->implements === []) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Classes that implement an interface must be final or abstract')
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }
}
