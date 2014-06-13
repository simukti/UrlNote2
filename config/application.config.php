<?php
return array(
    'modules' => array(
        'Application',
        'Simukti',
        'SmxGoogleAuth',
        'EdpModuleLayouts',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'config_cache_enabled'      => PRODUCTION,
        'config_cache_key'          => 'app_config_cache',
        'module_map_cache_enabled'  => PRODUCTION,
        'module_map_cache_key'      => 'module_map_cache',
        'cache_dir'                 => CACHE_PATH,
    ),
);
