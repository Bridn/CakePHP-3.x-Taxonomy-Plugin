<?php
/**
 * Test suite bootstrap for Taxonomy.
 */
// Customize this to be a relative path for embedded plugins.
// For standalone plugins, this should point at a CakePHP installation.
require dirname(__DIR__) . '/vendor/cakephp/cakephp/src/basics.php';
require dirname(__DIR__) . '/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('APP', sys_get_temp_dir());
define('ROOT', dirname(__DIR__));
Cake\Core\Configure::write('App', [
	'namespace' => 'App'
]);
