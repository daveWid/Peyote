<?php

// Load up clases needed for the tests
foreach (glob("tests/classes/*.php") as $filename)
{
    include $filename;
}

// Autoloading
$base = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR;

$loader = new SplClassLoader;
$loader->setIncludePath($base);
$loader->register();
