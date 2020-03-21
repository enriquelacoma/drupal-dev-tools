#!/usr/bin/env php
<?php

if (file_exists(__DIR__ . '/../../autoload.php')) {
  // Global vendor
  $autoloaderPath = __DIR__ . '/../../autoload.php';
} elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
  // Local vendor.
  $autoloaderPath = __DIR__ . '/vendor/autoload.php';
} else {
  die("Could not find autoloader. Run 'composer install'.");
}
require $autoloaderPath;p';

use Symfony\Component\Console\Application;
use Console\InstallDevCommand;
use Console\PhpcsCodingCommand;
use Console\InstallDrupalCommand;
use Console\BehatConfigCommand;

$app = new Application('Console App', 'v1.0.0');
$app->add(new InstallDevCommand());
$app->add(new Phpcscodingcommand());
$app->add(new InstallDrupalCommand());
$app->add(new BehatConfigCommand());
$app->run();
