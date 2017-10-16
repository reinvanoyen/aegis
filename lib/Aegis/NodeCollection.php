<?php

namespace Aegis;

/**
 * Class NodeCollection
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class NodeCollection implements NodeCollectionInterface
{
    /**
     * @var array
     */
    private $nodes = [];

    /**
     * @param $mixed
     */
    public function add($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $classname) {
                $this->add($classname);
            }
        } elseif (is_string($mixed)) {
            $this->nodes[] = $mixed;
        } else {
            throw new \InvalidArgumentException('Argument should be of type array or string');
        }
    }

    /**
     * @param ParserInterface $parser
     */
    public function parse(ParserInterface $parser)
    {
        foreach ($this->nodes as $node) {
            $node::parse($parser);
        }
    }
}
