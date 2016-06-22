<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Template/Renderer.php';

class Page
{
	public $title = 'Blog';
	public $subtitle = 'This is a nice blog';
	public $template = 'blog';
	public $count = 5;
}

$tpl = new Renderer();
$tpl->page = new Page();
$tpl->render( 'example.tpl' );