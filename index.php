<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Template/Template.php';

class Page
{
	public $title = 'Home';
	public $inc = 'side';
	public $count = 10;
}

$tpl = new Template();
$tpl->page = new Page();
$tpl->inc = 'side';
$tpl->render( 'default.tpl' );
