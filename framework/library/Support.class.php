<?php
//辅助类
class Support
{
    //错误显示
    public static function Error($msg)
    {
        exit('<pre>'.htmlspecialchars($msg).'</pre>');
    }

    //获取配置信息
    public static function Config($name)
    {
        static $config = null;
        if(!$config){
            $config = require COMMON_PATH.'config.php';
        }
        return isset($config[$name]) ? $config[$name] : '';
    }

    //session操作
    public static function Session($name, $value ='', $type ='get')
    {
        $prefix =   Support::Config('SESSION')['SESSION_PREFIX'];
        if(!isset($_SESSION[$prefix]))
        {
            $_SESSION[$prefix] = [];
        }
        switch($type)
        {
            case 'get':
                return isset($_SESSION[$prefix][$name]) ? $_SESSION[$prefix][$name] : '';
            case 'isset':
                return isset($_SESSION[$prefix][$name]);
            case 'save':
                $_SESSION[$prefix][$name] = $value;
            break;
            case 'unset':
                unset($_SESSION[$prefix][$name]);
            break;
        }
    }

    //接收变量，过滤变量操作
    public static function Input($var, $method='post', $type='html', $def='')
    {
        switch($method)
        {
            case 'get': $method = $_GET; break;
            case 'post': $method = $_POST; break;
        }
        $value = isset($method[$var]) ? $method[$var] : $def;
        switch($type)
        {
            case 'string':
                $value = is_string($value) ? $value : '';
                break;
            case 'html':
                $value = is_string($value) ? toHTML($value) : '';
                break;
            case 'int': $value = (int)$value; break;
            case 'id': $value = max((int)$value, 0); break;
            case 'page': $value = max((int)$value,1); break;
            case 'float': $value = (float)$value; break;
            case 'bool': $value = (bool)$value; break;
            case 'array': $value = is_array($value) ? $value : []; break;
        }

        return $value;
    }

    private function toHTML($str)
    {
        $str = trim(htmlspecialchars($str, ENT_QUOTES));
        return str_replace(' ', '&nbsp;', $str);
    }

    //单例模式，实例化指定模型
    public static function Dtable($name)
    {
        static $Model = [];
        $name = strtolower($name);
        if(!isset($Model[$name]))
        {
            $class_name = ucwords($name).'Model';
            //存在和模型相关的类则实例化，不存在实例化空模型
            $Model[$name] = is_file(MODEL_PATH."$class_name.class.php") ? new $class_name($name) : Mempty();
        }
        return $Model[$name];
    }

    //实例化空模型
    public function Mempty()
    {
        static $Model = null;
        if(!$Model)
        {
            $Model = new Model();
        }
        return $Model;
    }

    //跳转页面
    public function Redirect($url)
    {
        header("Location:$url");
        exit();
    }
}
