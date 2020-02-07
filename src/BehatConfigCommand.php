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
class BehatConfigCommand extends Command {

  /**
   * {@inheritdoc}
   */
  public function configure() {
    $this->setName('behat-config')
      ->setDescription('Configure behat.')
      ->setHelp('Configure behat...');
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
      $this->projectPath . "/" . $this->config['project']['name'] . "/vendor/bin/behat",
      "--init",
    ];
    $process = new Process($options, $this->projectPath . "/" . $this->config['project']['name']);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
  }

}
