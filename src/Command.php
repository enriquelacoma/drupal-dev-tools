<?php

namespace Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;

/**
 * Author: Enrique Lacoma<enriquelacoma@gmail.com>.
 */
class Command extends SymfonyCommand {
  /**
   * Modules to install.
   *
   * @var array
   */
  protected $dependencies = [];
  /**
   * Composer config.
   *
   * @var array
   */
  protected $composerConfig = [];
  /**
   * Project to udpate.
   *
   * @var string
   */
  protected $projectPath = '';
  protected $config = [];

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->getConfig();
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function runCommand(InputInterface $input, OutputInterface $output) {
  }

  /**
   * Get modules to install.
   */
  protected function getConfig() {
    $config = Yaml::parseFile('config.yml');
    $this->config = $config;
    $this->composerConfig = $config['composer']['config'];
    $this->projectPath = $config['project']['path'];
    foreach ($config['composer']['dependencies'] as $key => $value) {
      if (1 == $value['install']) {
        $this->dependencies[$key] = $value;
      }
    }
  }

  protected function composerTimeout() {
    if ($this->composerConfig > 0) {
      $options = [
        "composer",
        "config",
        "--global",
        "process-timeout",
        $this->composerConfig['timeout']
      ];
      // Add verbose options.
      if (1 == $this->composerConfig['verbose']) {
        $options[] = '-vvv';
      }
      $process = new Process($options, $this->projectPath);
      $process->run(function ($type, $buffer) {
        echo "---" . $buffer;
      });
    }
  }

}
