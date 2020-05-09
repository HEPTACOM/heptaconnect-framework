<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Throwable;

class StorageMethodNotImplemented extends \Exception
{
    /**
     * @var class-string<\Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface>
     */
    private string $class;

    private string $method;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface> $class
     * @psalm-param class-string<\Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface> $class
     */
    public function __construct(string $class, string $method, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(\sprintf('Method %s in class %s is not implemented', $method, $class), $code, $previous);
        $this->class = $class;
        $this->method = $method;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface>
     */
    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
