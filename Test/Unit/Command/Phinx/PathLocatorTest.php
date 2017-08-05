<?php

namespace PCf\MagentoMigration\Test\Unit\Command\Phinx;

use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PCF\MagentoMigration\Command\Phinx\PathLocator;


class PathLocatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $dirReader;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $directoryList;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $moduleList;

    /** @var PathLocator */
    private $testedObject;

    protected function setUp()
    {
        $this->dirReader = $this->getMockBuilder(Reader::class)->disableOriginalConstructor()->getMock();
        $this->directoryList = $this->getMockBuilder(DirectoryList::class)->disableOriginalConstructor()->getMock();
        $this->moduleList= $this->getMockBuilder(ModuleList::class)->disableOriginalConstructor()->getMock();

        $this->testedObject = new PathLocator($this->dirReader,$this->directoryList, $this->moduleList);
    }



    public function testCanBeInstantiated()
    {
        $objectManager = new ObjectManager($this);
        $testedInstance = $objectManager->getObject(PathLocator::class);
        $this->assertInstanceOf(PathLocator::class, $testedInstance);
    }

    public function testGetAllMigrationDirs()
    {
        $tmpDir = sys_get_temp_dir();
        mkdir($tmpDir . '/path/Migrations', 0777, true);
        $this->moduleList->expects($this->once())->method('getAll')->willReturn(['name' => 'meta']);
        $this->dirReader->expects($this->once())->method('getModuleDir')->with('', 'name')->willReturn($tmpDir . '/path');

        $this->assertSame(['name' => $tmpDir . '/path/Migrations'], $this->testedObject->getAllMigrationDirs());
        rmdir($tmpDir . '/path/Migrations');
    }
}
