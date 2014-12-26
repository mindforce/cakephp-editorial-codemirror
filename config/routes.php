<?php
use Cake\Routing\Router;

Router::plugin('Editorial/Codemirror', function ($routes) {
	$routes->fallbacks();
});
