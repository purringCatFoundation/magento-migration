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
    public function getPhinxBinaryPath() :string;

    /**
     * @return string
     */
    public function getVendorPath() :string;

    /**
     * @return array
     */
    public function getAllMigrationDirs() :array;
}
