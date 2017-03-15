<?php
class Controller
{
    private $_data = [];
    private $_tips = '';
    private $_layout = false; //布局开关

    //方法不存在时报错
    public function __call($name, $args)
    {
        Support::Error('访问的方法不存在');
    }

    protected function redirect($url)
    {
        header("Location:$url");
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

    //显示模版VIEW页面
    protected function display()
    {
        extract($this->_data);  //提取变量
        $this->_data = [];
        require $this->_layout ? $this->_layout : ACTION_VIEW; //载入视图
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

    //布局开关
    protected function layout($layout){
        $this->_layout = $layout;
    }
}
