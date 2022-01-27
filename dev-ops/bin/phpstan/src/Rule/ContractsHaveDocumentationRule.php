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
final class ContractsHaveDocumentationRule implements Rule
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

        if ($node->isFinal()) {
            return [];
        }

        if (!$node->isAbstract()) {
            return [];
        }

        if (!\str_ends_with((string) $node->name, 'Contract')) {
            return [];
        }

        $result = [];
        $interfaceNeedsDocumentation = count($node->getMethods()) > 1;

        if ($interfaceNeedsDocumentation && $this->getCommentSummary($node) === '') {
            $result[] = RuleErrorBuilder::message('Contract must have a documentation')
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build();
        }

        foreach ($node->getMethods() as $method) {
            if ($this->getCommentSummary($method) === '') {
                $result[] = RuleErrorBuilder::message(\sprintf('Contract method %s must have a documentation', $method->name))
                    ->line($method->getStartLine())
                    ->file($scope->getFile())
                    ->build();
            }
        }

        return $result;
    }

    private function getCommentSummary(Node $node): string
    {
        $commentSummary = '';

        foreach ($node->getComments() as $comment) {
            $commentLines = \explode("\n", (string)$comment);
            $commentLines = \array_map(
                static fn(string $l): string => \trim(\ltrim(\trim(\trim((string)$l), '/'), '*')),
                $commentLines
            );
            $commentSummary .= \implode("", $commentLines);
        }

        return $commentSummary;
    }
}
