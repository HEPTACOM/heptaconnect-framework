<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Webhook;

use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract
 */
class ContractTest extends TestCase
{
    public function testExtendingWebhookHandlerContract(): void
    {
        $this->expectNotToPerformAssertions();
        new class extends WebhookHandlerContract {
            protected function getDecorated(): WebhookHandlerContract
            {
                return $this;
            }
        };
    }
}
