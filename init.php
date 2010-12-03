<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Route /scripts/ to Assets controller.
 */
Route::set('scripts', 'scripts/<group>.js', array('group' => '[a-zA-Z0-9._-]+'))
	->defaults(array(
		'controller' => 'assets',
		'action'     => 'script',
	));

/**
 * Route /styles/ to Assets controller.
 */
Route::set('styles', 'styles/<group>.css', array('group' => '[a-zA-Z0-9._-]+'))
	->defaults(array(
		'controller' => 'assets',
		'action'     => 'style',
	));
