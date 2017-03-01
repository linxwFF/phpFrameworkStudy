<?php
class Framework
{
    public static function run()
    {
        self::_init();  //初始化
        self::_registerAutoLoad();  //自动加载类
        self::_dispatch();  //分发URL请求
    }

    private static function _init()
    {
        //引入项目的目录
        define('DS', DIRECTORY_SEPARATOR);  //路径分隔符
        define('ROOT', getcwd().DS);    //路径根目录
        define('APP_PATH', ROOT.'app'.DS);  //app路径
        define('FRAMEWORK_PATH', ROOT.'framework'.DS);  //框架路径
        define('LIBRARY_PATH', FRAMEWORK_PATH.'library'.DS);    //类库路径
        define('COMMON_PATH', APP_PATH.'common'.DS);    //公共目录

        list($m, $c, $a) = self::_getParams();

        define('MODULE', strtolower($m));
        define('CONTROLLER', strtolower($c));
        define('ACTION', strtolower($a));

        //拼接模块，控制器，模型，视图路径
        define('MODULE_PATH', APP_PATH.MODULE.DS);
        define('CONTROLLER_PATH', MODULE_PATH.'controller'.DS);
        define('MODEL_PATH', MODULE_PATH.'model'.DS);
        define('VIEW_PATH', MODULE_PATH.'view'.DS);

        //视图
        define('COMON_VIEW', VIEW_PATH.'common'.DS);
        define('CONTROLLER_VIEW', VIEW_PATH.CONTROLLER.DS);
        define('ACTION_VIEW', CONTROLLER_VIEW.ACTION.'html');
    }

    private static function _registerAutoLoad()
    {
        spl_autoload_register(function($class_name){
            $class_name = ucwords($class_name);
            if(strpos($class_name, 'Controller')){
                $target = CONTROLLER_PATH."$class_name.class.php";
            }else if(strpos($class_name, 'Model')){
                $target = MODEL_PATH."$class_name.class.php";
            }else{
                $target = LIBRARY_PATH."$class_name.class.php";
            }
            require $target;
        });
    }

    private static function _getParams()
    {
        $m = isset($_POST['m']) ? $_POST['m'] : 'home';
        $c = isset($_POST['c']) ? $_POST['c'] : 'index';
        $a = isset($_POST['a']) ? $_POST['a'] : 'index';
        return  [$m, $c, $a];
    }

    private static function _dispatch()
    {
        $c = CONTROLLER.'Controller';
        $a = ACTION.'Action';
        $Controller = new $c();
        $Controller->$a();
    }
}
