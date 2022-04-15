<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Test\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 */
final class UiActionParameterTest extends TestCase
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
        $portalClass = Portal::class;
        $entityType = FirstEntity::class;
        $codeOrigin = new CodeOrigin(__FILE__, 0, 1);

        yield new PortalEntityListCriteria($portalClass);
        yield new PortalEntityListResult($codeOrigin, $entityType, FooBarEmitter::class);
    }
}
