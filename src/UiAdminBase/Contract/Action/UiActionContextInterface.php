<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;

/**
 * Describes an admin UI action specific context.
 */
interface UiActionContextInterface extends AttachmentAwareInterface
{
    /**
     * Gets the user information related to the admin UI action call.
     */
    public function getAuditContext(): UiAuditContext;
}
