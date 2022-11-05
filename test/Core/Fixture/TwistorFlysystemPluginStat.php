<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use League\Flysystem\AdapterInterface;
use Twistor\Flysystem\Plugin\Stat;

/**
 * @link https://github.com/kor3k/flysystem-stream-wrapper/blob/master/src/Flysystem/Plugin/Stat.php
 * @link https://github.com/kor3k/flysystem-stream-wrapper/commit/d02293ad90c219474c0fae10249a642b645c3b8e
 */
final class TwistorFlysystemPluginStat extends Stat
{
    protected function normalizePermissions(string $permissions): int
    {
        if (is_numeric($permissions)) {
            return $permissions & 0777;
        }

        // remove the type identifier
        $permissions = substr($permissions, 1);

        // map the string rights to the numeric counterparts
        $map = ['-' => '0', 'r' => '4', 'w' => '2', 'x' => '1'];
        $permissions = strtr($permissions, $map);

        // split up the permission groups
        $parts = str_split($permissions, 3);

        // convert the groups
        $mapper = function ($part) {
            return array_sum(str_split($part));
        };

        // converts to decimal number
        return octdec(implode('', array_map($mapper, $parts)));
    }

    protected function mergeMeta(array $metadata): array
    {
        $ret = static::$defaultMeta;

        $ret['uid'] = $this->uid->getUid();
        $ret['gid'] = $this->uid->getGid();

        $ret['mode'] = $metadata['type'] === 'dir' ? 040000 : 0100000;
        $visibility = $metadata['visibility'];

        if ($visibility != AdapterInterface::VISIBILITY_PUBLIC && $visibility != AdapterInterface::VISIBILITY_PRIVATE) {
            $visibility = $this->normalizePermissions($visibility) & 0044 ? AdapterInterface::VISIBILITY_PUBLIC : AdapterInterface::VISIBILITY_PRIVATE;
        }

        $ret['mode'] += $this->permissions[$metadata['type']][$visibility];

        if (isset($metadata['size'])) {
            $ret['size'] = (int) $metadata['size'];
        }
        if (isset($metadata['timestamp'])) {
            $ret['mtime'] = (int) $metadata['timestamp'];
            $ret['ctime'] = (int) $metadata['timestamp'];
        }

        $ret['atime'] = time();

        return array_merge(array_values($ret), $ret);
    }
}
