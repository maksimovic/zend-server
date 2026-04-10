<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Method_CallbackTest extends TestCase
{
    protected $callback;

    protected function setUp(): void
    {
        $this->callback = new Zend_Server_Method_Callback();
    }

    protected function tearDown(): void
    {
    }

    public function testClassShouldBeNullByDefault(): void
    {
        $this->assertNull($this->callback->getClass());
    }

    public function testClassShouldBeMutable(): void
    {
        $this->assertNull($this->callback->getClass());
        $this->callback->setClass('Foo');
        $this->assertEquals('Foo', $this->callback->getClass());
    }

    public function testMethodShouldBeNullByDefault(): void
    {
        $this->assertNull($this->callback->getMethod());
    }

    public function testMethodShouldBeMutable(): void
    {
        $this->assertNull($this->callback->getMethod());
        $this->callback->setMethod('foo');
        $this->assertEquals('foo', $this->callback->getMethod());
    }

    public function testFunctionShouldBeNullByDefault(): void
    {
        $this->assertNull($this->callback->getFunction());
    }

    public function testFunctionShouldBeMutable(): void
    {
        $this->assertNull($this->callback->getFunction());
        $this->callback->setFunction('foo');
        $this->assertEquals('foo', $this->callback->getFunction());
    }

    public function testTypeShouldBeNullByDefault(): void
    {
        $this->assertNull($this->callback->getType());
    }

    public function testTypeShouldBeMutable(): void
    {
        $this->assertNull($this->callback->getType());
        $this->callback->setType('instance');
        $this->assertEquals('instance', $this->callback->getType());
    }

    public function testSettingTypeShouldThrowExceptionWhenInvalidTypeProvided(): void
    {
        $this->expectException(Zend_Server_Exception::class);
        $this->callback->setType('bogus');
    }

    public function testCallbackShouldSerializeToArray(): void
    {
        $this->callback->setClass('Foo')
                       ->setMethod('bar')
                       ->setType('instance');
        $test = $this->callback->toArray();
        $this->assertTrue(is_array($test));
        $this->assertEquals('Foo', $test['class']);
        $this->assertEquals('bar', $test['method']);
        $this->assertEquals('instance', $test['type']);
    }

    public function testConstructorShouldSetStateFromOptions(): void
    {
        $options = array(
            'type'   => 'static',
            'class'  => 'Foo',
            'method' => 'bar',
        );
        $callback = new Zend_Server_Method_Callback($options);
        $test = $callback->toArray();
        $this->assertSame($options, $test);
    }

    public function testSettingFunctionShouldSetTypeAsFunction(): void
    {
        $this->assertNull($this->callback->getType());
        $this->callback->setFunction('foo');
        $this->assertEquals('function', $this->callback->getType());
    }
}
