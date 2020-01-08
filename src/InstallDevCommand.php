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
 * Author: Enrique Lacoma<enriquelacoma@gmail.com>
 */
class InstallDevCommand extends Command {
  protected $dependencies = [];
  protected $composerConfig = [];
  protected $projectPath = '';
  public function configure() {
    $this->getDependencies();
    $this->getProjectPath();
    $this->getComposerConfig();
    $this->setName('install-dev')
      ->setDescription('Install dev.')
      ->setHelp('Install dev...');
      //->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
  }
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->runCommand($input, $output);
    $this->postScripts();
    // outputs multiple lines to the console (adding "\n" at the end of each line)
    /*
    $output->writeln([
      '====**** User Greetings Console App ****====',
      '==========================================',
      '',
    ]);
    */
  }
  protected function runCommand(InputInterface $input, OutputInterface $output) {
    // Configure composer timeout.
    if ($this->composerConfig > 0) {
      $options = ["composer", "config", "--global", "process-timeout", $this->composerConfig['timeout']];
      // Add verbose options.
      if (1 == $this->composerConfig['verbose']) {
        $options[] = '-vvv';
      }
      $process = new Process($options, $this->projectPath);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
    // Add tools with composer.
    foreach ($this->dependencies as $key => $value) {
      $options = ['composer', 'require', $key];
      // Add dev switch.
      if (1 == $value['require-dev']) {
        $options[] = '--dev';
      }
      // Add verbose options.
      if (1 == $this->composerConfig['verbose']) {
        $options[] = '-vvv';
      }
      $process = new Process($options, $this->projectPath);
      $process->run(function ($type, $buffer) {
        echo $buffer;
      });
    }
    $options = ['composer', 'install'];
    if (1 == $this->composerConfig['verbose']) {
      $options[] = '-vvv';
    }
    $process = new Process($options, $this->projectPath);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
  }
  protected function getDependencies() {
    $value = Yaml::parseFile('config.yml');
    foreach ($value['composer']['dependencies'] as $key => $value) {
      if (1 == $value['install']) {
        $this->dependencies[$key] = $value;
      }
    }
  }
  protected function getComposerConfig() {
    $value = Yaml::parseFile('config.yml');
    $this->composerConfig = $value['composer']['config'];
  }
  protected function getProjectPath() {
    $value = Yaml::parseFile('config.yml');
    $this->projectPath = $value['project']['path'];
  }
  protected function postScripts() {
    //phpcs --config-set installed_paths ~/.composer/vendor/drupal/coder/coder_sniffer
    //$ phpcs --config-set installed_paths ~/workspace/coder/coder_sniffer
  }
}