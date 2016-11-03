<?php

use Aegis\Node;

class NodeTest extends PHPUnit_Framework_TestCase
{
    public function testGetAndSetAttribute()
    {
        $node = $this->getMockForAbstractClass(Node::class);
        $attr = $this->getMockForAbstractClass(Node::class);
        $node->setAttribute($attr);

        $this->assertCount(1, $node->getAttributes());
    }

    public function testIsAttributeShouldReturnTrue()
    {
        $node = $this->getMockForAbstractClass(Node::class);
        $attr = $this->getMockForAbstractClass(Node::class);
        $node->setAttribute($attr);

        $this->assertEquals(true, $attr->isAttribute());
    }

    public function testIsAttributeShouldReturnFalse()
    {
        $attr = $this->getMockForAbstractClass(Node::class);

        $this->assertEquals(false, $attr->isAttribute());
    }

    public function testGetAttributeShouldReturnNull()
    {
        $node = $this->getMockForAbstractClass(Node::class);

        $this->assertNull($node->getAttribute(0));
    }
}
