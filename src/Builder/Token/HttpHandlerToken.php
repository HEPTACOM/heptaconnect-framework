<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

class HttpHandlerToken
{
    private string $path;

    /** @var callable|null */
    private $run = null;

    /** @var callable|null */
    private $options = null;

    /** @var callable|null */
    private $get = null;

    /** @var callable|null */
    private $post = null;

    /** @var callable|null */
    private $put = null;

    /** @var callable|null */
    private $patch = null;

    /** @var callable|null */
    private $delete = null;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRun(): ?callable
    {
        return $this->run;
    }

    public function setRun(callable $run): void
    {
        $this->run = $run;
    }

    public function getOptions(): ?callable
    {
        return $this->options;
    }

    public function setOptions(?callable $options): void
    {
        $this->options = $options;
    }

    public function getGet(): ?callable
    {
        return $this->get;
    }

    public function setGet(?callable $get): void
    {
        $this->get = $get;
    }

    public function getPost(): ?callable
    {
        return $this->post;
    }

    public function setPost(?callable $post): void
    {
        $this->post = $post;
    }

    public function getPut(): ?callable
    {
        return $this->put;
    }

    public function setPut(?callable $put): void
    {
        $this->put = $put;
    }

    public function getPatch(): ?callable
    {
        return $this->patch;
    }

    public function setPatch(?callable $patch): void
    {
        $this->patch = $patch;
    }

    public function getDelete(): ?callable
    {
        return $this->delete;
    }

    public function setDelete(?callable $delete): void
    {
        $this->delete = $delete;
    }
}
