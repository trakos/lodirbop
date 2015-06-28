<?php

$loader = require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);