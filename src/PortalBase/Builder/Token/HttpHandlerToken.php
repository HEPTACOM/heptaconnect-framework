<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

class HttpHandlerToken
{
    private ?\Closure $run = null;

    private ?\Closure $options = null;

    private ?\Closure $get = null;

    private ?\Closure $post = null;

    private ?\Closure $put = null;

    private ?\Closure $patch = null;

    private ?\Closure $delete = null;

    public function __construct(
        private string $path
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRun(): ?\Closure
    {
        return $this->run;
    }

    public function setRun(\Closure $run): void
    {
        $this->run = $run;
    }

    public function getOptions(): ?\Closure
    {
        return $this->options;
    }

    public function setOptions(?\Closure $options): void
    {
        $this->options = $options;
    }

    public function getGet(): ?\Closure
    {
        return $this->get;
    }

    public function setGet(?\Closure $get): void
    {
        $this->get = $get;
    }

    public function getPost(): ?\Closure
    {
        return $this->post;
    }

    public function setPost(?\Closure $post): void
    {
        $this->post = $post;
    }

    public function getPut(): ?\Closure
    {
        return $this->put;
    }

    public function setPut(?\Closure $put): void
    {
        $this->put = $put;
    }

    public function getPatch(): ?\Closure
    {
        return $this->patch;
    }

    public function setPatch(?\Closure $patch): void
    {
        $this->patch = $patch;
    }

    public function getDelete(): ?\Closure
    {
        return $this->delete;
    }

    public function setDelete(?\Closure $delete): void
    {
        $this->delete = $delete;
    }
}
