<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Audit;

use Heptacom\HeptaConnect\Core\Test\Fixture\AdminUiAction\CoreTestUiAction;
use Heptacom\HeptaConnect\Core\Test\Fixture\AdminUiAction\CoreTestUiActionPayload;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrailFactory;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginResult;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogOutput\UiAuditTrailLogOutputPayload;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailBeginActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailEndActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailLogErrorActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailLogOutputActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrailFactory
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd\UiAuditTrailEndPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayloadCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogOutput\UiAuditTrailLogOutputPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 */
final class AuditTrailFactoryTest extends TestCase
{
    /**
     * @var DeepObjectIteratorContract&MockObject
     */
    private ?DeepObjectIteratorContract $deepObjectIterator = null;

    /**
     * @var UiAuditTrailBeginActionInterface&MockObject
     */
    private ?UiAuditTrailBeginActionInterface $beginAction = null;

    /**
     * @var UiAuditTrailLogOutputActionInterface&MockObject
     */
    private ?UiAuditTrailLogOutputActionInterface $logOutputAction = null;

    /**
     * @var UiAuditTrailLogErrorActionInterface&MockObject
     */
    private ?UiAuditTrailLogErrorActionInterface $logErrorAction = null;

    /**
     * @var UiAuditTrailEndActionInterface&MockObject
     */
    private ?UiAuditTrailEndActionInterface $endAction = null;

    /**
     * @var LoggerInterface&MockObject
     */
    private ?LoggerInterface $logger = null;

    /**
     * @var AuditTrail
     */
    private ?AuditTrailInterface $auditTrail = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deepObjectIterator = $this->createMock(DeepObjectIteratorContract::class);
        $this->beginAction = $this->createMock(UiAuditTrailBeginActionInterface::class);
        $this->logOutputAction = $this->createMock(UiAuditTrailLogOutputActionInterface::class);
        $this->logErrorAction = $this->createMock(UiAuditTrailLogErrorActionInterface::class);
        $this->endAction = $this->createMock(UiAuditTrailEndActionInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->deepObjectIterator
            ->expects(static::atLeastOnce())
            ->method('iterate')
            ->willReturnCallback(
                static fn ($object): iterable => (new DeepObjectIteratorContract())->iterate($object)
            );

        $this->beginAction->expects(static::once())
            ->method('begin')
            ->willReturnCallback(function (UiAuditTrailBeginPayload $payload): UiAuditTrailBeginResult {
                static::assertSame('phpunit', $payload->getUserIdentifier());
                static::assertSame('test', $payload->getUiIdentifier());
                static::assertSame('admin', $payload->getUiType());
                static::assertTrue($payload->getUiActionType()->equals(CoreTestUiAction::class()));

                $key = $this->createMock(UiAuditTrailKeyInterface::class);
                $key->method('equals')
                    ->willReturnCallback(static fn (UiAuditTrailKeyInterface $other): bool => $key === $other);

                return new UiAuditTrailBeginResult($key);
            });

        $this->endAction->expects(static::once())->method('end');

        $this->logger->expects(static::never())->method('emergency');
        $this->logger->expects(static::never())->method('alert');
        $this->logger->expects(static::never())->method('critical');
        $this->logger->expects(static::never())->method('error');
        $this->logger->expects(static::never())->method('warning');
        $this->logger->expects(static::never())->method('notice');
        $this->logger->expects(static::never())->method('info');
        $this->logger->expects(static::never())->method('debug');
        $this->logger->expects(static::never())->method('log');

        $action = new CoreTestUiAction();
        $actionContext = new UiActionContext(new UiAuditContext('test', 'phpunit'));

        $factory = new AuditTrailFactory(
            $this->deepObjectIterator,
            new AuditableDataSerializer(
                $this->createMock(LoggerInterface::class),
                new class() extends StorageKeyGeneratorContract {
                    public function generateKeys(string $keyClassName, int $count): iterable
                    {
                        return [];
                    }
                }
            ),
            $this->beginAction,
            $this->logOutputAction,
            $this->logErrorAction,
            $this->endAction,
            $this->logger,
        );
        $this->auditTrail = $factory->create($action, $actionContext->getAuditContext(), [
            'payload' => new CoreTestUiActionPayload(),
            'context' => $actionContext,
        ]);
    }

    public function testAuditTrailEndingLifecycle(): void
    {
        $this->logErrorAction->expects(static::never())->method('logError');
        $this->logOutputAction->expects(static::never())->method('logOutput');

        $this->auditTrail->end();
    }

    public function testAuditTrailExceptionLifecycle(): void
    {
        $this->logOutputAction->expects(static::never())->method('logOutput');
        $this->logErrorAction
            ->expects(static::once())
            ->method('logError')
            ->willReturnCallback(static function (UiAuditTrailLogErrorPayloadCollection $payloads): void {
                static::assertCount(1, $payloads);

                $first = $payloads->first();
                static::assertInstanceOf(UiAuditTrailLogErrorPayload::class, $first);

                static::assertSame('oops', $first->getMessage());
                static::assertSame('987', $first->getCode());
                static::assertSame(0, $first->getDepth());
                static::assertSame(\RuntimeException::class, $first->getExceptionClass());
            });

        $throwable = new \RuntimeException('oops', 987);

        static::assertSame($throwable, $this->auditTrail->throwable($throwable));
    }

    public function testAuditTrailReturnLifecycle(): void
    {
        $this->logErrorAction->expects(static::never())->method('logError');
        $this->logOutputAction
            ->expects(static::once())
            ->method('logOutput')
            ->willReturnCallback(static function (UiAuditTrailLogOutputPayload $payload): void {
                static::assertNotSame('', $payload->getOutput());
            });

        $object = new CoreTestUiActionPayload();

        static::assertSame($object, $this->auditTrail->return($object));
    }

    public function testAuditTrailIterableLifecycle(): void
    {
        $this->logErrorAction->expects(static::never())->method('logError');
        $this->logOutputAction
            ->expects(static::exactly(3))
            ->method('logOutput')
            ->willReturnCallback(static function (UiAuditTrailLogOutputPayload $payload): void {
                static::assertNotSame('', $payload->getOutput());
            });

        static::assertCount(3, \iterable_to_array(
            $this->auditTrail->returnIterable((static function (): iterable {
                yield new CoreTestUiActionPayload();
                yield new CoreTestUiActionPayload();
                yield new CoreTestUiActionPayload();
            })())
        ));
    }

    public function testAuditTrailYieldLifecycle(): void
    {
        $this->logErrorAction->expects(static::never())->method('logError');
        $this->logOutputAction
            ->expects(static::once())
            ->method('logOutput')
            ->willReturnCallback(static function (UiAuditTrailLogOutputPayload $payload): void {
                static::assertNotSame('', $payload->getOutput());
            });

        $object = new CoreTestUiActionPayload();

        static::assertSame($object, $this->auditTrail->yield($object));

        $this->auditTrail->end();
    }
}
