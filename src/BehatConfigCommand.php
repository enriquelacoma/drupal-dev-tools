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
    // Replace behat.yml file.
    copy("config/behat.yml", $this->projectPath . "/" . $this->config['project']['name'] . "/behat.yml");
    // Replace values in behat.yml file.
    $config = Yaml::parseFile($this->projectPath . "/" . $this->config['project']['name'] . "/behat.yml");
    $wd_host ='http://selenium:4444/wd/hub';
    $files_path = $this->projectPath . "/" . $this->config['project']['name'] . "/web";
    $base_url = "http://php/drupal/web";
    $drupal_root = $this->projectPath . "/" . $this->config['project']['name'] . "/web";
    $config['default']['extensions']['Drupal\MinkExtension']['selenium2']['wd_host'] = $wd_host;
    $config['default']['extensions']['Drupal\MinkExtension']['files_path'] = $files_path;
    $config['default']['extensions']['Drupal\MinkExtension']['base_url'] = $base_url;
    $config['default']['extensions']['Drupal\DrupalExtension']['drupal']['drupal_root'] = $drupal_root;
    $yaml = Yaml::dump($config, 8);
    file_put_contents($this->projectPath . "/" . $this->config['project']['name'] . "/behat.yml", $yaml);
  }

}
