<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Command\Phinx;

use mikehaertl\shellcommand\Command;
use PCF\MagentoMigration\Api\PathLocatorInterface;
use PCF\MagentoMigration\Api\ConfigBuilderInterface;

class CommandBuilder
{

    /** @var PathLocatorInterface */
    protected $pathLocator;

    /** @var ConfigBuilderInterface */
    protected $configBuilder;

    /** @var Command */
    protected $command;

    /**
     * Phinx constructor.
     * @param PathLocatorInterface $pathLocator
     * @param ConfigBuilderInterface $configBuilder
     * @param Command $command
     */
    public function __construct(
        PathLocatorInterface $pathLocator,
        ConfigBuilderInterface $configBuilder,
        Command $command
    ) {
        $this->pathLocator = $pathLocator;
        $this->configBuilder = $configBuilder;
        $this->command = $command;
    }


    public function getExecCommand($method = '')
    {
        $this->command->useExec = true;
        $this->command->setCommand($this->pathLocator->getPhinxBinaryPath());
        $this->command->addArg('--configuration=', $this->configBuilder->getConfigPath());
        if (!empty($method)) {
            $this->command->addArg($method);
        }

        return $this->command->getExecCommand();
    }
}
