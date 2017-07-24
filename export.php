<?php

require 'src/Fixtures/AbstractProvider.php';
require 'src/Fixtures/Crawlers.php';
require 'src/Fixtures/Exclusions.php';
require 'src/Fixtures/Headers.php';

$src = [
	'Crawlers',
	'Exclusions',
	'Headers',
];

foreach($src as $class) {
	$class = "Jaybizzle\\CrawlerDetect\\Fixtures\\$class";
	$object = new $class;

	outputJson($object);
	outputTxt($object);
}

function outputJson($object) {
	$className = (new ReflectionClass($object))->getShortName();
	file_put_contents("raw/$className.json", json_encode($object->getAll()));
}

function outputTxt($object) {
	$className = (new ReflectionClass($object))->getShortName();
	file_put_contents("raw/$className.txt", implode($object->getAll(), PHP_EOL));
}