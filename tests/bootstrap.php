<?php

/**
 * This file is part of AT Common package.
 *
 * (c) 2014-2014 thehongtt@gmail.com
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$locations[] = __DIR__ . "/../vendor/autoload.php";
$locations[] = __DIR__ . "/../../../autoload.php";

foreach ($locations as $location) {
    if (is_file($location)) {
        $loader = require $location;
        $loader->addPsr4('AndyTruong\\Common\\TestCases\\', __DIR__.'/common');
        $loader->addPsr4('AndyTruong\\Common\\Fixtures\\', __DIR__.'/fixtures');
        break;
    }
}
