<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Yield_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Yield_>
 */
final class UiActionsPublicMethodMustCallTracerOnYieldRule implements Rule
{
    public function getNodeType(): string
    {
        return Yield_::class;
    }

    /**
     * @param Yield_ $node
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

        $yielded = $node->value;

        if ($yielded instanceof MethodCall) {
            $var = $yielded->var;
            $varMethod = (string) $yielded->name;

            foreach ($scope->getVariableType((string) $var->name)->getReferencedClasses() as $varType) {
                if ($varType === AuditTrailInterface::class && $varMethod === 'yield') {
                    return [];
                }
            }
        }

        return [
            RuleErrorBuilder::message(
                \sprintf(
                    'Class \'%s\' is an UI action and therefore must pass all single-yielded values in public methods through \'%s::%s\'',
                    $class->getName(),
                    AuditTrailInterface::class,
                    'yield'
                )
            )
                ->line($node->getStartLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }
}
