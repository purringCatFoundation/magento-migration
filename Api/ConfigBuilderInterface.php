<?php

declare(strict_types = 1);

namespace PCF\MagentoMigration\Api;

interface ConfigBuilderInterface
{

    /**
     * @return string
     */
    public function createConfigPath();
}
