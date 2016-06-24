<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Template/Template.php';

class Page
{
	public $title = 'Home';
	public $inc = 'main';
	public $count = 10;
	public $basetpl = 'base';

	public function __construct( $title = 'Home', $inc = 'main', $count = 10 )
	{
		$this->title = $title;
		$this->inc = $inc;
		$this->count = 10;
	}
}

$tpl = new Template();
$tpl->page = new Page();
$tpl->pages = [
	new Page( 'Home' ),
	new Page( 'About us' ),
	new Page( 'Contact' ),
];
$tpl->sitename = 'Aegis';
$tpl->render( 'default.tpl' );