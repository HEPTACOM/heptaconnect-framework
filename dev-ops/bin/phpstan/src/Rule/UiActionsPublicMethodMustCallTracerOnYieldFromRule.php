<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<YieldFrom>
 */
final class UiActionsPublicMethodMustCallTracerOnYieldFromRule implements Rule
{
    public function getNodeType(): string
    {
        return YieldFrom::class;
    }

    /**
     * @param YieldFrom $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $class = $scope->getClassReflection();

        if ($class === null) {
            return [];
        }

        if (!$class->implementsInterface(UiActionInterface::class)) {
            return [];
        }

        $scopeFunction = $scope->getFunctionName();

        if ($scopeFunction === null) {
            return [];
        }

        $method = $class->getMethod($scopeFunction, $scope);

        if (!$method->isPublic()) {
            return [];
        }

        if ($scopeFunction === 'class') {
            return [];
        }

        $result = $node->expr;

        if ($result instanceof MethodCall) {
            $var = $result->var;
            $yieldedName = $result->name;

            if ($var instanceof Variable && $yieldedName instanceof Identifier) {
                $varMethod = $yieldedName->name;
                $varName = $var->name;

                if (\is_string($varName)) {
                    foreach ($scope->getVariableType($varName)->getReferencedClasses() as $varType) {
                        if ($varType === AuditTrailInterface::class && $varMethod === 'returnIterable') {
                            return [];
                        }
                    }
                }
            }
        }

        return [
            RuleErrorBuilder::message(
                \sprintf(
                    'Class \'%s\' is an UI action and therefore must pass all yielded collections in public methods through \'%s::%s\'',
                    $class->getName(),
                    AuditTrailInterface::class,
                    'returnIterable'
                )
            )
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }
}
