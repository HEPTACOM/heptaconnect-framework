<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class RouteAddDefault implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var string[]
     */
    private array $capabilities;

    /**
     * @param string[] $capabilities
     */
    public function __construct(array $capabilities = [])
    {
        $this->attachments = new AttachmentCollection();
        $this->capabilities = $capabilities;
    }

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    public function getAuditableData(): array
    {
        return [
            'capabilities' => $this->getCapabilities(),
        ];
    }
}
