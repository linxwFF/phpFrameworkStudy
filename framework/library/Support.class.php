<?php
class Support
{
    public static function Error($msg)
    {
        exit('<pre>'.htmlspecialchars($msg).'</pre>');
    }

    public static function Config($name)
    {
        static $config = null;
        if(!$config){
            $config = require COMMON_PATH.'config.php';
        }
        return isset($config[$name]) ? $config[$name] : '';
    }
}
