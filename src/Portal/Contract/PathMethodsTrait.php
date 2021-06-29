<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

trait PathMethodsTrait
{
    public function getPsr4(): array
    {
        $path = $this->getPath();

        $composerJsonPath = \dirname($path).\DIRECTORY_SEPARATOR.'composer.json';

        if (\file_exists($composerJsonPath)) {
            $composerJson = \json_decode(\file_get_contents($composerJsonPath), true);
            $portals = $composerJson['extra']['heptaconnect']['portals'] ?? [];

            if (\in_array(static::class, $portals)) {
                $psr4 = $composerJson['autoload']['psr-4'];

                if ($psr4) {
                    return \array_map(
                        fn (string $psr4Path) => \dirname($path).\DIRECTORY_SEPARATOR.$psr4Path,
                        $psr4
                    );
                }
            }
        }

        $namespace = (new \ReflectionClass($this))->getNamespaceName().'\\';
        $sourceDir = \rtrim($path, \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR;

        return [
            $namespace => $sourceDir,
        ];
    }

    public function getContainerConfigurationPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'resources',
            'config',
        ]);
    }

    public function getFlowComponentsPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'flow-components',
        ]);
    }

    protected function getPath(): string
    {
        /** @var string $path */
        $path = (new \ReflectionClass($this))->getFileName();

        return \dirname($path);
    }
}
