<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionA\PortalExtensionA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionB\PortalExtensionB;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test portal extension related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class PortalExtensionTestContract extends TestCase
{
    /**
     * Validates a complete portal extension "lifecycle" can be managed with the storage. It covers activation and deactivation of extensions.
     */
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreate = $facade->getPortalNodeCreateAction();
        $portalNodeDelete = $facade->getPortalNodeDeleteAction();
        $portalExtensionActivate = $facade->getPortalExtensionActivateAction();
        $portalExtensionDeactivate = $facade->getPortalExtensionDeactivateAction();
        $portalExtensionFind = $facade->getPortalExtensionFindAction();

        $firstPortalNode = $portalNodeCreate->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
        ]))->first();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstPortalNode);

        $portalNodeKey = $firstPortalNode->getPortalNodeKey();

        $portalExtensionA = new PortalExtensionA();
        $portalExtensionB = new PortalExtensionB();
        $portalExtensionFindResult = $portalExtensionFind->find($portalNodeKey);

        static::assertTrue($portalExtensionFindResult->isActive($portalExtensionA));
        static::assertFalse($portalExtensionFindResult->isActive($portalExtensionB));

        $portalExtensionActivatePayload = new PortalExtensionActivatePayload($portalNodeKey);
        $portalExtensionActivatePayload->addExtension(PortalExtensionB::class);
        $portalExtensionActivateResult = $portalExtensionActivate->activate($portalExtensionActivatePayload);
        $portalExtensionFindResult = $portalExtensionFind->find($portalNodeKey);

        static::assertTrue($portalExtensionActivateResult->isSuccess());
        static::assertTrue($portalExtensionFindResult->isActive($portalExtensionA));
        static::assertTrue($portalExtensionFindResult->isActive($portalExtensionB));

        $portalExtensionDeactivatePayload = new PortalExtensionDeactivatePayload($portalNodeKey);
        $portalExtensionDeactivatePayload->addExtension(PortalExtensionA::class);
        $portalExtensionDeactivateResult = $portalExtensionDeactivate->deactivate($portalExtensionDeactivatePayload);
        $portalExtensionFindResult = $portalExtensionFind->find($portalNodeKey);

        static::assertTrue($portalExtensionDeactivateResult->isSuccess());
        static::assertFalse($portalExtensionFindResult->isActive($portalExtensionA));
        static::assertTrue($portalExtensionFindResult->isActive($portalExtensionB));

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$portalNodeKey])));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
