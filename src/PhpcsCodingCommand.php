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
class PhpcsCodingCommand extends Command {

  /**
   * {@inheritdoc}
   */
  public function configure() {
    $this->setName('phpcs-config')
      ->setDescription('Configure phpcs.')
      ->setHelp('Configure phpcs...');
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
    //$this->composerTimeout();
    $options = [
      $this->projectPath . "/" . $this->config['project']['name'] . '/vendor/squizlabs/php_codesniffer/bin/phpcs',
      '--config-set',
      'installed-paths',
      $this->projectPath . "/" . $this->config['project']['name'] . '/vendor/drupal/coder/code_sniffer',
    ];
    echo implode(" ", $options);
    $process = new Process($options, $this->projectPath);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
    $options = [
      $this->projectPath . "/" . $this->config['project']['name'] . '/vendor/squizlabs/php_codesniffer/bin/phpcs',
      '-i',
    ];
    echo implode(" ", $options);
    $process = new Process($options, $this->projectPath);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
  }

}
