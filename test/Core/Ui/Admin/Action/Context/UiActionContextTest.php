<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action\Context;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 */
final class UiActionContextTest extends TestCase
{
    public function testAttachability(): void
    {
        $context = new UiActionContext();

        $attachment = new FirstEntity();
        $context->attach($attachment);
        static::assertTrue($context->isAttached($attachment));
        $context->detach($attachment);
    }
}
