<?php 

namespace Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Author: Enrique Lacoma<enriquelacoma@gmail.com>
 */
class Command extends SymfonyCommand {

  public function __construct() {
    parent::__construct();
  }
  protected function runCommand(InputInterface $input, OutputInterface $output) {
    // outputs multiple lines to the console (adding "\n" at the end of each line)
    $output->writeln([
      '====**** User Greetings Console App ****====',
      '==========================================',
      '',
    ]);
  }

}