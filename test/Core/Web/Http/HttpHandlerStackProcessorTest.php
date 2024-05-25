<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackProcessor;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

#[CoversClass(LogMessage::class)]
#[CoversClass(HttpHandlerStackProcessor::class)]
#[CoversClass(HttpHandlerContract::class)]
#[CoversClass(HttpHandlerCollection::class)]
#[CoversClass(HttpHandlerStack::class)]
final class HttpHandlerStackProcessorTest extends TestCase
{
    public function testActingFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())
            ->method('critical')
            ->with(LogMessage::WEB_HTTP_HANDLE_NO_THROW());

        $stack = new HttpHandlerStack([new class() extends HttpHandlerContract {
            protected function supports(): string
            {
                return 'foobar';
            }

            protected function run(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                throw new \Exception('catch me if you can');
            }
        }]);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('withStatus')->willReturnSelf();
        $actor = new HttpHandlerStackProcessor($logger);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $context->method('getLogger')->willReturn($logger);

        $actor->processStack($request, $response, $stack, $context);
    }
}
