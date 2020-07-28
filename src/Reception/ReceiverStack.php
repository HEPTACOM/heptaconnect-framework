<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;

class ReceiverStack implements ReceiverStackInterface
{
    /**
     * @var array<array-key, ReceiverContract>
     */
    private array $receivers;

    /**
     * @param iterable<array-key, ReceiverContract> $receivers
     */
    public function __construct(iterable $receivers)
    {
        $this->receivers = iterable_to_array($receivers);
    }

    public function next(MappedDatasetEntityCollection $mappedDatasetEntities, ReceiveContextInterface $context): iterable
    {
        $receiver = \array_shift($this->receivers);

        if (!$receiver instanceof ReceiverContract) {
            return [];
        }

        return $receiver->receive($mappedDatasetEntities, $context, $this);
    }
}
