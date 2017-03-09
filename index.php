<?php
//入口文件
define('APP_DEBUG',true);   //是否开启调试模式
require './framework/Framework.class.php';
Framework::run();
