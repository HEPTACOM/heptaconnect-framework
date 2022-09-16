<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail;
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
        $result = $this->createMock(AuditTrailFactoryInterface::class);
        $result->expects(static::atLeastOnce())
            ->method('create')
            ->willReturnCallback(function (
                UiActionInterface $uiAction,
                UiAuditContext $auditContext,
                array $ingoing
            ): AuditTrailInterface {
                $logResult = $this->getMockBuilder(\stdClass::class)->addMethods(['__invoke'])->getMock();
                $logResult->method('__invoke')->willReturnArgument(0);

                $logThrowable = $this->getMockBuilder(\stdClass::class)->addMethods(['__invoke'])->getMock();
                $logThrowable->method('__invoke')
                    ->willReturnCallback(static function (\Throwable $throwable): void {
                        static::assertNotSame(0, $throwable->getCode());
                    });

                $logEnd = $this->getMockBuilder(\stdClass::class)->addMethods(['__invoke'])->getMock();
                $logEnd->expects(static::atLeastOnce())->method('__invoke');

                static::assertNotSame('', $auditContext->getUiIdentifier());
                static::assertNotSame('', $auditContext->getUserIdentifier());
                $uiAction::class()->isObjectOfType($uiAction);
                static::assertNotSame([], $ingoing);

                return new AuditTrail(
                    fn (object $object) => ($logResult)($object),
                    fn (\Throwable $throwable) => ($logThrowable)($throwable),
                    fn () => ($logEnd)(),
                );
            });

        return $result;
    }
}
