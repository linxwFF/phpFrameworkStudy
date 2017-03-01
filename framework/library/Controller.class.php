<?php
class Controller
{
    private $_data = [];
    private $_tips = '';

    //方法不存在时报错
    public function __call($name, $args)
    {
        Support::E('访问的方法不存在')
    }

    protected function redirect($url)
    {
        header("Location::$url");
        exit;
    }

    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] :null;
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    protected function display()
    {
        extract($this->_data);
        $this->_data = [];
        require ACTION_VIEW;
        exit;
    }

    protected function tips($flag = true,$tips = '')
    {
        if($flag)
        {
            $this->_tips = "<div>$tips</div>";
        }else{
            $this->_tips = "<div class='error'>$tips</div>";
        }
    }
}
