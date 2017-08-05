<?php

namespace PCf\MagentoMigration\Test\Unit\Command\Phinx;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use mikehaertl\shellcommand\Command;
use PCF\MagentoMigration\Api\PathLocatorInterface;
use PCF\MagentoMigration\Api\ConfigBuilderInterface;
use PCF\MagentoMigration\Command\Phinx\CommandBuilder;

class CommandBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $pathLocator;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $configBuilder;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $command;

    /** @var CommandBuilder */
    private $testedObject;

    protected function setUp()
    {
        $this->pathLocator = $this->getMockBuilder(PathLocatorInterface::class)->getMock();
        $this->configBuilder = $this->getMockBuilder(ConfigBuilderInterface::class)->getMock();
        $this->command = $this->getMockBuilder(Command::class)->disableOriginalConstructor()->getMock();

        $this->testedObject = new CommandBuilder($this->pathLocator, $this->configBuilder, $this->command);


    public function testCanBeInstantiated()
    {
        $objectManager = new ObjectManager($this);
        $testedInstance = $objectManager->getObject(CommandBuilder::class);
        $this->assertInstanceOf(CommandBuilder::class, $testedInstance);
    }

    public function testGetExecCommand()
    {
        $bin = 'put your garbage here';
        $path = 'walk through the valey';
        $method = 'is the key to success';
        $this->pathLocator->expects($this->once())->method('getPhinxBinaryPath')->willReturn($bin);
        $this->command->expects($this->once())->method('setCommand')->with($bin);
        $this->configBuilder->expects($this->once())->method('getConfigPath')->willReturn($path);
        $this->command->expects($this->exactly(2))->method('addArg')
            ->withConsecutive(['--configuration=',  $path], [$method]);

        $this->testedObject->getExecCommand($method);
    }

}
