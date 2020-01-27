<?php

namespace Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Console\Command;

/**
 * Author: Enrique Lacoma<enriquelacoma@gmail.com>.
 */
class InstallDrupalCommand extends Command {

  /**
   * {@inheritdoc}
   */
  public function configure() {
    $this->setName('install-site')
      ->setDescription('Install drupal.')
      ->setHelp('Install drupal...');
  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->runCommand($input, $output);
  }

  /**
   * {@inheritdoc}
   */
  protected function runCommand(InputInterface $input, OutputInterface $output) {
    $options = [
      "composer",
      "create-project",
      "drupal-composer/drupal-project:8.x-dev",
      $this->config['project']['name'],
      "--no-interaction",
    ];
    // Add verbose options.
    if (1 == $this->composerConfig['verbose']) {
      $options[] = '-vvv';
    }
    $process = new Process($options, $this->projectPath);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
    $options = [
      'composer',
      'install',
    ];
    $process = new Process($options, $this->projectPath . "/" . $this->config['project']['name']);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
    $dbUser = $this->config['project']['mysql']['DB_USER'];
    $dbPass = $this->config['project']['mysql']['DB_PASSWORD'];
    $ipAddress = "mariadb";
    $dbName = $this->config['project']['mysql']['DB_NAME'];
    $options = [
      "drush",
      "si",
      "standard",
      "-vvv",
      "--db-url=mysql://$dbUser:$dbPass@$ipAddress/$dbName",
    ];
    $process = new Process($options, $this->projectPath . "/drupal");
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
    $options = [
      "drush",
      "upwd",
      "admin",
      "admin",
    ];
    $process = new Process($options, $this->projectPath . "/drupal");
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
  }

}
