<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Test\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortalExtension;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteRemove\RouteRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteRemove\RouteRemoveCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListResultContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNodeExtensionActiveChangePayloadContract
 */
final class UiActionParameterTest extends TestCase
{
    public function testAttachabilityOfUiActionStructs(): void
    {
        foreach ($this->iterateAttachmentAwareActionStructs() as $attachmentAware) {
            $attachment = new FirstEntity();
            $attachmentAware->attach($attachment);
            static::assertTrue($attachmentAware->isAttached($attachment));
            $attachmentAware->detach($attachment);
        }
    }

    public function testAuditabilityOfUiActionStructs(): void
    {
        $serializer = new AuditableDataSerializer($this->createMock(LoggerInterface::class), new class() extends StorageKeyGeneratorContract {
            public function generateKeys(string $keyClassName, int $count): iterable
            {
                return [];
            }
        });

        foreach ($this->iterateAuditableAwareActionStructs() as $auditableAware) {
            $data = $auditableAware->getAuditableData();
            static::assertNotSame([], $data);
            // validate all objects are non empty json serializables
            static::assertStringNotContainsString('{}', $serializer->serialize($auditableAware));
        }
    }

    /**
     * @return iterable<AuditableDataAwareInterface>
     */
    private function iterateAuditableAwareActionStructs(): iterable
    {
        yield from \iterable_filter(
            $this->iterateAttachmentAwareActionStructs(),
            static fn (object $struct): bool => !$struct instanceof CollectionInterface && !$struct instanceof UiAuditContext
        );
    }

    /**
     * @return iterable<AttachmentAwareInterface>
     */
    private function iterateAttachmentAwareActionStructs(): iterable
    {
        $portalClass = Portal::class;
        $entityType = FirstEntity::class;
        $codeOrigin = new CodeOrigin(__FILE__, 0, 1);
        $portalNodeKey = new PreviewPortalNodeKey($portalClass::class());
        $portalNodeKeys = new PortalNodeKeyCollection([$portalNodeKey]);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalExtensionClass = FooBarPortalExtension::class;
        $unsafeClass = new UnsafeClassString($entityType);
        $routeKeys = new RouteKeyCollection();
        $jobKeys = new JobKeyCollection();

        yield new UiAuditContext('', '');
        yield new JobScheduleCriteria();
        yield new JobScheduleResult($jobKeys, $jobKeys);
        yield new PortalEntityListCriteria($portalClass::class());
        yield new PortalEntityListResult($codeOrigin, $entityType::class(), FooBarEmitter::class);
        yield new PortalNodeAddPayload($portalClass::class());
        yield new PortalNodeAddResult($portalNodeKey);
        yield new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection());
        yield new PortalNodeConfigurationGetResult($portalNodeKey, []);
        yield new PortalNodeConfigurationRenderCriteria(new PortalNodeKeyCollection());
        yield new PortalNodeConfigurationRenderResult($portalNodeKey, []);
        yield new PortalNodeEntityListCriteria($portalNodeKey);
        yield new PortalNodeEntityListResult($codeOrigin, $entityType::class(), FooBarEmitter::class);
        yield new PortalNodeExtensionActivatePayload($portalNodeKey);
        yield new PortalNodeExtensionBrowseCriteria($portalNodeKey);
        yield new PortalNodeExtensionBrowseResult($portalNodeKey, true, $portalExtensionClass::class());
        yield new PortalNodeExtensionBrowseResult($portalNodeKey, true, $unsafeClass);
        yield new PortalNodeExtensionDeactivatePayload($portalNodeKey);
        yield new PortalNodeRemoveCriteria($portalNodeKeys);
        yield new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection());
        yield new PortalNodeStorageGetResult($portalNodeKey, '', null);
        yield new PortalNodeStatusReportPayload($portalNodeKey, []);
        yield new PortalNodeStatusReportResult($portalNodeKey, '', true, []);
        yield new RouteAddDefault([]);
        yield new RouteAddPayload($portalNodeKey, $portalNodeKey, $entityType::class(), []);
        yield new RouteAddPayloadCollection();
        yield new RouteAddResult($routeKey, $portalNodeKey, $portalNodeKey, $entityType::class(), []);
        yield new RouteAddResultCollection();
        yield new RouteBrowseCriteria();
        yield new RouteBrowseResult($routeKey, $portalNodeKey, $portalNodeKey, $entityType::class(), new StringCollection());
        yield new RouteBrowseResult($routeKey, $portalNodeKey, $portalNodeKey, $unsafeClass, new StringCollection());
        yield new RouteRemoveCriteria($routeKeys);
    }
}
