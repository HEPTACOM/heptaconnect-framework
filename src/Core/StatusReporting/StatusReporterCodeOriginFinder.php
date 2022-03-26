<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\StatusReporting;

use Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;

class StatusReporterCodeOriginFinder implements StatusReporterCodeOriginFinderInterface
{
    public function findOrigin(StatusReporterContract $statusReporter): CodeOrigin
    {
        if ($statusReporter instanceof StatusReporter) {
            /** @var array<\Closure|null> $closures */
            $closures = [
                $statusReporter->getRunMethod(),
            ];

            $lastReflectionException = null;

            foreach ($closures as $closure) {
                if ($closure instanceof \Closure) {
                    try {
                        $reflection = new \ReflectionFunction($closure);
                        $filepath = $reflection->getFileName();

                        if (\is_string($filepath)) {
                            return new CodeOrigin($filepath, $reflection->getStartLine(), $reflection->getEndLine());
                        }
                    } catch (\ReflectionException $e) {
                        $lastReflectionException = $e;
                    }
                }
            }

            throw new CodeOriginNotFound($statusReporter, 1641079371, $lastReflectionException);
        }

        try {
            $reflection = new \ReflectionClass($statusReporter);
            $filepath = $reflection->getFileName();

            if (\is_string($filepath)) {
                return new CodeOrigin($filepath, $reflection->getStartLine(), $reflection->getEndLine());
            }
        } catch (\ReflectionException $e) {
            throw new CodeOriginNotFound($statusReporter, 1641079372, $e);
        }

        throw new CodeOriginNotFound($statusReporter, 1641079373);
    }
}