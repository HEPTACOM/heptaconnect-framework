<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
 */
class MappedDatasetEntityCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return MappedDatasetEntityStruct::class;
    }

    /**
     * @return MappedDatasetEntityCollection[]
     */
    public function groupByPortalNode(): iterable
    {
        $portalNodeKeys = [];
        $groups = [];

        /** @var MappedDatasetEntityStruct $item */
        foreach ($this->items as $item) {
            $portalNodeKeyHash = null;

            /**
             * @var string $hash
             * @var PortalNodeKeyInterface $portalNodeKey
             */
            foreach ($portalNodeKeys as $hash => $portalNodeKey) {
                if ($portalNodeKey->equals($item->getMapping()->getPortalNodeKey())) {
                    $portalNodeKeyHash = $hash;

                    break;
                }
            }

            if (\is_null($portalNodeKeyHash)) {
                $portalNodeKeyHash = \spl_object_hash($item->getMapping()->getPortalNodeKey());
                $portalNodeKeys[$portalNodeKeyHash] = $item->getMapping()->getPortalNodeKey();
            }

            $groups[$portalNodeKeyHash][] = $item;
        }

        foreach ($groups as $group) {
            yield new static($group);
        }
    }
}
