<?php
return array(
    /* Cookie设置 */
    'COOKIE_EXPIRE'         =>  7,       // Cookie有效期

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'novel_site',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3307',        // 端口
    'DB_PREFIX'             =>  't_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_FIELDS_CACHE'       =>  false,   //字段缓存

    /* 默认设定 */
    'DEFAULT_MODULE'        =>  'Book',  // 默认模块
    // 'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式


    // 'ERROR_PAGE'            =>  '/Common/Common/error.html', // 错误定向页面
);