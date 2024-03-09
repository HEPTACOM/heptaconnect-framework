<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DevOps\PhpStan\Rule;

use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Return_>
 */
final class UiActionsPublicMethodMustCallTracerOnReturnRule implements Rule
{
    public function getNodeType(): string
    {
        return Return_::class;
    }

    /**
     * @param Return_ $node
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

        $nativeMethod = $class->getNativeReflection()->getMethod($scopeFunction);
        $nativeReturnType = $nativeMethod->getReturnType();

        if ($nativeReturnType instanceof \ReflectionNamedType && $nativeReturnType->getName() === 'void') {
            return [];
        }

        $result = $node->expr;

        if ($result instanceof MethodCall) {
            $var = $result->var;
            $resultName = $result->name;

            if ($var instanceof Variable && $resultName instanceof Identifier) {
                $varMethod = $resultName->name;
                $varName = $var->name;

                if (\is_string($varName)) {
                    foreach ($scope->getVariableType($varName)->getReferencedClasses() as $varType) {
                        if ($varType === AuditTrailInterface::class && \in_array($varMethod, ['return', 'returnIterable'], true)) {
                            return [];
                        }
                    }
                }
            }
        }

        return [
            RuleErrorBuilder::message(
                \sprintf(
                    'Class \'%s\' is an UI action and therefore must pass all returned values in public methods through \'%s::%s\' or \'%s::%s\' respectively',
                    $class->getName(),
                    AuditTrailInterface::class,
                    'return',
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
