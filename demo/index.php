<?php

ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

\Aegis\Template::$templateDirectory = 'templates/';

$tpl = new \Aegis\Template(new \Aegis\Runtime\DefaultRuntime(new \Aegis\Runtime\DefaultNodeCollection()));
$tpl->setLexer(new \Aegis\Lexer());
$tpl->setParser(new \Aegis\Parser());
$tpl->setCompiler(new \Aegis\Compiler());

$tpl->title = 'This is a title';
$tpl->subtitle = 'This is a subtitle';
$tpl->five = 5;
$tpl->members = [ 'Rein', 'Wie', 'nog iemand' ];

$tpl->include_primary = false;
$tpl->include_secondary = 'include02';

echo $tpl->render('index');