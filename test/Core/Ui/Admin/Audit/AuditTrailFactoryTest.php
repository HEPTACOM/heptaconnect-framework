<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Audit;

use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrailFactory;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrailFactory
 */
final class AuditTrailFactoryTest extends TestCase
{
    public function testAuditTrailLifecycle(): void
    {
        static::expectNotToPerformAssertions();

        $action = new class() implements UiActionInterface {
            public static function class(): UiActionType
            {
                return new UiActionType(static::class);
            }
        };

        $factory = new AuditTrailFactory();
        $trail = $factory->create($action, new UiAuditContext('test', 'phpunit'), []);
        $trail->end();
    }
}
