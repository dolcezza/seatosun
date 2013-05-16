<?php
/*
	Plugin Name: WPThumbFx
	Plugin URI: http://demo.wpthemers.net/wpthumbfx/
	Description: Responsive jQuery & HTML5 Thumbnail Effects for WordPress.
	Version: 1.02
	Author: WP Themers
	Author URI: http://www.wpthemers.net
*/

// check compatibility
if (version_compare(PHP_VERSION, '5.2.4', '>=')) {

	// load class
	require_once(dirname(__FILE__).'/classes/atomicpress.php');

	// get instance and init system
	$atomicpress = AtomicPress::getInstance();
	$atomicpress['system']->init();

}