<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Throw_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Throw_>
 */
final class UiActionsPublicMethodMustCallTracerOnThrowRule implements Rule
{
    public function getNodeType(): string
    {
        return Throw_::class;
    }

    /**
     * @param Throw_ $node
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

        if (!$class->getMethod($scopeFunction, $scope)->isPublic()) {
            return [];
        }

        $thrown = $node->expr;

        if ($thrown instanceof MethodCall) {
            $var = $thrown->var;
            $varMethod = (string) $thrown->name;

            foreach ($scope->getVariableType((string) $var->name)->getReferencedClasses() as $varType) {
                if ($varType === AuditTrailInterface::class && $varMethod === 'throwable') {
                    return [];
                }
            }
        }

        return [
            RuleErrorBuilder::message(
                \sprintf(
                    'Class \'%s\' is an UI action and therefore must pass all thrown exceptions in public methods through \'%s::%s\'',
                    $class->getName(),
                    AuditTrailInterface::class,
                    'throwable'
                )
            )
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }
}
