<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require 'autoload.php';

\Aegis\Template::$debug = TRUE;

$tpl = new \Aegis\Template();

$tpl->title = 'My testje';

echo $tpl->render( 'example/index' );
