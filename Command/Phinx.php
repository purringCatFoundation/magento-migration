<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PCF\MagentoMigration\Command\Phinx\CommandBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;

class Phinx extends Command
{
    const COMAND_CODE = 'pcf:migration';
    /** @var CommandBuilder */
    private $commandBuilder;

    /** @var Process */
    private $process;

    /**
     * Phinx constructor.
     * @param CommandBuilder $commandBuilder
     */
    public function __construct(CommandBuilder $commandBuilder, Process $process)
    {
        parent::__construct();
        $this->commandBuilder = $commandBuilder;
        $this->process = $process;
    }


    protected function configure()
    {
        $this->addArgument('input', InputArgument::IS_ARRAY, InputOption::VALUE_OPTIONAL);
        $this->setName(self::COMAND_CODE)->setDescription('Migration using phinx command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = implode(' ', $input->getArgument('input'));
        try {
            $execCommand = $this->commandBuilder->getExecCommand($command);
        } catch (\LogicException $e) {
            return $output->writeln($e->getMessage());
        }

        $this->process->setCommandLine($execCommand);
        $this->process->setTty(true);
        $this->process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        return 0;
    }
}
