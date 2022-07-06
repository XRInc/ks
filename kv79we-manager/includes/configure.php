<?php
define('HTTP_SERVER', 'https://kernanasumall.com');
define('HTTPS_SERVER', 'https://kernanasumall.com');
define('ENABLE_SSL', 'false');

//WS
define('DIR_WS_CATALOG', '/');
define('DIR_WS_CATALOG_IMAGES', HTTP_SERVER . DIR_WS_CATALOG . 'images/');
define('DIR_WS_CATALOG_IMAGES_CACHE', DIR_WS_CATALOG_IMAGES . 'cache/');
define('DIR_WS_ADMIN', DIR_WS_CATALOG . 'kv79we-manager/');
define('DIR_WS_ADMIN_IMAGES', 'images/');

//FS
define('ROOT_IMAGE', str_replace('\\', '/', dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/image/');
define('DIR_FS_CATALOG', str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))) . '/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_IMAGES_CACHE', DIR_FS_CATALOG_IMAGES . 'cache/');
define('DIR_FS_CATALOG_CACHE', DIR_FS_CATALOG . 'cache/');
define('DIR_FS_CATALOG_INCLUDES', DIR_FS_CATALOG . 'includes/');
define('DIR_FS_CATALOG_CLASSES', DIR_FS_CATALOG_INCLUDES . 'classes/');
define('DIR_FS_CATALOG_FUNCTIONS', DIR_FS_CATALOG_INCLUDES . 'functions/');
define('DIR_FS_CATALOG_TEMPLATES', DIR_FS_CATALOG_INCLUDES . 'templates/');
define('DIR_FS_ADMIN', str_replace('\\', '/', dirname(dirname(__FILE__))) . '/');
define('DIR_FS_ADMIN_INCLUDES', DIR_FS_ADMIN . 'includes/');
define('DIR_FS_ADMIN_CLASSES', DIR_FS_ADMIN_INCLUDES . 'classes/');
define('DIR_FS_ADMIN_FUNCTIONS', DIR_FS_ADMIN_INCLUDES . 'functions/');
define('DIR_FS_ADMIN_INIT_INCLUDES', DIR_FS_ADMIN_INCLUDES . 'init_includes/');

//DB
define('DB_TYPE', 'mysql');
define('DB_PREFIX', '');
define('DB_CHARSET', 'utf8');
define('DB_SERVER', 'ls-68ae4f5286535e918683b808da2fe76767d29c05.cm7vg81pk9jy.us-east-1.rds.amazonaws.com');
define('DB_SERVER_USERNAME', 'dbmasteruser');
define('DB_SERVER_PASSWORD', ',}J-[FEA<9.Y~a-TOpi]#KHcTN%?H0!f');
define('DB_DATABASE', 'KV79WE');
define('DB_CACHE_METHOD', 'file');

//ADMIN
define('ADMIN_USERNAME', 'manager');
define('ADMIN_PASSWORD', 'npJp5xxX7tEv91uL79');
