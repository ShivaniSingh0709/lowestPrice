<?php
namespace Pixelmechanics\LowestPrice\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoreLowestPrice extends Command
{
   
   protected $_lowestPriceImportRun;

   public function __construct(
        \Pixelmechanics\LowestPrice\Cron\Lowest $lowestPriceImportRun
   ){
        $this->_lowestPriceImportRun = $lowestPriceImportRun;
        parent::__construct();
   }
    protected function configure()
   {
       $this->setName('pm:catalog:storelowestprice');
       $this->setDescription('CLI command to store the lowest price in the Flat Table');
       
       parent::configure();
   }
   protected function execute(InputInterface $input, OutputInterface $output)
   {
       $output->writeln("Process Started");
       $this->_lowestPriceImportRun->execute();
       $output->writeln("Process Done!");
   }
}
