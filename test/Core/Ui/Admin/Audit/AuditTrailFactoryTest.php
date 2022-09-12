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
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 */
final class AuditTrailFactoryTest extends TestCase
{
    public function testAuditTrailLifecycle(): void
    {
        $action = new class() implements UiActionInterface {
            public static function class(): UiActionType
            {
                return new UiActionType(static::class);
            }
        };

        $factory = new AuditTrailFactory();
        $trail = $factory->create($action, new UiAuditContext('test', 'phpunit'), []);
        $trail->end();

        $throwable = new \RuntimeException('oops');

        static::assertSame($throwable, $trail->throwable($throwable));
    }
}
