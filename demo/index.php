<?php

ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$nodes = new \Aegis\NodeCollection\NodeCollection();

$nodes->register([
    \Aegis\Node\ComponentNode::class,
    \Aegis\Node\SlotNode::class,
    \Aegis\Node\AssignmentNode::class,
    \Aegis\Node\IfNode::class,
    \Aegis\Node\ForNode::class,
    \Aegis\Node\ExtendNode::class,
    \Aegis\Node\BlockNode::class,
    \Aegis\Node\IncludeNode::class,
    \Aegis\Node\PrintNode::class,
    \Aegis\Node\RawNode::class,
    \Aegis\Node\PhpNode::class,
]);

$tpl = new \Aegis\Template(
    new \Aegis\Config\PhpFileConfig(__DIR__ . '/config.php'),
    new \Aegis\Filesystem\Filesystem(),
    new \Aegis\Engine\Engine(
        new \Aegis\Lexer\Lexer(),
        new \Aegis\Parser\Parser($nodes),
        new \Aegis\Compiler\Compiler()
    ),
    new \Aegis\Runtime\Runtime()
);

$tpl->set('title', 'Your website');

echo $tpl->render('index');
