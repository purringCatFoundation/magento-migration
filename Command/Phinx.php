<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\{InputArgument, InputOption};
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

    /** @var LoggerInterface */
    private $logger;

    /**
     * Phinx constructor.
     * @param CommandBuilder $commandBuilder
     * @param Process $process
     * @param LoggerInterface $logger
     */
    public function __construct(CommandBuilder $commandBuilder, Process $process, LoggerInterface $logger)
    {
        parent::__construct();
        $this->commandBuilder = $commandBuilder;
        $this->process = $process;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this->addArgument('input', InputArgument::IS_ARRAY, 'Input command, same use as Phinx');
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
        try {
            $this->process->run(function ($type, $buffer) use ($output) {
                $output->write($buffer);
            });
        } catch (\RuntimeException $e) {
            $this->logger->error($e, ['command' => $command]);
            $output->writeln("Error occured, for more info check logs");
        } catch (\LogicException $e) {
            $this->logger->error($e, ['command' => $command]);
            $output->writeln($e->getMessage());
        }

        return 0;
    }
}
