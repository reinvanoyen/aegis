<?php

namespace Aegis;

abstract class Node
{
    private $parent = null;
    private $children = [];
    private $attributes = [];
    private $isAttribute = false;

    public function setAttribute(Node $n)
    {
        $n->isAttribute = true;
        $this->attributes[] = $n;
    }

    public function isAttribute()
    {
        return $this->isAttribute;
    }

    public function getAttribute($i)
    {
        return isset($this->attributes[ $i ]) ? $this->attributes[ $i ] : null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
        // @TODO should throw exception when there's no parent
        return $this->parent;
    }

    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    public function getChild($i)
    {
        // @TODO should throw exception when no child is present at the index
        return $this->children[ $i ];
    }

    public function getLastChild()
    {
        // @TODO should throw exception when no child is at the end

        return end($this->children);
    }

    public function removeChild($i)
    {
        // @TODO should throw exception when no child is at the index

        unset($this->children[ $i ]);
    }

    public function removeLastChild()
    {
        // @TODO should throw exception when no child is there to pop

        array_pop($this->children);
    }

    public function getSiblings()
    {
        return $this->getParent()->children;
    }

    public function insert(Node $node)
    {
        $this->children[] = $node;
    }

    public function getName()
    {
        return get_class($this);
    }

    abstract public function compile(CompilerInterface $compiler);
}
