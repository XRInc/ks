<?php
define('HTTP_SERVER', 'https://kernanasumall.com');
define('HTTPS_SERVER', 'https://kernanasumall.com');
define('ENABLE_MOBILE', 'false');
define('ENABLE_SSL', 'false');
define('ENABLE_CATEGORY_TEMPLATE', 'false');

//WS
define('DIR_WS_CATALOG', '/');
define('DIR_WS_CATALOG_IMAGES', 'images/');
define('DIR_WS_CATALOG_IMAGES_CACHE', DIR_WS_CATALOG_IMAGES . 'cache/');
define('DIR_WS_CATALOG_INCLUDES', 'includes/');
define('DIR_WS_CATALOG_TEMPLATES', DIR_WS_CATALOG_INCLUDES . 'templates/');

//FS
define('ROOT_IMAGE', str_replace('\\', '/', dirname(dirname(dirname(dirname(__FILE__))))) . '/image/');
define('DIR_FS_CATALOG', str_replace('\\', '/', dirname(dirname(__FILE__))) . '/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_IMAGES_CACHE', DIR_FS_CATALOG_IMAGES . 'cache/');
define('DIR_FS_CATALOG_CACHE', DIR_FS_CATALOG . 'cache/');
define('DIR_FS_CATALOG_INCLUDES', DIR_FS_CATALOG . 'includes/');
define('DIR_FS_CATALOG_CLASSES', DIR_FS_CATALOG_INCLUDES . 'classes/');
define('DIR_FS_CATALOG_FUNCTIONS', DIR_FS_CATALOG_INCLUDES . 'functions/');
define('DIR_FS_CATALOG_INIT_INCLUDES', DIR_FS_CATALOG_INCLUDES . 'init_includes/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG_INCLUDES . 'languages/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG_INCLUDES . 'modules/');

//DB
define('DB_TYPE', 'mysql');
define('DB_PREFIX', '');
define('DB_CHARSET', 'utf8');
define('DB_SERVER', 'ls-68ae4f5286535e918683b808da2fe76767d29c05.cm7vg81pk9jy.us-east-1.rds.amazonaws.com');
define('DB_SERVER_USERNAME', 'dbmasteruser');
define('DB_SERVER_PASSWORD', ',}J-[FEA<9.Y~a-TOpi]#KHcTN%?H0!f');
define('DB_DATABASE', 'KV79WE');
define('DB_CACHE_METHOD', 'file');