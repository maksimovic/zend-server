<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_ClassTest extends TestCase
{
    public function test__construct(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));
        $this->assertTrue($r instanceof Zend_Server_Reflection_Class);
        $this->assertEquals('', $r->getNamespace());

        $methods = $r->getMethods();
        $this->assertIsArray($methods);
        foreach ($methods as $m) {
            $this->assertTrue($m instanceof Zend_Server_Reflection_Method);
        }

        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'), 'namespace');
        $this->assertEquals('namespace', $r->getNamespace());
    }

    public function test__call(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));
        $this->assertIsString($r->getName());
        $this->assertEquals('Zend_Server_Reflection', $r->getName());
    }

    public function testGetSet(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));
        $r->system = true;
        $this->assertTrue($r->system);
    }

    public function testGetMethods(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));

        $methods = $r->getMethods();
        $this->assertIsArray($methods);
        foreach ($methods as $m) {
            $this->assertTrue($m instanceof Zend_Server_Reflection_Method);
        }
    }

    public function testGetNamespace(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));
        $this->assertEquals('', $r->getNamespace());
        $r->setNamespace('namespace');
        $this->assertEquals('namespace', $r->getNamespace());
    }

    public function test__wakeup(): void
    {
        $r = new Zend_Server_Reflection_Class(new ReflectionClass('Zend_Server_Reflection'));
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Serialization of 'ReflectionMethod' is not allowed");
        $s = serialize($r);
        $u = unserialize($s);

        $this->assertTrue($u instanceof Zend_Server_Reflection_Class);
        $this->assertEquals('', $u->getNamespace());
        $this->assertEquals($r->getName(), $u->getName());
        $rMethods = $r->getMethods();
        $uMethods = $r->getMethods();

        $this->assertCount(count($rMethods), $uMethods);
    }
}
