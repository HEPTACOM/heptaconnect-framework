<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\DevOps\PhpStan\Support\StructDetector;
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
    private StructDetector $structDetector;

    public function __construct()
    {
        $this->structDetector = new StructDetector();
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
        if ($this->structDetector->isClassLikeAStruct($node)) {
            if (!$this->canBeFinal($node)) {
                return [];
            }

            if (!$node->isFinal()) {
                return [
                    RuleErrorBuilder::message(\sprintf('Class \'%s\' that looks like a struct is expected to be final', $node->namespacedName))
                        ->line($node->getStartLine())
                        ->file($scope->getFile())
                        ->build(),
                ];
            }

            $implements = \array_map('strval', $node->implements);

            if (!\in_array(AttachmentAwareInterface::class, $implements, true)) {
                return [
                    RuleErrorBuilder::message(\sprintf('Class \'%s\' that looks like a struct is expected to implement %s', $node->namespacedName, AttachmentAwareInterface::class))
                        ->line($node->getStartLine())
                        ->file($scope->getFile())
                        ->build(),
                ];
            }

            return [];
        }

        if (!$this->canBeFinal($node)) {
            return [];
        }

        $implements = $this->structDetector->getNonStructInterfaces($node);

        if ($node->isFinal()) {
            if ($implements === [] && !$this->structDetector->hasStructInterfaces($node) && $node->extends === null) {
                return [
                    RuleErrorBuilder::message(\sprintf('Class \'%s\' that is final neither looks like a struct, extends a contract nor implements an interface', $node->namespacedName))
                        ->line($node->getStartLine())
                        ->file($scope->getFile())
                        ->build(),
                ];
            }

            return [];
        }

        if ($node->extends !== null) {
            $extends = (string) $node->extends;

            if (\str_ends_with($extends, 'Contract')) {
                return [
                    RuleErrorBuilder::message(\sprintf('Class \'%s\' extends a contract must be final or abstract', $node->namespacedName))
                        ->line($node->getStartLine())
                        ->file($scope->getFile())
                        ->build(),
                ];
            }
        }

        if ($implements === []) {
            return [];
        }

        return [
            RuleErrorBuilder::message(\sprintf('Class \'%s\' implements an interface must be final or abstract', $node->namespacedName))
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }

    private function canBeFinal(Class_ $class): bool
    {
        /* @see https://github.com/nikic/PHP-Parser/issues/821 */
        if ($class->isAnonymous() || \str_starts_with((string) $class->name, 'AnonymousClass')) {
            return false;
        }

        if ($class->isAbstract() || \str_ends_with((string) $class->name, 'Contract')) {
            return false;
        }

        // soft limit
        if (\is_a((string) $class->namespacedName, \Throwable::class, true)) {
            return false;
        }

        return true;
    }
}
