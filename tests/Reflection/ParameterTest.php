<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_ParameterTest extends TestCase
{
    protected function _getParameter()
    {
        $method = new ReflectionMethod('Zend_Server_Reflection_Parameter', 'setType');
        $parameters = $method->getParameters();
        return $parameters[0];
    }

    public function test__construct(): void
    {
        $parameter = $this->_getParameter();

        $reflection = new Zend_Server_Reflection_Parameter($parameter);
        $this->assertTrue($reflection instanceof Zend_Server_Reflection_Parameter);
    }

    public function test__call(): void
    {
        $r = new Zend_Server_Reflection_Parameter($this->_getParameter());

        // just test a few call proxies...
        $this->assertTrue(is_bool($r->allowsNull()));
        $this->assertTrue(is_bool($r->isOptional()));
    }

    public function testGetSetType(): void
    {
        $r = new Zend_Server_Reflection_Parameter($this->_getParameter());
        $this->assertEquals('mixed', $r->getType());

        $r->setType('string');
        $this->assertEquals('string', $r->getType());
    }

    public function testGetDescription(): void
    {
        $r = new Zend_Server_Reflection_Parameter($this->_getParameter());
        $this->assertEquals('', $r->getDescription());

        $r->setDescription('parameter description');
        $this->assertEquals('parameter description', $r->getDescription());
    }

    public function testSetPosition(): void
    {
        $r = new Zend_Server_Reflection_Parameter($this->_getParameter());
        $this->assertEquals(null, $r->getPosition());

        $r->setPosition(3);
        $this->assertEquals(3, $r->getPosition());
    }
}
