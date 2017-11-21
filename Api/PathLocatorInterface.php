<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Api;

interface PathLocatorInterface
{
    const VENDOR_DIR_NAME = 'vendor';
    const MIGRATION_DIR_NAME = 'Migrations';

    /**
     * @return string
     */
    public function getPhinxBinaryPath();

    /**
     * @return string
     */
    public function getVendorPath();

    /**
     * @return array
     */
    public function getAllMigrationDirs();
}
