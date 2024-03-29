<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Job\Handler;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmitServiceInterface;
use Heptacom\HeptaConnect\Core\Job\Contract\EmissionHandlerInterface;
use Heptacom\HeptaConnect\Core\Job\JobData;
use Heptacom\HeptaConnect\Core\Job\JobDataCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFailActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Psr\Log\LoggerInterface;

final class EmissionHandler implements EmissionHandlerInterface
{
    public function __construct(
        private EmitServiceInterface $emitService,
        private JobStartActionInterface $jobStartAction,
        private JobFinishActionInterface $jobFinishAction,
        private JobFailActionInterface $jobFailAction,
        private LoggerInterface $logger
    ) {
    }

    public function triggerEmission(JobDataCollection $jobs): void
    {
        $emissions = [];
        /** @var JobKeyInterface[][] $processed */
        $processed = [];

        /** @var JobData $job */
        foreach ($jobs as $job) {
            $emissions[(string) $job->getMappingComponent()->getEntityType()][] = $job->getMappingComponent();
            $processed[(string) $job->getMappingComponent()->getEntityType()][] = $job->getJobKey();
        }

        foreach ($emissions as $dataType => $emission) {
            $emissionChunks = \array_chunk($emission, 10);
            $processedChunks = \array_chunk($processed[$dataType], 10);

            foreach ($emissionChunks as $chunkKey => $emissionChunk) {
                $jobKeys = new JobKeyCollection($processedChunks[$chunkKey] ?? []);

                $this->jobStartAction->start(new JobStartPayload(
                    $jobKeys,
                    new \DateTimeImmutable(),
                    null
                ));

                try {
                    $this->emitService->emit(new TypedMappingComponentCollection(
                        new EntityType($dataType),
                        $emissionChunk
                    ));
                } catch (\Throwable $exception) {
                    $this->logger->error($exception->getMessage(), [
                        'code' => 1686752874,
                        'jobKeys' => $jobKeys->asArray(),
                    ]);

                    $this->jobFailAction->fail(new JobFailPayload(
                        $jobKeys,
                        new \DateTimeImmutable(),
                        $exception->getMessage() . \PHP_EOL . 'Code: ' . $exception->getCode()
                    ));

                    continue;
                }

                $this->jobFinishAction->finish(new JobFinishPayload(
                    $jobKeys,
                    new \DateTimeImmutable(),
                    null
                ));
            }
        }
    }
}
