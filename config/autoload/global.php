<?php

return [
    'debug' => ($_SERVER['APPLICATION_ENV'] === 'production') ? false : true,

    'config_cache_enabled' => ($_SERVER['APPLICATION_ENV'] === 'production') ? true : false,
];