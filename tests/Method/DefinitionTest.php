<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Method_DefinitionTest extends TestCase
{
    protected $definition;

    protected function setUp(): void
    {
        $this->definition = new Zend_Server_Method_Definition();
    }

    protected function tearDown(): void
    {
    }

    public function testCallbackShouldBeNullByDefault(): void
    {
        $this->assertNull($this->definition->getCallback());
    }

    public function testSetCallbackShouldAcceptMethodCallback(): void
    {
        $callback = new Zend_Server_Method_Callback();
        $this->definition->setCallback($callback);
        $test = $this->definition->getCallback();
        $this->assertSame($callback, $test);
    }

    public function testSetCallbackShouldAcceptArray(): void
    {
        $callback = array(
            'type'     => 'function',
            'function' => 'foo',
        );
        $this->definition->setCallback($callback);
        $test = $this->definition->getCallback()->toArray();
        $this->assertSame($callback, $test);
    }

    public function testMethodHelpShouldBeEmptyStringByDefault(): void
    {
        $this->assertEquals('', $this->definition->getMethodHelp());
    }

    public function testMethodHelpShouldBeMutable(): void
    {
        $this->assertEquals('', $this->definition->getMethodHelp());
        $this->definition->setMethodHelp('foo bar');
        $this->assertEquals('foo bar', $this->definition->getMethodHelp());
    }

    public function testNameShouldBeNullByDefault(): void
    {
        $this->assertNull($this->definition->getName());
    }

    public function testNameShouldBeMutable(): void
    {
        $this->assertNull($this->definition->getName());
        $this->definition->setName('foo.bar');
        $this->assertEquals('foo.bar', $this->definition->getName());
    }

    public function testObjectShouldBeNullByDefault(): void
    {
        $this->assertNull($this->definition->getObject());
    }

    public function testObjectShouldBeMutable(): void
    {
        $this->assertNull($this->definition->getObject());
        $object = new stdClass;
        $this->definition->setObject($object);
        $this->assertEquals($object, $this->definition->getObject());
    }

    public function testSettingObjectToNonObjectShouldThrowException(): void
    {
        $this->expectException(Zend_Server_Exception::class);
        $this->definition->setObject('foo');
    }

    public function testInvokeArgumentsShouldBeEmptyArrayByDefault(): void
    {
        $args = $this->definition->getInvokeArguments();
        $this->assertTrue(is_array($args));
        $this->assertTrue(empty($args));
    }

    public function testInvokeArgumentsShouldBeMutable(): void
    {
        $this->testInvokeArgumentsShouldBeEmptyArrayByDefault();
        $args = array('foo', array('bar', 'baz'), new stdClass);
        $this->definition->setInvokeArguments($args);
        $this->assertSame($args, $this->definition->getInvokeArguments());
    }

    public function testPrototypesShouldBeEmptyArrayByDefault(): void
    {
        $prototypes = $this->definition->getPrototypes();
        $this->assertTrue(is_array($prototypes));
        $this->assertTrue(empty($prototypes));
    }

    public function testDefinitionShouldAllowAddingSinglePrototypes(): void
    {
        $this->testPrototypesShouldBeEmptyArrayByDefault();
        $prototype1 = new Zend_Server_Method_Prototype;
        $this->definition->addPrototype($prototype1);
        $test = $this->definition->getPrototypes();
        $this->assertSame($prototype1, $test[0]);

        $prototype2 = new Zend_Server_Method_Prototype;
        $this->definition->addPrototype($prototype2);
        $test = $this->definition->getPrototypes();
        $this->assertSame($prototype1, $test[0]);
        $this->assertSame($prototype2, $test[1]);
    }

    public function testDefinitionShouldAllowAddingMultiplePrototypes(): void
    {
        $prototype1 = new Zend_Server_Method_Prototype;
        $prototype2 = new Zend_Server_Method_Prototype;
        $prototypes = array($prototype1, $prototype2);
        $this->definition->addPrototypes($prototypes);
        $this->assertSame($prototypes, $this->definition->getPrototypes());
    }

    public function testSetPrototypesShouldOverwriteExistingPrototypes(): void
    {
        $this->testDefinitionShouldAllowAddingMultiplePrototypes();

        $prototype1 = new Zend_Server_Method_Prototype;
        $prototype2 = new Zend_Server_Method_Prototype;
        $prototypes = array($prototype1, $prototype2);
        $this->assertNotSame($prototypes, $this->definition->getPrototypes());
        $this->definition->setPrototypes($prototypes);
        $this->assertSame($prototypes, $this->definition->getPrototypes());
    }

    public function testDefintionShouldSerializeToArray(): void
    {
        $name       = 'foo.bar';
        $callback   = array('function' => 'foo', 'type' => 'function');
        $prototypes = array(array('returnType' => 'struct', 'parameters' => array('string', 'array')));
        $methodHelp = 'foo bar';
        $object     = new stdClass;
        $invokeArgs = array('foo', array('bar', 'baz'));
        $this->definition->setName($name)
                         ->setCallback($callback)
                         ->setPrototypes($prototypes)
                         ->setMethodHelp($methodHelp)
                         ->setObject($object)
                         ->setInvokeArguments($invokeArgs);
        $test = $this->definition->toArray();
        $this->assertEquals($name, $test['name']);
        $this->assertEquals($callback, $test['callback']);
        $this->assertEquals($prototypes, $test['prototypes']);
        $this->assertEquals($methodHelp, $test['methodHelp']);
        $this->assertEquals($object, $test['object']);
        $this->assertEquals($invokeArgs, $test['invokeArguments']);
    }

    public function testPassingOptionsToConstructorShouldSetObjectState(): void
    {
        $options = array(
            'name'            => 'foo.bar',
            'callback'        => array('function' => 'foo', 'type' => 'function'),
            'prototypes'      => array(array('returnType' => 'struct', 'parameters' => array('string', 'array'))),
            'methodHelp'      => 'foo bar',
            'object'          => new stdClass,
            'invokeArguments' => array('foo', array('bar', 'baz')),
        );
        $definition = new Zend_Server_Method_Definition($options);
        $test = $definition->toArray();
        $this->assertEquals($options['name'], $test['name']);
        $this->assertEquals($options['callback'], $test['callback']);
        $this->assertEquals($options['prototypes'], $test['prototypes']);
        $this->assertEquals($options['methodHelp'], $test['methodHelp']);
        $this->assertEquals($options['object'], $test['object']);
        $this->assertEquals($options['invokeArguments'], $test['invokeArguments']);
    }
}
