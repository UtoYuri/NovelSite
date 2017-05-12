<?php

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);
// 定义应用名称
define('APP_NAME', 'Novel_Site');
// 定义应用目录
define('APP_PATH', './NovelSite/');
// 加载框架引导文件
require('./ThinkPHP/ThinkPHP.php');