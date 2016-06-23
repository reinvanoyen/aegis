<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Template/Renderer.php';

class Page
{
	public $title = 'Home';
	public $block = 'side';
}

$tpl = new Renderer();
$tpl->page = new Page();
$tpl->render( 'default2.tpl' );