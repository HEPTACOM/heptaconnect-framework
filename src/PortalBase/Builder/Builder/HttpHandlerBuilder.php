<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Support\BuilderPriorityTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken;

class HttpHandlerBuilder
{
    use BuilderPriorityTrait;

    private HttpHandlerToken $token;

    public function __construct(HttpHandlerToken $token)
    {
        $this->token = $token;
    }

    public function run(\Closure $run): self
    {
        $this->token->setRun($run);

        return $this;
    }

    public function options(\Closure $options): self
    {
        $this->token->setOptions($options);

        return $this;
    }

    public function get(\Closure $get): self
    {
        $this->token->setGet($get);

        return $this;
    }

    public function post(\Closure $post): self
    {
        $this->token->setPost($post);

        return $this;
    }

    public function put(\Closure $put): self
    {
        $this->token->setPut($put);

        return $this;
    }

    public function patch(\Closure $patch): self
    {
        $this->token->setPatch($patch);

        return $this;
    }

    public function delete(\Closure $delete): self
    {
        $this->token->setDelete($delete);

        return $this;
    }
}
