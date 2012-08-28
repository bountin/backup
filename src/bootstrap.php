<?php

$autoloader = __DIR__.'/../vendor/autoload.php';

if (file_exists($autoloader)) {
	$autoloader = include $autoloader;
} else {
	// Taken from the composer project
	die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
		'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
		'php composer.phar install'.PHP_EOL);
}
