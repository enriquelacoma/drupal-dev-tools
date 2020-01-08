<?php

namespace Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Author: Enrique Lacoma<enriquelacoma@gmail.com>.
 */
class Command extends SymfonyCommand {

  /**
   * Project to udpate.
   *
   * @var string
   */
  protected $projectPath = '';

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function runCommand(InputInterface $input, OutputInterface $output) {
  }

  /**
   * Get project path to configure.
   */
  protected function getProjectPath() {
    $value = Yaml::parseFile('config.yml');
    $this->projectPath = $value['project']['path'];
  }


}
