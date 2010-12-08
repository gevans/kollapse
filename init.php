<?php defined('SYSPATH') or die('No direct script access.');

Route::set('kollapse', 'kollapse')
	->defaults(array(
		'controller' => 'kollapse',
		'action'     => 'index',
	));

Route::set('assets', '<action>/(<group>)', array('action' => '(scripts|styles)'))
	->defaults(array(
		'controller' => 'kollapse',
	));
