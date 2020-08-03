<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Cronjob;

use Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract
 */
class ContractTest extends TestCase
{
    public function testExtendingCronjobHandlerContract(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends CronjobHandlerContract {
            protected function getDecorated(): CronjobHandlerContract
            {
                return $this;
            }

            public function handle(CronjobInterface $cronjob): void
            {
            }
        };
    }
}
