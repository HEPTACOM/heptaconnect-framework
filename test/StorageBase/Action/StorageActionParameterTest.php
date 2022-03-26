<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\IdentityErrorKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestResult;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Listing\JobListFinishedResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobScheduleResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Listing\PortalNodeListResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Clear\PortalNodeStorageClearCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Delete\PortalNodeStorageDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItem;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItems;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\FileReferenceRequestKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Portal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayloadCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResults
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Listing\JobListFinishedResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobScheduleResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResults
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Listing\PortalNodeListResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Clear\PortalNodeStorageClearCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Delete\PortalNodeStorageDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\PortalNodeStorageItemContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItem
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItems
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayloads
 */
class StorageActionParameterTest extends TestCase
{
    public function testAttachabilityOfStorageActionStructs(): void
    {
        foreach ($this->iterateAttachmentAwareActionStructs() as $attachmentAware) {
            $attachment = new FirstEntity();
            $attachmentAware->attach($attachment);
            static::assertTrue($attachmentAware->isAttached($attachment));
            $attachmentAware->detach($attachment);
        }
    }

    /**
     * @return iterable<AttachmentAwareInterface>
     */
    private function iterateAttachmentAwareActionStructs(): iterable
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $mappingNodeKey = $this->createMock(MappingNodeKeyInterface::class);
        $fileReferenceRequestKey = $this->createMock(FileReferenceRequestKeyInterface::class);
        $jobKey = $this->createMock(JobKeyInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $identityErrorKey = $this->createMock(IdentityErrorKeyInterface::class);
        $entityCollection = new DatasetEntityCollection();
        $mappedDatasetEntityCollection = new MappedDatasetEntityCollection();
        $portalNodeKeys = new PortalNodeKeyCollection();
        $portalClass = Portal::class;
        $externalId = '1c1500cf-241b-4ecc-bdb1-f691daf59481';
        $entityType = FirstEntity::class;
        $mappingComponentStruct = new MappingComponentStruct($portalNodeKey, $entityType, $externalId);
        $jobKeys = new JobKeyCollection();
        $routeKeys = new RouteKeyCollection();
        $createdAt = \date_create();

        yield new FileReferencePersistRequestPayload($portalNodeKey);
        yield new FileReferencePersistRequestResult($portalNodeKey);
        yield new FileReferenceGetRequestCriteria($portalNodeKey, new FileReferenceRequestKeyCollection());
        yield new FileReferenceGetRequestResult($portalNodeKey, $fileReferenceRequestKey, '');
        yield new IdentityMapPayload($portalNodeKey, $entityCollection);
        yield new IdentityMapResult($mappedDatasetEntityCollection);
        yield new IdentityOverviewCriteria();
        yield new IdentityOverviewResult($portalNodeKey, $mappingNodeKey, $externalId, $entityType, $createdAt);
        yield new IdentityPersistPayload($portalNodeKey, new IdentityPersistPayloadCollection());
        yield new IdentityReflectPayload($portalNodeKey, $mappedDatasetEntityCollection);
        yield new IdentityErrorCreatePayloads();
        yield new IdentityErrorCreatePayload($mappingComponentStruct, new \Exception());
        yield new IdentityErrorCreateResults();
        yield new IdentityErrorCreateResult($identityErrorKey);
        yield new JobCreatePayload('', $mappingComponentStruct, null);
        yield new JobCreateResult($jobKey);
        yield new JobDeleteCriteria($jobKeys);
        yield new JobFailPayload($jobKeys, $createdAt, '');
        yield new JobFailResult($jobKeys, $jobKeys);
        yield new JobFinishPayload($jobKeys, $createdAt, '');
        yield new JobFinishResult($jobKeys, $jobKeys);
        yield new JobGetCriteria($jobKeys);
        yield new JobGetResult('', $jobKey, $mappingComponentStruct, null);
        yield new JobListFinishedResult($jobKey);
        yield new JobSchedulePayload($jobKeys, $createdAt, '');
        yield new JobScheduleResult($jobKeys, $jobKeys);
        yield new JobStartPayload($jobKeys, $createdAt, '');
        yield new JobStartResult($jobKeys, $jobKeys);
        yield new PortalExtensionActivatePayload($portalNodeKey);
        yield new PortalExtensionActivateResult([], []);
        yield new PortalExtensionDeactivatePayload($portalNodeKey);
        yield new PortalExtensionDeactivateResult([], []);
        yield new PortalExtensionFindResult();
        yield new PortalNodeCreatePayloads();
        yield new PortalNodeCreatePayload($portalClass);
        yield new PortalNodeCreateResults();
        yield new PortalNodeCreateResult($portalNodeKey);
        yield new PortalNodeDeleteCriteria($portalNodeKeys);
        yield new PortalNodeGetCriteria($portalNodeKeys);
        yield new PortalNodeGetResult($portalNodeKey, $portalClass);
        yield new PortalNodeListResult($portalNodeKey);
        yield new PortalNodeOverviewCriteria();
        yield new PortalNodeOverviewResult($portalNodeKey, $portalClass, $createdAt);
        yield new PortalNodeConfigurationGetCriteria($portalNodeKeys);
        yield new PortalNodeConfigurationGetResult($portalNodeKey, []);
        yield new PortalNodeConfigurationSetPayload($portalNodeKey, []);
        yield new PortalNodeConfigurationSetPayloads();
        yield new PortalNodeStorageClearCriteria($portalNodeKey);
        yield new PortalNodeStorageDeleteCriteria($portalNodeKey, new StringCollection());
        yield new PortalNodeStorageGetResult($portalNodeKey, '', '', '');
        yield new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection());
        yield new PortalNodeStorageListCriteria($portalNodeKey);
        yield new PortalNodeStorageListResult($portalNodeKey, '', '', '');
        yield new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems());
        yield new PortalNodeStorageSetItem('', '', '', null);
        yield new PortalNodeStorageSetItems();
        yield new RouteCreatePayload($portalNodeKey, $portalNodeKey, $entityType);
        yield new RouteCreatePayloads();
        yield new RouteCreateResult($routeKey);
        yield new RouteCreateResults();
        yield new RouteDeleteCriteria($routeKeys);
        yield new RouteFindCriteria($portalNodeKey, $portalNodeKey, $entityType);
        yield new RouteFindResult($routeKey);
        yield new RouteGetCriteria($routeKeys);
        yield new RouteGetResult($routeKey, $portalNodeKey, $portalNodeKey, $entityType, []);
        yield new ReceptionRouteListCriteria($portalNodeKey, $entityType);
        yield new ReceptionRouteListResult($routeKey);
        yield new RouteOverviewCriteria();
        yield new RouteOverviewResult($routeKey, $entityType, $portalNodeKey, $portalClass, $portalNodeKey, $portalClass, $createdAt, []);
        yield new RouteCapabilityOverviewCriteria();
        yield new RouteCapabilityOverviewResult('', $createdAt);
        yield new WebHttpHandlerConfigurationFindCriteria($portalNodeKey, '', '');
        yield new WebHttpHandlerConfigurationFindResult(null);
        yield new WebHttpHandlerConfigurationSetPayload($portalNodeKey, '', '');
        yield new WebHttpHandlerConfigurationSetPayloads();
    }
}
