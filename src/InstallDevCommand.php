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
class InstallDevCommand extends Command {
  /**
   * {@inheritdoc}
   */
  public function configure() {
    $this->setName('install-dev')
      ->setDescription('Install dev.')
      ->setHelp('Install dev...');
    /*
    ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
     */
  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->runCommand($input, $output);
    $this->postScripts();
  }

  /**
   * {@inheritdoc}
   */
  protected function runCommand(InputInterface $input, OutputInterface $output) {
    // Configure composer timeout.
    // $this->composerTimeout();
    // Add tools with composer.
    foreach ($this->dependencies as $key => $value) {
      $options = ['composer'];
      if (isset($value['switches']) && count($value['switches'])) {
        $options = array_merge($options, $value['switches']);
      }
      $options = array_merge($options, ['require', $key]);
      // Add dev switch.
      if (1 == $value['require-dev']) {
        $options[] = '--dev';
      }
      // Add verbose options.
      if (1 == $this->composerConfig['verbose']) {
        $options[] = '-vvv';
      }
      $process = new Process($options, $this->projectPath . "/" . $this->config['project']['name']);
      $process->setTimeout($this->composerConfig['timeout']);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
    $options = ['composer', 'install'];
    if (1 == $this->composerConfig['verbose']) {
      $options[] = '-vvv';
    }
    $process = new Process($options, $this->projectPath);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
  }

  /**
   * Run commands after installing.
   */
  protected function postScripts() {
    foreach ($this->dependencies as $value) {
      $options = explode(" ", $value['post-command']);
      $process = new Process($options);
      $process->setTimeout($this->composerConfig['timeout']);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
  }

  /**
   * Run drush commands after installing.
   */
  protected function drushScripts() {
    foreach ($this->dependencies as $value) {
      $options = explode(" ", $value['drush-command']);
      $process = new Process($options);
      $process->setTimeout($this->composerConfig['timeout']);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
  }

}
