<?php
use Cake\Routing\Router;

Router::plugin('Taxonomy', function($routes) {
	$routes->fallbacks();
});
