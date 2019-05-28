<?php

namespace Aegis\NodeCollection;

use Aegis\Contracts\NodeCollectionInterface;

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
    public function register($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $classname) {
                $this->register($classname);
            }
        } elseif (is_string($mixed)) {
            $this->nodes[] = $mixed;
        } else {
            throw new \InvalidArgumentException('Argument should be of type array or string');
        }
    }

    public function getAll(): array
    {
        return $this->nodes;
    }
}
