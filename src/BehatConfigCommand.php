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
    // $this->composerTimeout();
    $options = [
      $this->config['project']['path'] . "/vendor/bin/behat",
      "--init",
    ];
    $process = new Process($options, $this->config['project']['path']);
    $process->setTimeout($this->composerConfig['timeout']);
    $process->run(function ($type, $buffer) {
      echo $buffer;
    });
    // Replace behat.yml file.
    copy("config/behat.yml", $this->config['project']['path'] . "/behat.yml");
    // Replace values in behat.yml file.
    $config = Yaml::parseFile($this->config['project']['path'] . "/behat.yml");
    $wd_host = $this->config['behat']['selenium_url'];
    $base_url = $this->config['behat']['behat_url'];
    $drupal_root = $this->config['behat']['drupal_root'];
    $config['default']['extensions']['Drupal\MinkExtension']['selenium2']['wd_host'] = $wd_host;
    $config['default']['extensions']['Drupal\MinkExtension']['files_path'] = $drupal_root;
    $config['default']['extensions']['Drupal\MinkExtension']['base_url'] = $base_url;
    $config['default']['extensions']['Drupal\DrupalExtension']['drupal']['drupal_root'] = $drupal_root;
    $yaml = Yaml::dump($config, 8);
    file_put_contents($this->config['project']['path'] . "/behat.yml", $yaml);
  }

}
