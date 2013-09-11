<?php

/**
 * Autoload function adapted from from PSR-0 standard.
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#splclassloader-implementation
 */
function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', '/', $namespace) . '/';
    }
    $fileName = '../src/' . $fileName;
    $fileName .= str_replace('_', '/', $className) . '.php';

    require $fileName;
}

spl_autoload_register('autoload');
