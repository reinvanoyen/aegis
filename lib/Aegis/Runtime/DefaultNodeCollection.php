<?php

namespace Aegis\Runtime;

use Aegis\NodeCollection;

/**
 * Class DefaultNodeCollection
 * @package Aegis\Runtime
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class DefaultNodeCollection extends NodeCollection
{
    /**
     * DefaultNodeCollection constructor.
     */
    public function __construct()
    {
        $this->add([
            'Aegis\\Runtime\\Node\\AssignmentNode',
            'Aegis\\Runtime\\Node\\IfNode',
            'Aegis\\Runtime\\Node\\ForNode',
            'Aegis\\Runtime\\Node\\BlockNode',
            'Aegis\\Runtime\\Node\\ExtendNode',
            'Aegis\\Runtime\\Node\\IncludeNode',
            'Aegis\\Runtime\\Node\\PrintNode',
            'Aegis\\Runtime\\Node\\RawNode',
            'Aegis\\Runtime\\Node\\PhpNode',
        ]);
    }
}
