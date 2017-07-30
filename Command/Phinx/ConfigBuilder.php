<?php

namespace PCF\MagentoMigration\Command\Phinx;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Exception\LocalizedException;
use PCF\MagentoMigration\Api\ConfigBuilderInterface;
use Symfony\Component\Yaml\Dumper;

class ConfigBuilder implements ConfigBuilderInterface
{
    /** @var DeploymentConfig */
    private $deploymentConfig;

    /** @var PathLocator */
    private $pathLocator;

    /** @var Dumper */
    private $yamlDumper;

    private $defaultDb = 'default';

    /**
     * @param DeploymentConfig $deploymentConfig
     * @param PathLocator $pathLocator
     * @param Dumper $yamlDumper
     */
    public function __construct(DeploymentConfig $deploymentConfig, PathLocator $pathLocator, Dumper $yamlDumper)
    {
        $this->deploymentConfig = $deploymentConfig;
        $this->pathLocator = $pathLocator;
        $this->yamlDumper = $yamlDumper;
    }

    /**
     * @inheritdoc
     */
    public function getConfigPath()
    {
        $yml = $this->yamlDumper->dump($this->getConfigArray(), 5);
        $tmpFileName = tempnam(sys_get_temp_dir(), md5($yml));
        file_put_contents($tmpFileName, $yml);

        return $tmpFileName;
    }

    /**
     * @param string $defaultDb
     */
    protected function setDefaultDb(string $defaultDb)
    {
        $this->defaultDb = $defaultDb;
    }

    protected function getConfigArray()
    {
        return [
            'paths' => [ 'migrations' => $this->pathLocator->getAllMigrationDirs()],
            'environments' => $this->getEnvironments()
        ];
    }

    /**
     * @throws LocalizedException
     * todo: make port and charset configurable
     */
    protected function getDbConfig()
    {
         $magentoConfig = $this->deploymentConfig->getConfigData(ConfigOptionsListConstants::CONFIG_PATH_DB);

        if (empty($magentoConfig['connection'])) {
            throw new LocalizedException(__('no db connection given'));
        }

        $connections = [];
        foreach ($magentoConfig['connection'] as $envName => $config) {
            $connections[$envName] = [
                'adapter' => 'mysql',
                'host' => $config['host'],
                'name' => $config['dbname'],
                'user' => $config['username'],
                'pass' => $config['password'],
                'port' => 3306,
                'charset' => 'utf8'
            ];
        }

        if (empty($magentoConfig['connection']['default'])) {
            $this->setDefaultDb(key($magentoConfig['connection']));
        }

        return $connections;
    }

    protected function getEnvironments()
    {
        $return = [
              'default_migration_table' => 'phinxlog',
              'default_database' => $this->defaultDb,
        ];

        return array_merge($return, $this->getDbConfig());
    }
}