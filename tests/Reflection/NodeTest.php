<?php

use PHPUnit\Framework\TestCase;

class Zend_Server_Reflection_NodeTest extends TestCase
{
    public function test__construct(): void
    {
        $node = new Zend_Server_Reflection_Node('string');
        $this->assertTrue($node instanceof Zend_Server_Reflection_Node);
        $this->assertEquals('string', $node->getValue());
        $this->assertTrue(null === $node->getParent());
        $children = $node->getChildren();
        $this->assertTrue(empty($children));

        $child = new Zend_Server_Reflection_Node('array', $node);
        $this->assertTrue($child instanceof Zend_Server_Reflection_Node);
        $this->assertEquals('array', $child->getValue());
        $this->assertTrue($node === $child->getParent());
        $children = $child->getChildren();
        $this->assertTrue(empty($children));

        $children = $node->getChildren();
        $this->assertTrue($child === $children[0]);
    }

    public function testSetParent(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $child  = new Zend_Server_Reflection_Node('array');

        $child->setParent($parent);

        $this->assertTrue($parent === $child->getParent());
    }

    public function testCreateChild(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $child = $parent->createChild('array');

        $this->assertTrue($child instanceof Zend_Server_Reflection_Node);
        $this->assertTrue($parent === $child->getParent());
        $children = $parent->getChildren();
        $this->assertTrue($child === $children[0]);
    }

    public function testAttachChild(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $child  = new Zend_Server_Reflection_Node('array');

        $parent->attachChild($child);
        $this->assertTrue($parent === $child->getParent());
        $children = $parent->getChildren();
        $this->assertTrue($child === $children[0]);
    }

    public function testGetChildren(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $child = $parent->createChild('array');

        $children = $parent->getChildren();
        $types = array();
        foreach ($children as $c) {
            $types[] = $c->getValue();
        }
        $this->assertTrue(is_array($children));
        $this->assertEquals(1, count($children), var_export($types, 1));
        $this->assertTrue($child === $children[0]);
    }

    public function testHasChildren(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');

        $this->assertFalse($parent->hasChildren());
        $parent->createChild('array');
        $this->assertTrue($parent->hasChildren());
    }

    public function testGetParent(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $child = $parent->createChild('array');

        $this->assertTrue(null === $parent->getParent());
        $this->assertTrue($parent === $child->getParent());
    }

    public function testGetValue(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $this->assertEquals('string', $parent->getValue());
    }

    public function testSetValue(): void
    {
        $parent = new Zend_Server_Reflection_Node('string');
        $this->assertEquals('string', $parent->getValue());
        $parent->setValue('array');
        $this->assertEquals('array', $parent->getValue());
    }

    public function testGetEndPoints(): void
    {
        $root = new Zend_Server_Reflection_Node('root');
        $child1 = $root->createChild('child1');
        $child2 = $root->createChild('child2');
        $child1grand1 = $child1->createChild(null);
        $child1grand2 = $child1->createChild('child1grand2');
        $child2grand1 = $child2->createChild('child2grand1');
        $child2grand2 = $child2->createChild('child2grand2');
        $child2grand2great1 = $child2grand2->createChild(null);
        $child2grand2great2 = $child2grand2->createChild('child2grand2great2');

        $endPoints = $root->getEndPoints();
        $endPointsArray = array();
        foreach ($endPoints as $endPoint) {
            $endPointsArray[] = $endPoint->getValue();
        }

        $test = array(
            'child1',
            'child1grand2',
            'child2grand1',
            'child2grand2',
            'child2grand2great2'
        );

        $this->assertTrue($test === $endPointsArray, 'Test was [' . var_export($test, 1) . ']; endPoints were [' . var_export($endPointsArray, 1) . ']');
    }
}
