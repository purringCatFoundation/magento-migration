<?php

namespace PCF\MagentoMigration\Controller\Index;

use \Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;
use PCF\MagentoMigration\Command\Phinx\ConfigBuilder;

class Index extends Action
{
    public function execute()
    {
        $pathLocator = $this->_objectManager->get(\PCF\MagentoMigration\Command\Phinx::class);
        $x = $pathLocator->phinx('create');
        var_dump($x);
        exit();
    }
}