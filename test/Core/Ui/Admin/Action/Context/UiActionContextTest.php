<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action\Context;

use Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action\UiActionTestTrait;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 */
final class UiActionContextTest extends TestCase
{
    use UiActionTestTrait;

    public function testAttachability(): void
    {
        $context = $this->createUiActionContext();

        $attachment = new FirstEntity();
        $context->attach($attachment);
        static::assertTrue($context->isAttached($attachment));
        $context->detach($attachment);
    }
}
