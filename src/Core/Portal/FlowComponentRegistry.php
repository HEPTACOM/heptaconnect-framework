<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Portal;

use Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PackageCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;

class FlowComponentRegistry
{
    private bool $isLoaded = false;

    /**
     * @param array<int, ExplorerCollection>       $sourcedExplorers
     * @param array<int, EmitterCollection>        $sourcedEmitters
     * @param array<int, ReceiverCollection>       $sourcedReceivers
     * @param array<int, StatusReporterCollection> $sourcedStatusReporters
     * @param array<int, HttpHandlerCollection>    $sourcedWebHttpHandlers
     * @param array<class-string, string[]>        $flowBuilderFiles
     */
    public function __construct(
        private PackageCollection $packages,
        private array $sourcedExplorers,
        private array $sourcedEmitters,
        private array $sourcedReceivers,
        private array $sourcedStatusReporters,
        private array $sourcedWebHttpHandlers,
        private array $flowBuilderFiles
    ) {
    }

    /**
     * @deprecated Parameter $source will be removed
     * @param class-string $source
     */
    public function getExplorers(?string $source = null): ExplorerCollection
    {
        $this->loadSource();

        return new ExplorerCollection($this->sourcedExplorers);
    }

    /**
     * @deprecated Parameter $source will be removed
     * @param class-string $source
     */
    public function getEmitters(?string $source = null): EmitterCollection
    {
        $this->loadSource();

        return new EmitterCollection($this->sourcedEmitters);
    }

    /**
     * @deprecated Parameter $source will be removed
     * @param class-string $source
     */
    public function getReceivers(?string $source = null): ReceiverCollection
    {
        $this->loadSource();

        return new ReceiverCollection($this->sourcedReceivers);
    }

    /**
     * @deprecated Parameter $source will be removed
     * @param class-string $source
     */
    public function getStatusReporters(?string $source = null): StatusReporterCollection
    {
        $this->loadSource();

        return new StatusReporterCollection($this->sourcedStatusReporters);
    }

    /**
     * @deprecated Parameter $source will be removed
     * @param class-string $source
     */
    public function getWebHttpHandlers(?string $source = null): HttpHandlerCollection
    {
        $this->loadSource();

        return new HttpHandlerCollection($this->sourcedWebHttpHandlers);
    }

    /**
     * Returns an ordered array of FQCNs for the portal class and all supporting portal extension classes that
     * contribute flow components to the current portal container. The first item will be the FQCN of the portal class.
     * The supporting portal extension FQCNs will be ordered lexicographically.
     *
     * @deprecated Method will be removed. Call the corresponding getter directly without any arguments.
     *
     * @return class-string[]
     */
    public function getOrderedSources(): array
    {
        return [PortalContract::class];
    }

    private function loadSource(): void
    {
        if ($this->isLoaded) {
            return;
        }

        foreach ($this->flowBuilderFiles as $source => $files) {
            if ($files !== []) {
                $flowBuilder = new FlowComponent();

                $flowBuilder->reset();
                $flowBuilder->setDefaultPriority(
                    $this->getSourcePackage($source)->getDefaultFlowComponentPriority()
                );

                foreach ($files as $file) {
                    // prevent access to object context
                    (static function (string $file): void {
                        include $file;
                    })($file);
                }

                foreach ($flowBuilder->buildExplorers() as $priority => $explorer) {
                    ($this->sourcedExplorers[$priority] ??= new ExplorerCollection())->push([$explorer]);
                }

                foreach ($flowBuilder->buildEmitters() as $priority => $emitter) {
                    ($this->sourcedEmitters[$priority] ??= new EmitterCollection())->push([$emitter]);
                }

                foreach ($flowBuilder->buildReceivers() as $priority => $receiver) {
                    ($this->sourcedReceivers[$priority] ??= new ReceiverCollection())->push([$receiver]);
                }

                foreach ($flowBuilder->buildStatusReporters() as $priority => $statusReporter) {
                    ($this->sourcedStatusReporters[$priority] ??= new StatusReporterCollection())->push([$statusReporter]);
                }

                foreach ($flowBuilder->buildHttpHandlers() as $priority => $httpHandler) {
                    ($this->sourcedWebHttpHandlers[$priority] ??= new HttpHandlerCollection())->push([$httpHandler]);
                }
            }
        }

        $this->flowBuilderFiles = [];

        \ksort($this->sourcedExplorers);
        \ksort($this->sourcedEmitters);
        \ksort($this->sourcedReceivers);
        \ksort($this->sourcedStatusReporters);
        \ksort($this->sourcedWebHttpHandlers);

        $this->sourcedExplorers = \array_merge(...\array_map(
            static fn (ExplorerCollection $explorers) => $explorers->asArray(),
            $this->sourcedExplorers
        ));

        $this->sourcedEmitters = \array_merge(...\array_map(
            static fn (EmitterCollection $emitters) => $emitters->asArray(),
            $this->sourcedEmitters
        ));

        $this->sourcedReceivers = \array_merge(...\array_map(
            static fn (ReceiverCollection $receivers) => $receivers->asArray(),
            $this->sourcedReceivers
        ));

        $this->sourcedStatusReporters = \array_merge(...\array_map(
            static fn (StatusReporterCollection $statusReporters) => $statusReporters->asArray(),
            $this->sourcedStatusReporters
        ));

        $this->sourcedWebHttpHandlers = \array_merge(...\array_map(
            static fn (HttpHandlerCollection $webHttpHandlers) => $webHttpHandlers->asArray(),
            $this->sourcedWebHttpHandlers
        ));

        $this->isLoaded = true;
    }

    /**
     * @param class-string $source
     */
    private function getSourcePackage(string $source): PackageContract
    {
        $packages = $this->packages->withoutItems();

        $packages->push($this->packages->filter(
            static fn (PackageContract $package): bool => \is_a($package, $source)
        ));

        $sourcePackage = $packages->first();

        if (!$sourcePackage instanceof PackageContract) {
            throw new \Exception(
                'Unable to find source package "' . $source . '" in built packages.',
                1693695453
            );
        }

        return $sourcePackage;
    }
}
