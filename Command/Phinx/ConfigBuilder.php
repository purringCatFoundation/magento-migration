<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Command\Phinx;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Exception\LocalizedException;
use PCF\MagentoMigration\Api\ConfigBuilderInterface;
use PCF\MagentoMigration\Api\PathLocatorInterface;
use Symfony\Component\Yaml\Dumper;

class ConfigBuilder implements ConfigBuilderInterface
{
    /** @var DeploymentConfig */
    protected $deploymentConfig;

    /** @var PathLocatorInterface */
    protected $pathLocator;

    /** @var Dumper */
    protected $yamlDumper;

    protected $defaultDb = 'default';

    /**
     * @param DeploymentConfig $deploymentConfig
     * @param PathLocatorInterface $pathLocator
     * @param Dumper $yamlDumper
     */
    public function __construct(
        DeploymentConfig $deploymentConfig,
        PathLocatorInterface $pathLocator,
        Dumper $yamlDumper
    ) {
        $this->deploymentConfig = $deploymentConfig;
        $this->pathLocator = $pathLocator;
        $this->yamlDumper = $yamlDumper;
    }

    /**
     * @inheritdoc
     */
    public function createConfigPath() :string
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

    /**
     * @return array
     */
    protected function getConfigArray(): array
    {
        return [
            'paths' => [ 'migrations' => $this->pathLocator->getAllMigrationDirs()],
            'environments' => $this->getEnvironments()
        ];
    }

    /**
     * @throws LocalizedException
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
                'port' => empty($config['port']) ? 3306 : $config['port'],
                'charset' => 'utf8'
            ];
        }

        if (empty($magentoConfig['connection']['default'])) {
            $this->setDefaultDb(key($magentoConfig['connection']));
        }

        return $connections;
    }

    /**
     * @return array
     */
    protected function getEnvironments() : array
    {
        $return = [
              'default_migration_table' => 'phinxlog',
              'default_database' => $this->defaultDb,
        ];

        return array_merge($return, $this->getDbConfig());
    }
}
