<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_PrototypeTest extends TestCase
{
    protected $_r;
    protected $_parametersRaw;
    protected $_parameters;

    protected function setUp(): void
    {
        $class = new ReflectionClass('Zend_Server_Reflection');
        $method = $class->getMethod('reflectClass');
        $parameters = $method->getParameters();
        $this->_parametersRaw = $parameters;

        $fParameters = array();
        foreach ($parameters as $p) {
            $fParameters[] = new Zend_Server_Reflection_Parameter($p);
        }
        $this->_parameters = $fParameters;

        $this->_r = new Zend_Server_Reflection_Prototype(new Zend_Server_Reflection_ReturnValue('void', 'No return'));
    }

    protected function tearDown(): void
    {
        unset($this->_r);
        unset($this->_parameters);
        unset($this->_parametersRaw);
    }

    public function test__construct(): void
    {
        $this->assertTrue($this->_r instanceof Zend_Server_Reflection_Prototype);

        try {
            $r1 = new Zend_Server_Reflection_Prototype($this->_r->getReturnValue(), $this->_parametersRaw);
            $this->fail('Construction should only accept Z_S_R_Parameters');
        } catch (Exception $e) {
            // do nothing
        }

        try {
            $r1 = new Zend_Server_Reflection_Prototype($this->_r->getReturnValue(), 'string');
            $this->fail('Construction requires an array of parameters');
        } catch (Exception $e) {
            // do nothing
        }
    }

    public function testGetReturnType(): void
    {
        $this->assertEquals('void', $this->_r->getReturnType());
    }

    public function testGetReturnValue(): void
    {
        $this->assertTrue($this->_r->getReturnValue() instanceof Zend_Server_Reflection_ReturnValue);
    }

    public function testGetParameters(): void
    {
        $r = new Zend_Server_Reflection_Prototype($this->_r->getReturnValue(), $this->_parameters);
        $p = $r->getParameters();

        $this->assertTrue(is_array($p));
        foreach ($p as $parameter) {
            $this->assertTrue($parameter instanceof Zend_Server_Reflection_Parameter);
        }

        $this->assertTrue($p === $this->_parameters);
    }
}
