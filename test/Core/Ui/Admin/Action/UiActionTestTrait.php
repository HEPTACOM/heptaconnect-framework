<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailFactoryInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

trait UiActionTestTrait
{
    private function createUiActionContext(): UiActionContext
    {
        return new UiActionContext(new UiAuditContext('test', 'phpunit'));
    }

    private function createAuditTrailFactory(): AuditTrailFactoryInterface
    {
        $trail = $this->createMock(AuditTrailInterface::class);

        $trail->expects(static::atLeastOnce())
            ->method('end');

        $trail->method('throwable')
            ->willReturnCallback(static function (\Throwable $throwable) use ($trail): \Throwable {
                static::assertNotSame(0, $throwable->getCode());

                $trail->end();

                return $throwable;
            });

        $trail->method('return')
            ->willReturnCallback(static function (object $result) use ($trail): object {
                $trail->end();

                return $result;
            });

        $trail->method('returnIterable')
            ->willReturnCallback(static function (iterable $result) use ($trail): iterable {
                try {
                    yield from $result;

                    $trail->end();
                } catch (\Throwable $throwable) {
                    throw $trail->throwable($throwable);
                }
            });

        $trail->method('yield')->willReturnArgument(0);

        $result = $this->createMock(AuditTrailFactoryInterface::class);
        $result->expects(static::atLeastOnce())
            ->method('create')
            ->willReturnCallback(static function (
                UiActionInterface $uiAction,
                UiAuditContext $auditContext,
                array $ingoing
            ) use ($trail): AuditTrailInterface {
                static::assertNotSame('', $auditContext->getUiIdentifier());
                static::assertNotSame('', $auditContext->getUserIdentifier());
                $uiAction::class()->isObjectOfType($uiAction);
                static::assertNotSame([], $ingoing);

                return $trail;
            });

        return $result;
    }
}
