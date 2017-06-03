<?php
return array(
    /* Cookie设置 */
    'COOKIE_EXPIRE'         =>  7,       // Cookie有效期

    /* Session设置 */
    'SESSION_OPTIONS'       =>  array(
        'name'              =>  'PHPSESSION',                    //设置session名
        'expire'            =>  3600 * 24 * 7,                   //SESSION过期时间，单位秒
        'use_trans_sid'     =>  1,                               //跨页传递
        'use_only_cookies'  =>  0,                               //是否只开启基于cookies的session的会话方式
    ),

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'movie_site',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3307',        // 端口
    'DB_PREFIX'             =>  't_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_FIELDS_CACHE'       =>  false,   //字段缓存

    /* 默认设定 */
    'DEFAULT_MODULE'        =>  'Media',  // 默认模块
    // 'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式


    // 'ERROR_PAGE'            =>  '/Common/Common/error.html', // 错误定向页面

    /* 邮件发送账号设置 */
    'MAIL_SMTP'             =>  'smtp.163.com',      // 邮箱smtp地址
    'MAIL_USERNAME'         =>  'urdreamer_service@163.com',      // 邮箱登录名
    'MAIL_PASSWORD'         =>  'Zhuyunrui433',      // 邮箱密码

    /*网站其他设置*/
    'CHECK_SESSION_KEY'         =>  false,      // 是否禁止异地登录
);