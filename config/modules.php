<?php

use Zend\Expressive\ConfigManager\ConfigManager;

/**
 * Use Fully Qualified Namespace to enable the expressive configuration
 */
$modules = [
    App\ConfigProvider::class,
    Student\ConfigProvider::class,
    Employee\ConfigProvider::class,
    //Append module namespace here
];

return (new ConfigManager($modules))->getMergedConfig();
