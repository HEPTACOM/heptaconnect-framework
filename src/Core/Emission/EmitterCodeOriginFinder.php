<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Emission;

use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

final class EmitterCodeOriginFinder implements EmitterCodeOriginFinderInterface
{
    public function findOrigin(EmitterContract $emitter): CodeOrigin
    {
        if ($emitter instanceof Emitter) {
            /** @var array<\Closure|null> $closures */
            $closures = [
                $emitter->getRunMethod(),
                $emitter->getBatchMethod(),
                $emitter->getExtendMethod(),
            ];

            $lastReflectionException = null;

            foreach ($closures as $closure) {
                if ($closure instanceof \Closure) {
                    try {
                        $reflection = new \ReflectionFunction($closure);
                        $filepath = $reflection->getFileName();

                        if (\is_string($filepath)) {
                            return $this->createOrigin($reflection, $filepath);
                        }
                    } catch (\ReflectionException $e) {
                        $lastReflectionException = $e;
                    }
                }
            }

            throw new CodeOriginNotFound($emitter, 1637607653, $lastReflectionException);
        }

        try {
            $reflection = new \ReflectionClass($emitter);
            $filepath = $reflection->getFileName();

            if (\is_string($filepath)) {
                return $this->createOrigin($reflection, $filepath);
            }
        } catch (\ReflectionException $e) {
            throw new CodeOriginNotFound($emitter, 1637607654, $e);
        }

        throw new CodeOriginNotFound($emitter, 1637607655);
    }

    /**
     * @param \ReflectionClass<EmitterContract>|\ReflectionFunction $reflection
     */
    private function createOrigin(\ReflectionClass|\ReflectionFunction $reflection, string $filepath): CodeOrigin
    {
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        return new CodeOrigin(
            $filepath,
            $startLine !== false ? $startLine : -1,
            $endLine !== false ? $endLine : -1
        );
    }
}
