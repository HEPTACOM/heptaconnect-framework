<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

trait PathMethodsTrait
{
    public function getPsr4(): array
    {
        $path = $this->getPath();
        $composerPsr4 = $this->getComposerPsr4($path);

        if (\is_array($composerPsr4)) {
            return $composerPsr4;
        }

        $namespace = (new \ReflectionClass($this))->getNamespaceName() . '\\';
        $sourceDir = \rtrim($path, \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR;

        return [
            $namespace => $sourceDir,
        ];
    }

    public function getContainerConfigurationPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'Resources',
            'config',
        ]);
    }

    public function getFlowComponentsPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'Resources',
            'flow-component',
        ]);
    }

    protected function getPath(): string
    {
        /** @var string $path */
        $path = (new \ReflectionClass($this))->getFileName();

        return \dirname($path);
    }

    private function getComposerPsr4(string $path): ?array
    {
        $composerJsonPath = \dirname($path) . \DIRECTORY_SEPARATOR . 'composer.json';

        if (!\file_exists($composerJsonPath)) {
            return null;
        }

        $composerJsonContent = \file_get_contents($composerJsonPath);

        if ($composerJsonContent === false) {
            return null;
        }

        $composerJson = \json_decode($composerJsonContent, true);

        if (!\is_array($composerJson)) {
            return null;
        }

        $composerExtra = $composerJson['extra'] ?? null;

        if (!\is_array($composerExtra)) {
            return null;
        }

        $composerExtraHeptaconnect = $composerExtra['heptaconnect'] ?? null;

        if (!\is_array($composerExtraHeptaconnect)) {
            return null;
        }

        /** @var array|null $portals */
        $portals = $composerExtraHeptaconnect['portals'] ?? null;

        if (!\is_array($portals)) {
            $portals = [];
        }

        /** @var array|null $portalExtensions */
        $portalExtensions = $composerExtraHeptaconnect['portalExtensions'] ?? null;

        if (!\is_array($portalExtensions)) {
            $portalExtensions = [];
        }

        /** @var array<int, string> $portals */
        $portals = \array_values(\array_filter($portals, 'is_string'));
        /** @var array<int, string> $portalExtensions */
        $portalExtensions = \array_values(\array_filter($portalExtensions, 'is_string'));

        if (!\in_array(static::class, [...$portals, ...$portalExtensions], true)) {
            return null;
        }

        $psr4 = $composerJson['autoload']['psr-4'] ?? null;

        if (!\is_array($psr4)) {
            return null;
        }

        return \array_map(
            fn (string $psr4Path) => \dirname($path) . \DIRECTORY_SEPARATOR . $psr4Path,
            $psr4
        );
    }
}
