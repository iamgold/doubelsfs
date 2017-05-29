<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use iamgold\doublesfs\storages\LocalStorage;

$config = [
    'sourcePath' => __DIR__ . '/source'
];

try {
    $localStorage = new LocalStorage($config);

    $filename = '123.txt';
    $localStorage->putContents('123123', $filename);

    $path = $localStorage->getRealPath($filename);

    $key = 'abc.txt';
    $localStorage->put($path, $key);

    $path = $localStorage->getRealPath($key);

    $key = '/' . date('Ymd') . '/123/3423/aasd/new_abc.txt';
    $localStorage->put($path, $key, ['deleteSource'=>true]);

    var_dump($localStorage);
} catch (Exception $e) {
    throw $e;
}
