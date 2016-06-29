<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require 'autoload.php';

// Your playground

$tpl = new \Aegis\Template();
$tpl->title = 'test';
$tpl->render( 'test' );