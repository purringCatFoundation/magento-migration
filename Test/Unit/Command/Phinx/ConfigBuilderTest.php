<?php

declare(strict_types = 1);

namespace PCf\MagentoMigration\Test\Unit\Command\Phinx;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PCF\MagentoMigration\Api\ConfigBuilderInterface;
use Symfony\Component\Yaml\Dumper;
use PCF\MagentoMigration\Api\PathLocatorInterface;
use PCF\MagentoMigration\Command\Phinx\ConfigBuilder;

class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $deploymentConfig;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $pathLocator;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $yamlDumper;

    /** @var ConfigBuilder */
    private $testedObject;

    protected function setUp()
    {
        $this->deploymentConfig = $this->getMockBuilder(DeploymentConfig::class)
            ->disableOriginalConstructor()->getMock();

        $this->pathLocator = $this->getMockBuilder(PathLocatorInterface::class)->getMock();
        $this->yamlDumper = $this->getMockBuilder(Dumper::class)->disableOriginalConstructor()->getMock();

        $this->testedObject = new ConfigBuilder($this->deploymentConfig, $this->pathLocator, $this->yamlDumper);
    }

    public function testCanBeInstantiated()
    {
        $objectManager = new ObjectManager($this);
        $testedInstance = $objectManager->getObject(ConfigBuilder::class);
        $this->assertInstanceOf(ConfigBuilder::class, $testedInstance);
    }

    public function testGetConfigPath()
    {
        $confArray = [
            'paths' => ['migrations' => ['module' => 'path']],
            'environments' => [
                'default_migration_table' => 'phinxlog',
                'default_database' => 'default',
                'default' => [
                    'adapter' => 'mysql',
                    'host' => 'host',
                    'user' => 'username',
                    'pass' => 'password',
                    'port' => 3306,
                    'name' => 'dbname',
                    'charset' => 'utf8'
                ]
            ]
        ];
        $this->pathLocator->expects($this->once())->method('getAllMigrationDirs')
            ->willReturn(['module' => 'path']);

        $this->deploymentConfig->expects($this->once())->method('getConfigData')
            ->willReturn(['connection' => ['default' =>[
                'host' => 'host',
                'dbname' =>'dbname',
                'username' => 'username',
                'password' => 'password',
            ]]]);

        $this->yamlDumper->expects($this->once())->method('dump')
            ->with($confArray)->willReturn('some yml');

        $this->testedObject->createConfigPath();
    }
}
