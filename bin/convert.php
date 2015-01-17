#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__ . '/../vendor/autoload.php';

$finder = new \CSD\PhpdocToReturn\FunctionFinder();
$finder->setRoot(realpath(__DIR__ . '/../src'));


$converter = new \CSD\PhpdocToReturn\Converter();


foreach ($finder->getFiles() as $file) {
    foreach ($file->getFunctions() as $function) {
        $converter->convert($function);
    }

    $file->write();


    /*var_dump(array_keys($file->getFunctions()));

    $file->insertToken('test', 4);

    var_dump(array_keys($file->getFunctions()));
*/
    // $file->write();

    //var_dump($function->getReflection()->getName() . ' (' . $function->getReflection()->getStartLine() . ')');
    //var_dump($function->getReturnType());


}
