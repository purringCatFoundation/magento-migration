<?php

namespace PCF\MagentoMigration\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PCF\MagentoMigration\Command\Phinx\CommandBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;

class Phinx extends Command
{
    /** @var CommandBuilder */
    private $commandBuilder;

    /**
     * Phinx constructor.
     * @param CommandBuilder $commandBuilder
     */
    public function __construct(CommandBuilder $commandBuilder)
    {
        parent::__construct();
        $this->commandBuilder = $commandBuilder;
    }


    protected function configure()
    {
        $this->addArgument('input', InputArgument::IS_ARRAY, InputOption::VALUE_OPTIONAL);
        $this->setName('pcf:migration')->setDescription('Micration using phinx command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * todo format output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = implode(' ', $input->getArgument('input'));
        $return = $this->commandBuilder->phinx($command);
        $error = $return->getError();
        if (!empty($error)) {
            $output->writeln($error);
        }

        $output->writeln($return->getOutput());
    }
}