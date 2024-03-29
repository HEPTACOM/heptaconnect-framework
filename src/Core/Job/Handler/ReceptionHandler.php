<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Job\Handler;

use Heptacom\HeptaConnect\Core\Job\Contract\ReceptionHandlerInterface;
use Heptacom\HeptaConnect\Core\Job\Exception\ReceptionJobHandlingException;
use Heptacom\HeptaConnect\Core\Job\JobData;
use Heptacom\HeptaConnect\Core\Job\JobDataCollection;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveServiceInterface;
use Heptacom\HeptaConnect\Core\Reception\Support\LockAttachable;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFailActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
final class ReceptionHandler implements ReceptionHandlerInterface
{
    public function __construct(
        private LockFactory $lockFactory,
        private StorageKeyGeneratorContract $storageKeyGenerator,
        private ReceiveServiceInterface $receiveService,
        private DeepObjectIteratorContract $objectIterator,
        private RouteGetActionInterface $routeGetAction,
        private LoggerInterface $logger,
        private JobStartActionInterface $jobStartAction,
        private JobFinishActionInterface $jobFinishAction,
        private IdentityMapActionInterface $identityMapAction,
        private IdentityReflectActionInterface $identityReflectAction,
        private JobFailActionInterface $jobFailAction,
    ) {
    }

    public function triggerReception(JobDataCollection $jobs): void
    {
        $receptions = [];
        $routeKeys = new RouteKeyCollection();
        $routeKeys->pushIgnoreInvalidItems($jobs->map(
            static fn (JobData $jobData): mixed => $jobData->getPayload()[Reception::ROUTE_KEY] ?? null
        ));

        $uniqueRouteKeys = new RouteKeyCollection();

        /** @var RouteKeyInterface $routeKey */
        foreach ($routeKeys as $routeKey) {
            /** @var RouteKeyInterface $uniqueRouteKey */
            foreach ($uniqueRouteKeys as $uniqueRouteKey) {
                if ($routeKey->equals($uniqueRouteKey)) {
                    continue 2;
                }
            }

            $uniqueRouteKeys->push([$routeKey]);
        }

        $routeDatas = $this->routeGetAction->get(new RouteGetCriteria($uniqueRouteKeys));
        /** @var RouteGetResult[] $routes */
        $routes = [];

        foreach ($routeDatas as $routeData) {
            $routes[$this->storageKeyGenerator->serialize($routeData->getRouteKey())] = $routeData;
        }

        foreach ($jobs as $job) {
            try {
                $routeKey = $job->getPayload()[Reception::ROUTE_KEY] ?? null;

                if (!$routeKey instanceof RouteKeyInterface) {
                    throw new ReceptionJobHandlingException($job, 1636503503);
                }

                $entity = $job->getPayload()[Reception::ENTITY] ?? null;

                if (!$entity instanceof DatasetEntityContract) {
                    throw new ReceptionJobHandlingException($job, 1636503504);
                }

                $route = $routes[$this->storageKeyGenerator->serialize($routeKey)] ?? null;

                if (!$route instanceof RouteGetResult) {
                    throw new ReceptionJobHandlingException($job, 1636503505);
                }

                if (!\in_array(RouteCapability::RECEPTION, $route->getCapabilities(), true)) {
                    throw new ReceptionJobHandlingException($job, 1636503506);
                }

                if (!$route->getEntityType()->equals($entity::class())) {
                    throw new ReceptionJobHandlingException($job, 1636503507);
                }

                $externalId = $job->getMappingComponent()->getExternalId();

                if ($externalId !== $entity->getPrimaryKey()) {
                    throw new ReceptionJobHandlingException($job, 1636503508);
                }

                $targetPortal = $this->storageKeyGenerator->serialize($route->getTargetPortalNodeKey()->withoutAlias());
                $sourcePortal = $this->storageKeyGenerator->serialize($route->getSourcePortalNodeKey()->withoutAlias());
            } catch (ReceptionJobHandlingException|UnsupportedStorageKeyException $throwable) {
                $this->logger->critical('Reception job preparation failed', [
                    'code' => $throwable->getCode(),
                    'exception' => $throwable,
                ]);

                continue;
            }

            $receptions[(string) $route->getEntityType()][$targetPortal][$sourcePortal][$externalId]['mapping'] = $job->getMappingComponent();
            $receptions[(string) $route->getEntityType()][$targetPortal][$sourcePortal][$externalId]['jobs'][] = [
                'entity' => $entity,
                'jobKey' => $job->getJobKey(),
            ];
        }

        foreach ($receptions as $dataType => $portaledEntities) {
            foreach ($portaledEntities as $targetPortalKey => $sourcePortaledEntities) {
                foreach ($sourcePortaledEntities as $sourcePortalKey => $entityGroups) {
                    foreach ($entityGroups as $externalId => $entityGroup) {
                        $lock = $this->lockFactory->createLock('ca9137ba5ec646078043b96030a00e70_' . \md5(\implode('_', [
                            $sourcePortalKey,
                            $targetPortalKey,
                            $dataType,
                            $externalId,
                        ])));

                        $entityGroup['jobs'][0]['entity']->attach(new LockAttachable($lock));
                    }

                    $sourcePortalNodeKey = $this->storageKeyGenerator->deserialize($sourcePortalKey);

                    if (!$sourcePortalNodeKey instanceof PortalNodeKeyInterface) {
                        continue;
                    }

                    $targetPortalNodeKey = $this->storageKeyGenerator->deserialize($targetPortalKey);

                    if (!$targetPortalNodeKey instanceof PortalNodeKeyInterface) {
                        continue;
                    }

                    $entityType = new EntityType($dataType);
                    $rawEntityGroups = [];
                    $jobKeyGroups = [];

                    foreach ($entityGroups as $entityGroup) {
                        $rawEntityGroups[] = \array_column($entityGroup['jobs'], 'entity');
                        $jobKeyGroups[] = \array_column($entityGroup['jobs'], 'jobKey');
                    }

                    /** @var DatasetEntityContract[] $rawEntities */
                    $rawEntities = \array_merge([], ...$rawEntityGroups);
                    $jobKeys = new JobKeyCollection(\array_merge(...$jobKeyGroups));

                    $filteredEntityObjects = new DatasetEntityCollection();
                    $filteredEntityObjects->pushIgnoreInvalidItems($this->objectIterator->iterate($rawEntities));
                    // TODO inspect memory raise - probably fixed by new storage
                    $mappedEntities = $this->identityMapAction
                        ->map(new IdentityMapPayload($sourcePortalNodeKey, $filteredEntityObjects))
                        ->getMappedDatasetEntityCollection();
                    $this->identityReflectAction->reflect(new IdentityReflectPayload($targetPortalNodeKey, $mappedEntities));

                    $this->jobStartAction->start(new JobStartPayload($jobKeys, new \DateTimeImmutable(), null));
                    try {
                        $this->receiveService->receive(
                            new TypedDatasetEntityCollection($dataType, $rawEntities),
                            $targetPortalNodeKey
                        );
                    } catch (\Throwable $exception) {
                        $this->logger->error($exception->getMessage(), [
                            'code' => 1686752889,
                            'jobKeys' => $jobKeys->asArray(),
                        ]);
                        $this->jobFailAction->fail(new JobFailPayload(
                            $jobKeys,
                            new \DateTimeImmutable(),
                            $exception->getMessage() . \PHP_EOL . 'Code: ' . $exception->getCode()
                        ));

                        continue;
                    }
                    $this->jobFinishAction->finish(new JobFinishPayload($jobKeys, new \DateTimeImmutable(), null));
                }
            }
        }
    }
}
