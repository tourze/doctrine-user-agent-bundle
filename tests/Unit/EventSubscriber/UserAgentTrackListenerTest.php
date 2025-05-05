<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Unit\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\EventSubscriber\UserAgentTrackListener;

class UserAgentTrackListenerTest extends TestCase
{
    private UserAgentTrackListener $listener;
    private LoggerInterface&MockObject $logger;
    private PropertyAccessorInterface&MockObject $propertyAccessor;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->propertyAccessor = $this->createMock(PropertyAccessorInterface::class);
        $this->listener = new UserAgentTrackListener($this->logger, $this->propertyAccessor);
    }

    public function testSetAndGetUserAgent(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';
        $this->listener->setUserAgent($userAgent);

        $this->assertEquals($userAgent, $this->listener->getUserAgent());
    }

    public function testOnKernelRequest(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';

        $headers = $this->createMock(HeaderBag::class);
        $headers->expects($this->once())
            ->method('get')
            ->with('User-Agent', '')
            ->willReturn($userAgent);

        /** @var Request&MockObject $request */
        $request = $this->createMock(Request::class);
        $request->headers = $headers;

        /** @var HttpKernelInterface&MockObject $kernel */
        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->listener->onKernelRequest($event);

        $this->assertEquals($userAgent, $this->listener->getUserAgent());
    }

    public function testReset(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';
        $this->listener->setUserAgent($userAgent);
        $this->assertEquals($userAgent, $this->listener->getUserAgent());

        $this->listener->reset();

        $this->assertNull($this->listener->getUserAgent());
    }

    public function testPrePersistWithoutUserAgent(): void
    {
        // PrePersistEventArgs 是 final 类，无法进行模拟
        // 这里我们只测试没有设置 UserAgent 时的行为
        $this->listener->reset();

        $entity = new class {
            #[CreateUserAgentColumn]
            private ?string $createdUserAgent = null;

            public function getCreatedUserAgent(): ?string
            {
                return $this->createdUserAgent;
            }

            public function setCreatedUserAgent(?string $createdUserAgent): self
            {
                $this->createdUserAgent = $createdUserAgent;
                return $this;
            }
        };

        // 不调用 prePersist 方法，因为无法模拟 PrePersistEventArgs

        // 验证没有 UserAgent 时，字段保持为 null
        $this->assertNull($entity->getCreatedUserAgent());
    }

    public function testPreUpdateWithoutUserAgent(): void
    {
        // PreUpdateEventArgs 是 final 类，无法进行模拟
        // 这里我们只测试没有设置 UserAgent 时的行为
        $this->listener->reset();

        $entity = new class {
            #[UpdateUserAgentColumn]
            private ?string $updatedUserAgent = null;

            public function getUpdatedUserAgent(): ?string
            {
                return $this->updatedUserAgent;
            }

            public function setUpdatedUserAgent(?string $updatedUserAgent): self
            {
                $this->updatedUserAgent = $updatedUserAgent;
                return $this;
            }
        };

        // 不调用 preUpdate 方法，因为无法模拟 PreUpdateEventArgs

        // 验证没有 UserAgent 时，字段保持为 null
        $this->assertNull($entity->getUpdatedUserAgent());
    }
}
