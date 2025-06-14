<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineUserAgentBundle\Traits\CreatedByUAAware;

class CreatedByUAAwareTest extends TestCase
{
    private object $testObject;

    public function test_getCreatedFromUa_withDefaultValue_returnsNull(): void
    {
        $result = $this->testObject->getCreatedFromUa();

        $this->assertNull($result);
    }

    public function test_setCreatedFromUa_withValidString_setsValue(): void
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';

        $result = $this->testObject->setCreatedFromUa($userAgent);

        $this->assertSame($this->testObject, $result);
        $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
    }

    public function test_setCreatedFromUa_withNull_setsNull(): void
    {
        // 先设置一个值
        $this->testObject->setCreatedFromUa('test');

        // 然后设置为 null
        $result = $this->testObject->setCreatedFromUa(null);

        $this->assertSame($this->testObject, $result);
        $this->assertNull($this->testObject->getCreatedFromUa());
    }

    public function test_setCreatedFromUa_withEmptyString_setsEmptyString(): void
    {
        $result = $this->testObject->setCreatedFromUa('');

        $this->assertSame($this->testObject, $result);
        $this->assertEquals('', $this->testObject->getCreatedFromUa());
    }

    public function test_setCreatedFromUa_withLongString_setsValue(): void
    {
        $longUserAgent = str_repeat('A', 1000);

        $result = $this->testObject->setCreatedFromUa($longUserAgent);

        $this->assertSame($this->testObject, $result);
        $this->assertEquals($longUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function test_setCreatedFromUa_withSpecialCharacters_setsValue(): void
    {
        $specialUserAgent = 'Mozilla/5.0 (测试中文; 特殊字符 !@#$%^&*()_+)';

        $result = $this->testObject->setCreatedFromUa($specialUserAgent);

        $this->assertSame($this->testObject, $result);
        $this->assertEquals($specialUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function test_setCreatedFromUa_withRealUserAgents_setsValue(): void
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
        ];

        foreach ($userAgents as $userAgent) {
            $result = $this->testObject->setCreatedFromUa($userAgent);

            $this->assertSame($this->testObject, $result);
            $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
        }
    }

    public function test_setCreatedFromUa_fluent_interface_worksCorrectly(): void
    {
        $userAgent = 'Test User Agent';

        $result = $this->testObject
            ->setCreatedFromUa($userAgent);

        $this->assertSame($this->testObject, $result);
        $this->assertEquals($userAgent, $this->testObject->getCreatedFromUa());
    }

    public function test_getCreatedFromUa_afterMultipleSet_returnsLatestValue(): void
    {
        $firstUserAgent = 'First User Agent';
        $secondUserAgent = 'Second User Agent';

        $this->testObject->setCreatedFromUa($firstUserAgent);
        $this->assertEquals($firstUserAgent, $this->testObject->getCreatedFromUa());

        $this->testObject->setCreatedFromUa($secondUserAgent);
        $this->assertEquals($secondUserAgent, $this->testObject->getCreatedFromUa());
    }

    public function test_trait_hasCorrectMethods(): void
    {
        $this->assertTrue(method_exists($this->testObject, 'getCreatedFromUa'));
        $this->assertTrue(method_exists($this->testObject, 'setCreatedFromUa'));
    }

    public function test_trait_hasCorrectReturnTypes(): void
    {
        $reflectionClass = new \ReflectionClass($this->testObject);

        $getMethod = $reflectionClass->getMethod('getCreatedFromUa');
        $this->assertTrue($getMethod->hasReturnType());
        $this->assertEquals('?string', (string) $getMethod->getReturnType());

        $setMethod = $reflectionClass->getMethod('setCreatedFromUa');
        $this->assertTrue($setMethod->hasReturnType());
        $this->assertEquals('static', (string) $setMethod->getReturnType());
    }

    protected function setUp(): void
    {
        $this->testObject = new class {
            use CreatedByUAAware;
        };
    }
}
