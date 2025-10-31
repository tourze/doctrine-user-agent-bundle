<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Traits;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineUserAgentBundle\Traits\CreatedByUAAware;

/**
 * @internal
 */
#[CoversClass(CreatedByUAAware::class)]
final class CreatedByUAAwareTest extends TestCase
{
    private TestEntityWithCreatedByUA $testObject;

    public function testGetCreatedFromUaWithDefaultValueReturnsNull(): void
    {
        $result = $this->testObject->getCreatedFromUa();

        $this->assertNull($result);
    }

    public function testSetCreatedFromUaWithValidStringSetsValue(): void
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';

        $this->testObject->setCreatedFromUa($userAgent);

        $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
    }

    public function testSetCreatedFromUaWithNullSetsNull(): void
    {
        // 先设置一个值
        $this->testObject->setCreatedFromUa('test');

        // 然后设置为 null
        $this->testObject->setCreatedFromUa(null);

        $this->assertNull($this->testObject->getCreatedFromUa());
    }

    public function testSetCreatedFromUaWithEmptyStringSetsEmptyString(): void
    {
        $this->testObject->setCreatedFromUa('');

        $this->assertEquals('', $this->testObject->getCreatedFromUa());
    }

    public function testSetCreatedFromUaWithLongStringSetsValue(): void
    {
        $longUserAgent = str_repeat('A', 1000);

        $this->testObject->setCreatedFromUa($longUserAgent);

        $this->assertEquals($longUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function testSetCreatedFromUaWithSpecialCharactersSetsValue(): void
    {
        $specialUserAgent = 'Mozilla/5.0 (测试中文; 特殊字符 !@#$%^&*()_+)';

        $this->testObject->setCreatedFromUa($specialUserAgent);

        $this->assertEquals($specialUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function testSetCreatedFromUaWithRealUserAgentsSetsValue(): void
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
        ];

        foreach ($userAgents as $userAgent) {
            $this->testObject->setCreatedFromUa($userAgent);

            $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
        }
    }

    public function testSetCreatedFromUaWorksCorrectly(): void
    {
        $userAgent = 'Test User Agent';

        $this->testObject->setCreatedFromUa($userAgent);

        $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
    }

    public function testGetCreatedFromUaAfterMultipleSetReturnsLatestValue(): void
    {
        $firstUserAgent = 'First User Agent';
        $secondUserAgent = 'Second User Agent';

        $this->testObject->setCreatedFromUa($firstUserAgent);
        $this->assertEquals($firstUserAgent, $this->testObject->getCreatedFromUa());

        $this->testObject->setCreatedFromUa($secondUserAgent);
        $this->assertEquals($secondUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function testTraitHasCorrectReturnTypes(): void
    {
        $reflectionClass = new \ReflectionClass($this->testObject);

        $getMethod = $reflectionClass->getMethod('getCreatedFromUa');
        $this->assertTrue($getMethod->hasReturnType());
        $this->assertEquals('?string', (string) $getMethod->getReturnType());

        $setMethod = $reflectionClass->getMethod('setCreatedFromUa');
        $this->assertTrue($setMethod->hasReturnType());
        $this->assertEquals('void', (string) $setMethod->getReturnType());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObject = new TestEntityWithCreatedByUA();
    }
}
