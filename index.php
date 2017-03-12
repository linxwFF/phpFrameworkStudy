<?php
//入口文件
define('APP_DEBUG',true);   //是否开启调试模式

// if(!APP_DEBUG){
//     error_reporting(0); //屏蔽错误
// }
require './framework/Framework.class.php';
Framework::run();
