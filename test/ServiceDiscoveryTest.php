<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Support\ServiceDiscovery;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\NonWebhookServiceStrategy;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\WebhookServiceStrategy;
use Http\Discovery\Exception\ClassInstantiationFailedException;
use Http\Discovery\Exception\DiscoveryFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\ServiceDiscovery
 */
class ServiceDiscoveryTest extends TestCase
{
    private static $originalStrategies = [];

    static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$originalStrategies = ServiceDiscovery::getStrategies();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        ServiceDiscovery::setStrategies(self::$originalStrategies);
    }

    public function testMissingServiceException(): void
    {
        $this->expectException(DiscoveryFailedException::class);
        ServiceDiscovery::findWebhookService();
    }

    public function testWrongServiceException(): void
    {
        $this->expectException(ClassInstantiationFailedException::class);
        ServiceDiscovery::appendStrategy(NonWebhookServiceStrategy::class);
        ServiceDiscovery::findWebhookService();
    }

    public function testFindWebhookService(): void
    {
        $this->expectNotToPerformAssertions();
        ServiceDiscovery::appendStrategy(WebhookServiceStrategy::class);
        ServiceDiscovery::findWebhookService();
    }
}
