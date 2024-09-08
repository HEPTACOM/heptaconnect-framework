<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PackageContract>
 */
class PackageCollection extends AbstractObjectCollection
{
    #[\Override]
    protected function getT(): string
    {
        return PackageContract::class;
    }

    public function withAdditionalPackages(): self
    {
        /** @var PackageContract[] $toCheck */
        $toCheck = $this->asArray();
        $packages = [];

        while ($toCheck !== []) {
            $newToCheck = [];

            foreach ($toCheck as $package) {
                if (\array_key_exists($package::class, $packages)) {
                    continue;
                }

                $packages[$package::class] = $package;

                foreach ($package->getAdditionalPackages() as $additionalPackage) {
                    $newToCheck[] = $additionalPackage;
                }
            }

            $toCheck = $newToCheck;
        }

        $result = self::withoutItems();
        $result->push(\array_values($packages));

        return $result;
    }
}
