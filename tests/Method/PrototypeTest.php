<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Method_PrototypeTest extends TestCase
{
    protected $prototype;

    protected function setUp(): void
    {
        $this->prototype = new Zend_Server_Method_Prototype();
    }

    protected function tearDown(): void
    {
    }

    public function testReturnTypeShouldBeVoidByDefault(): void
    {
        $this->assertEquals('void', $this->prototype->getReturnType());
    }

    public function testReturnTypeShouldBeMutable(): void
    {
        $this->assertEquals('void', $this->prototype->getReturnType());
        $this->prototype->setReturnType('string');
        $this->assertEquals('string', $this->prototype->getReturnType());
    }

    public function testParametersShouldBeEmptyArrayByDefault(): void
    {
        $params = $this->prototype->getParameters();
        $this->assertTrue(is_array($params));
        $this->assertTrue(empty($params));
    }

    public function testPrototypeShouldAllowAddingSingleParameters(): void
    {
        $this->testParametersShouldBeEmptyArrayByDefault();
        $this->prototype->addParameter('string');
        $params = $this->prototype->getParameters();
        $this->assertTrue(is_array($params));
        $this->assertEquals(1, count($params));
        $this->assertEquals('string', $params[0]);

        $this->prototype->addParameter('array');
        $params = $this->prototype->getParameters();
        $this->assertEquals(2, count($params));
        $this->assertEquals('string', $params[0]);
        $this->assertEquals('array', $params[1]);
    }

    public function testPrototypeShouldAllowAddingParameterObjects(): void
    {
        $parameter = new Zend_Server_Method_Parameter(array(
            'type' => 'string',
            'name' => 'foo',
        ));
        $this->prototype->addParameter($parameter);
        $this->assertSame($parameter, $this->prototype->getParameter('foo'));
    }

    public function testPrototypeShouldAllowFetchingParameterByNameOrIndex(): void
    {
        $parameter = new Zend_Server_Method_Parameter(array(
            'type' => 'string',
            'name' => 'foo',
        ));
        $this->prototype->addParameter($parameter);
        $test1 = $this->prototype->getParameter('foo');
        $test2 = $this->prototype->getParameter(0);
        $this->assertSame($test1, $test2);
        $this->assertSame($parameter, $test1);
        $this->assertSame($parameter, $test2);
    }

    public function testPrototypeShouldAllowRetrievingParameterObjects(): void
    {
        $this->prototype->addParameters(array('string', 'array'));
        $parameters = $this->prototype->getParameterObjects();
        foreach ($parameters as $parameter) {
            $this->assertTrue($parameter instanceof Zend_Server_Method_Parameter);
        }
    }

    public function testPrototypeShouldAllowAddingMultipleParameters(): void
    {
        $this->testParametersShouldBeEmptyArrayByDefault();
        $params = array(
            'string',
            'array',
        );
        $this->prototype->addParameters($params);
        $test = $this->prototype->getParameters();
        $this->assertSame($params, $test);
    }

    public function testSetParametersShouldOverwriteParameters(): void
    {
        $this->testPrototypeShouldAllowAddingMultipleParameters();
        $params = array(
            'bool',
            'base64',
            'struct',
        );
        $this->prototype->setParameters($params);
        $test = $this->prototype->getParameters();
        $this->assertSame($params, $test);
    }

    public function testPrototypeShouldSerializeToArray(): void
    {
        $return = 'string';
        $params = array(
            'bool',
            'base64',
            'struct',
        );
        $this->prototype->setReturnType($return)
                        ->setParameters($params);
        $test = $this->prototype->toArray();
        $this->assertEquals($return, $test['returnType']);
        $this->assertEquals($params, $test['parameters']);
    }

    public function testConstructorShouldSetObjectStateFromOptions(): void
    {
        $options = array(
            'returnType' => 'string',
            'parameters' => array(
                'bool',
                'base64',
                'struct',
            ),
        );
        $prototype = new Zend_Server_Method_Prototype($options);
        $test = $prototype->toArray();
        $this->assertSame($options, $test);
    }
}
