<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<ConstFetch>
 */
final class DoNotUseGlobBraceRule implements Rule
{
    public function getNodeType(): string
    {
        return ConstFetch::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $name = $node->name->toString();

        if ($name === 'GLOB_BRACE') {
            return [
                RuleErrorBuilder::message('Do not use GLOB_BRACE as not every php implementation has this')
                    ->line($node->getStartLine())
                    ->file($scope->getFile())
                    ->build(),
            ];
        }

        return [];
    }
}
