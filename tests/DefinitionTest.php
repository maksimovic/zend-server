<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_DefinitionTest extends TestCase
{
    protected $definition;

    protected function setUp(): void
    {
        $this->definition = new Zend_Server_Definition();
    }

    protected function tearDown(): void
    {
    }

    public function testMethodsShouldBeEmptyArrayByDefault(): void
    {
        $methods = $this->definition->getMethods();
        $this->assertTrue(is_array($methods));
        $this->assertTrue(empty($methods));
    }

    public function testDefinitionShouldAllowAddingSingleMethods(): void
    {
        $method = new Zend_Server_Method_Definition(array('name' => 'foo'));
        $this->definition->addMethod($method);
        $methods = $this->definition->getMethods();
        $this->assertEquals(1, count($methods));
        $this->assertSame($method, $methods['foo']);
        $this->assertSame($method, $this->definition->getMethod('foo'));
    }

    public function testDefinitionShouldAllowAddingMultipleMethods(): void
    {
        $method1 = new Zend_Server_Method_Definition(array('name' => 'foo'));
        $method2 = new Zend_Server_Method_Definition(array('name' => 'bar'));
        $this->definition->addMethods(array($method1, $method2));
        $methods = $this->definition->getMethods();
        $this->assertEquals(2, count($methods));
        $this->assertSame($method1, $methods['foo']);
        $this->assertSame($method1, $this->definition->getMethod('foo'));
        $this->assertSame($method2, $methods['bar']);
        $this->assertSame($method2, $this->definition->getMethod('bar'));
    }

    public function testSetMethodsShouldOverwriteExistingMethods(): void
    {
        $this->testDefinitionShouldAllowAddingMultipleMethods();
        $method1 = new Zend_Server_Method_Definition(array('name' => 'foo'));
        $method2 = new Zend_Server_Method_Definition(array('name' => 'bar'));
        $methods = array($method1, $method2);
        $this->assertNotEquals($methods, $this->definition->getMethods());
        $this->definition->setMethods($methods);
        $test = $this->definition->getMethods();
        $this->assertEquals(array_values($methods), array_values($test));
    }

    public function testHasMethodShouldReturnFalseWhenMethodNotRegisteredWithDefinition(): void
    {
        $this->assertFalse($this->definition->hasMethod('foo'));
    }

    public function testHasMethodShouldReturnTrueWhenMethodRegisteredWithDefinition(): void
    {
        $this->testDefinitionShouldAllowAddingMultipleMethods();
        $this->assertTrue($this->definition->hasMethod('foo'));
    }

    public function testDefinitionShouldAllowRemovingIndividualMethods(): void
    {
        $this->testDefinitionShouldAllowAddingMultipleMethods();
        $this->assertTrue($this->definition->hasMethod('foo'));
        $this->definition->removeMethod('foo');
        $this->assertFalse($this->definition->hasMethod('foo'));
    }

    public function testDefinitionShouldAllowClearingAllMethods(): void
    {
        $this->testDefinitionShouldAllowAddingMultipleMethods();
        $this->definition->clearMethods();
        $test = $this->definition->getMethods();
        $this->assertTrue(empty($test));
    }

    public function testDefinitionShouldSerializeToArray(): void
    {
        $method = array(
            'name' => 'foo.bar',
            'callback' => array(
                'type'     => 'function',
                'function' => 'bar',
            ),
            'prototypes' => array(
                array(
                    'returnType' => 'string',
                    'parameters' => array('string'),
                ),
            ),
            'methodHelp' => 'Foo Bar!',
            'invokeArguments' => array('foo'),
        );
        $definition = new Zend_Server_Definition();
        $definition->addMethod($method);
        $test = $definition->toArray();
        $this->assertEquals(1, count($test));
        $test = array_shift($test);
        $this->assertEquals($method['name'], $test['name']);
        $this->assertEquals($method['methodHelp'], $test['methodHelp']);
        $this->assertEquals($method['invokeArguments'], $test['invokeArguments']);
        $this->assertEquals($method['prototypes'][0]['returnType'], $test['prototypes'][0]['returnType']);
    }

    public function testPassingOptionsToConstructorShouldSetObjectState(): void
    {
        $method = array(
            'name' => 'foo.bar',
            'callback' => array(
                'type'     => 'function',
                'function' => 'bar',
            ),
            'prototypes' => array(
                array(
                    'returnType' => 'string',
                    'parameters' => array('string'),
                ),
            ),
            'methodHelp' => 'Foo Bar!',
            'invokeArguments' => array('foo'),
        );
        $options = array($method);
        $definition = new Zend_Server_Definition($options);
        $test = $definition->toArray();
        $this->assertEquals(1, count($test));
        $test = array_shift($test);
        $this->assertEquals($method['name'], $test['name']);
        $this->assertEquals($method['methodHelp'], $test['methodHelp']);
        $this->assertEquals($method['invokeArguments'], $test['invokeArguments']);
        $this->assertEquals($method['prototypes'][0]['returnType'], $test['prototypes'][0]['returnType']);
    }
}
