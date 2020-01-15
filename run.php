#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Console\InstallDevCommand;
use Console\PhpcsCodingCommand;
use Console\InstallDrupalCommand;

$app = new Application('Console App', 'v1.0.0');
$app->add(new InstallDevCommand());
$app->add(new Phpcscodingcommand());
$app->add(new InstallDrupalCommand());
$app->run();
