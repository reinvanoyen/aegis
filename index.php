<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Template/Template.php';

class Page
{
	public $title = 'Home';
	public $inc = 'main';
	public $count = 10;
}

$tpl = new Template();
$tpl->page = new Page();
$tpl->render( 'default.tpl' );