<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_ReturnValueTest extends TestCase
{
    public function test__construct(): void
    {
        $obj = new Zend_Server_Reflection_ReturnValue();
        $this->assertTrue($obj instanceof Zend_Server_Reflection_ReturnValue);
    }

    public function testGetType(): void
    {
        $obj = new Zend_Server_Reflection_ReturnValue();
        $this->assertEquals('mixed', $obj->getType());

        $obj->setType('array');
        $this->assertEquals('array', $obj->getType());
    }

    public function testSetType(): void
    {
        $obj = new Zend_Server_Reflection_ReturnValue();

        $obj->setType('array');
        $this->assertEquals('array', $obj->getType());
    }

    public function testGetDescription(): void
    {
        $obj = new Zend_Server_Reflection_ReturnValue('string', 'Some description');
        $this->assertEquals('Some description', $obj->getDescription());

        $obj->setDescription('New Description');
        $this->assertEquals('New Description', $obj->getDescription());
    }

    public function testSetDescription(): void
    {
        $obj = new Zend_Server_Reflection_ReturnValue();

        $obj->setDescription('New Description');
        $this->assertEquals('New Description', $obj->getDescription());
    }
}
