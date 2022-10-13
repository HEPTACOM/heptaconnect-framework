<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd\UiAuditTrailEndPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogOutput\UiAuditTrailLogOutputPayload;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\AdminUiAction\StorageTestUiAction;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test creation of UI audit trail related storage actions.
 */
abstract class UiAuditTrailTestContract extends TestCase
{
    /**
     * Validates a complete UI audit trail "lifecycle" can be managed with the storage. It covers beginning, logging and ending a trail.
     */
    public function testLifecycle(): void
    {
        static::expectNotToPerformAssertions();

        $facade = $this->createStorageFacade();
        $beginAction = $facade->getUiAuditTrailBeginAction();
        $logOutputAction = $facade->getUiAuditTrailLogOutputAction();
        $logErrorAction = $facade->getUiAuditTrailLogErrorAction();
        $endAction = $facade->getUiAuditTrailEndAction();

        $uiAuditTrailKey = $beginAction->begin(new UiAuditTrailBeginPayload(
            'test-ui',
            'Heptacom\HeptaConnect\TestSuite',
            StorageTestUiAction::class(),
            'phpunit',
            new TaggedStringCollection([
                new TagItem(
                    new StringCollection([
                        'Action about portal node',
                        \json_encode([
                            'portalNode' => new PreviewPortalNodeKey(PortalA::class()),
                            'code' => 12345,
                        ]),
                    ]),
                    'input'
                ),
            ]),
        ))->getUiAuditTrailKey();

        $logOutputAction->logOutput(new UiAuditTrailLogOutputPayload($uiAuditTrailKey, new TaggedStringCollection()));
        $logErrorAction->logError(new UiAuditTrailLogErrorPayloadCollection([
            new UiAuditTrailLogErrorPayload($uiAuditTrailKey, \Throwable::class, 1, 'Ouch', '123'),
            new UiAuditTrailLogErrorPayload($uiAuditTrailKey, \Throwable::class, 2, 'that', '234'),
            new UiAuditTrailLogErrorPayload($uiAuditTrailKey, \Throwable::class, 3, 'hurts', '345'),
        ]));

        $endAction->end(new UiAuditTrailEndPayload($uiAuditTrailKey));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
