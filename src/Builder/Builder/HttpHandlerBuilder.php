<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken;

class HttpHandlerBuilder
{
    private HttpHandlerToken $token;

    public function __construct(HttpHandlerToken $token)
    {
        $this->token = $token;
    }

    public function run(callable $run): self
    {
        $this->token->setRun($run);

        return $this;
    }

    public function options(callable $options): self
    {
        $this->token->setOptions($options);

        return $this;
    }

    public function get(callable $get): self
    {
        $this->token->setGet($get);

        return $this;
    }

    public function post(callable $post): self
    {
        $this->token->setPost($post);

        return $this;
    }

    public function put(callable $put): self
    {
        $this->token->setPut($put);

        return $this;
    }

    public function patch(callable $patch): self
    {
        $this->token->setPatch($patch);

        return $this;
    }

    public function delete(callable $delete): self
    {
        $this->token->setDelete($delete);

        return $this;
    }
}
