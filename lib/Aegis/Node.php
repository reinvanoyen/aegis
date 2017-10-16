<?php

namespace Aegis;

/**
 * Class Node
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
abstract class Node
{
    /**
     * @var Node|null
     */
    private $parent = null;

    /**
     * @var Node[]
     */
    private $children = [];

    /**
     * @var Node[]
     */
    private $attributes = [];

    /**
     * @var bool
     */
    private $isAttribute = false;

    /**
     * Adds the given node as an attribute
     *
     * @param Node $node
     */
    public function setAttribute(Node $node)
    {
        $node->isAttribute = true;
        $this->attributes[] = $node;
    }

    /**
     * Return whether the node is an attribute
     *
     * @return bool
     */
    public function isAttribute()
    {
        return $this->isAttribute;
    }

    /**
     * Gets an attribute node
     *
     * @param int $index
     * @return Node|null
     */
    public function getAttribute(int $index) : ?Node
    {
        return isset($this->attributes[$index]) ? $this->attributes[$index] : null;
    }

    /**
     * Gets all attribute nodes
     *
     * @return Node[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Gets all child nodes
     *
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Gets the parent node
     *
     * @return Node
     * @throws AegisError
     */
    public function getParent()
    {
        if (!$this->parent) {
            throw new AegisError('Could not get parent node of node, because the node has no parent');
        }

        return $this->parent;
    }

    /**
     * Sets the parent node
     *
     * @param Node $parent
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Gets the child at the given index
     *
     * @param int $index
     * @return Node
     * @throws AegisError
     */
    public function getChild(int $index) : Node
    {
        if (!isset($this->children[$index])) {
            throw new AegisError('Could not get child from node, because there\'s no child at index '.$i);
        }

        return $this->children[$index];
    }

    /**
     * Gets the last child node
     *
     * @return Node
     * @throws AegisError
     */
    public function getLastChild() : Node
    {
        $last = end($this->children);

        if (!$last) {
            throw new AegisError('Could not get last child from node, because the node has no children');
        }

        return $last;
    }

    /**
     * Removes the child node at the given index
     *
     * @param int $index
     * @throws AegisError
     */
    public function removeChild(int $index)
    {
        if (!isset($this->children[$index])) {
            throw new AegisError('Could remove child from node, because there\'s no child at index '.$index);
        }

        unset($this->children[$index]);
    }

    /**
     * Removes the last child node
     *
     * @throws AegisError
     */
    public function removeLastChild()
    {
        $last = array_pop($this->children);

        if (!$last) {
            throw new AegisError('Could not remove last child from node, because the node has no children');
        }
    }

    /**
     * Gets all sibling nodes
     *
     * @return Node[]
     */
    public function getSiblings()
    {
        return $this->getParent()->getChildren();
    }

    /**
     * Adds a child node
     *
     * @param Node $node
     */
    public function insert(Node $node)
    {
        $this->children[] = $node;
    }

    /**
     * Gets the name of the node
     *
     * @return string
     */
    public function getName() : string
    {
        return get_class($this);
    }

    /**
     * @param CompilerInterface $compiler
     * @return mixed
     */
    abstract public function compile(CompilerInterface $compiler);
}
