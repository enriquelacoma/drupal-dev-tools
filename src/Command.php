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
    $config = Yaml::parseFile(__DIR__ . '/../config/config.yml');
    $this->config = $config;
    $this->composerConfig = $config['composer']['config'];
    $this->config['project']['path'] = $config['project']['install_path'] . "/" . $config['project']['project_name'];
    $this->config['behat']['drupal_root'] = $this->config['project']['path'] . "/web";
    foreach ($config['composer']['dependencies'] as $key => $value) {
      if (1 == $value['install']) {
        $this->dependencies[$key] = $value;
      }
    }
  }

  protected function composerTimeout() {
    if ($this->config['composer']['config']['timeout'] > 0) {
      $options = [
        "composer",
        "config",
        "process-timeout",
        $this->composerConfig['timeout'],
      ];
      set_time_limit(100);
      // Add verbose options.
      if (1 == $this->composerConfig['verbose']) {
        $options[] = '-vvv';
      }
      $process = new Process($options, $this->config['project']['path']);
      $process->setTimeout($this->composerConfig['timeout']);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
  }
}
