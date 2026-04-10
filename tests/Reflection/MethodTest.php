<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_MethodTest extends TestCase
{
    protected $_classRaw;
    protected $_class;
    protected $_method;

    protected function setUp(): void
    {
        $this->_classRaw = new ReflectionClass('Zend_Server_Reflection');
        $this->_method   = $this->_classRaw->getMethod('reflectClass');
        $this->_class    = new Zend_Server_Reflection_Class($this->_classRaw);
    }

    public function test__construct(): void
    {
        $r = new Zend_Server_Reflection_Method($this->_class, $this->_method);
        $this->assertTrue($r instanceof Zend_Server_Reflection_Method);
        $this->assertTrue($r instanceof Zend_Server_Reflection_Function_Abstract);

        $r = new Zend_Server_Reflection_Method($this->_class, $this->_method, 'namespace');
        $this->assertEquals('namespace', $r->getNamespace());
    }

    public function testGetDeclaringClass(): void
    {
        $r = new Zend_Server_Reflection_Method($this->_class, $this->_method);

        $class = $r->getDeclaringClass();

        $this->assertTrue($class instanceof Zend_Server_Reflection_Class);
        $this->assertSame($this->_class, $class);
    }

    public function test__wakeup(): void
    {
        $r = new Zend_Server_Reflection_Method($this->_class, $this->_method);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Serialization of 'ReflectionMethod' is not allowed");
        $s = serialize($r);
        $u = unserialize($s);

        $this->assertTrue($u instanceof Zend_Server_Reflection_Method);
        $this->assertTrue($u instanceof Zend_Server_Reflection_Function_Abstract);
        $this->assertEquals($r->getName(), $u->getName());
        $this->assertEquals($r->getDeclaringClass()->getName(), $u->getDeclaringClass()->getName());
    }
}
