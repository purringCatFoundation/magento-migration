<?php

namespace PCF\MagentoMigration\Command\Phinx;

use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Module\ModuleList;
use PCF\MagentoMigration\Api\PathLocatorInterface;

class PathLocator implements PathLocatorInterface
{
    /** @var Reader */
    private $dirReader;

    /** @var DirectoryList */
    private $directoryList;

    /** @var ModuleList */
    private $moduleList;

    /**
     * @param Reader $dirReader
     * @param DirectoryList $directoryList
     * @param ModuleList $moduleList
     */
    public function __construct(Reader $dirReader, DirectoryList $directoryList, ModuleList $moduleList)
    {
        $this->dirReader = $dirReader;
        $this->directoryList = $directoryList;
        $this->moduleList = $moduleList;
    }

    /**
     * @inheritdoc
     */
    public function getPhinxBinaryPath()
    {
        return $this->getVendorPath() . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phinx';
    }

    /**
     * @inheritdoc
     */
    public function getVendorPath()
    {
        return $this->directoryList->getRoot() . DIRECTORY_SEPARATOR . self::VENDOR_DIR_NAME;
    }

    /**
     * @inheritdoc
     */
    public function getAllMigrationDirs()
    {
        $modules = $this->moduleList->getAll();

        return $this->getAllExistingMigrationsPaths($modules);
    }

    /**
     * @param array $modulesList
     * @return array
     */
    protected function getAllExistingMigrationsPaths(array $modulesList)
    {
        $modulesPaths = [];
        foreach ($modulesList as $moduleName => $moduleMeta) {
            $path = $this->dirReader->getModuleDir( '', $moduleName) . DIRECTORY_SEPARATOR . self::MIGRATION_DIR_NAME;
            if (file_exists($path)) {
                $modulesPaths[$moduleName] = $path;
            }
        }

        return $modulesPaths;
    }
}