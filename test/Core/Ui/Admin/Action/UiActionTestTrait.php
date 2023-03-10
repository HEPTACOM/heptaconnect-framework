<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailFactoryInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\Contract\PortalNodeExistenceSeparatorInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

trait UiActionTestTrait
{
    private function createUiActionContext(): UiActionContext
    {
        $factory = new UiActionContextFactory();

        return $factory->createContext(new UiAuditContext('test', 'phpunit'));
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

    private function createPortalNodeSeparatorAllPreview(): PortalNodeExistenceSeparatorInterface
    {
        $result = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $result->expects(static::atLeastOnce())
            ->method('separateKeys')
            ->willReturnCallback(static function (PortalNodeKeyCollection $keys): PortalNodeExistenceSeparationResult {
                static::assertNotEmpty($keys);

                return new PortalNodeExistenceSeparationResult($keys, new PortalNodeKeyCollection(), new PortalNodeKeyCollection());
            });

        return $result;
    }

    private function createPortalNodeSeparatorAllExists(): PortalNodeExistenceSeparatorInterface
    {
        $result = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $result->expects(static::atLeastOnce())
            ->method('separateKeys')
            ->willReturnCallback(static function (PortalNodeKeyCollection $keys): PortalNodeExistenceSeparationResult {
                static::assertNotEmpty($keys);

                return new PortalNodeExistenceSeparationResult(new PortalNodeKeyCollection(), $keys, new PortalNodeKeyCollection());
            });

        return $result;
    }

    private function createPortalNodeSeparatorNoneExists(): PortalNodeExistenceSeparatorInterface
    {
        $result = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $result->expects(static::atLeastOnce())
            ->method('separateKeys')
            ->willReturnCallback(static function (PortalNodeKeyCollection $keys): PortalNodeExistenceSeparationResult {
                static::assertNotEmpty($keys);

                return new PortalNodeExistenceSeparationResult(new PortalNodeKeyCollection(), new PortalNodeKeyCollection(), $keys);
            });

        return $result;
    }
}
