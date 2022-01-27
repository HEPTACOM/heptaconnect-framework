<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Interface_>
 */
final class InterfacesHaveDocumentationRule implements Rule
{
    public function getNodeType(): string
    {
        return Interface_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $result = [];
        $interfaceNeedsDocumentation = count($node->getMethods()) > 1;

        if ($interfaceNeedsDocumentation && $this->getCommentSummary($node) === '') {
            $result[] = RuleErrorBuilder::message('Interface must have a documentation')
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build();
        }

        foreach ($node->getMethods() as $method) {
            if ($this->getCommentSummary($method) === '') {
                $result[] = RuleErrorBuilder::message(\sprintf('Interface method %s must have a documentation', $method->name))
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
