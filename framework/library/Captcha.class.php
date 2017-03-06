<?php
//生成验证码
class captcha
{
    //生成验证码字串
    public function code($count = 5)
    {
        $code ='';//保存生成的验证码文本
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';  //随机因子
        $len = strlen($charset) - 1;
        for($i = 0; $i <$count; $i++)
        {
            $code .= $charset[rand(0, $len)];   //随机生成
        }
        return $code;
    }

    public function show($code)
    {
        //创建图片资源，随机生成背景颜色
        $im = imagecreate($x=250, $y=62);
        imagecolorallocate($im, rand(50,200), rand(0,155), rand(0,155));
        $fontColor = imagecolorallocate($im, 255, 255, 255);    //字体颜色
        $fontStyle = COMMON_PATH.'captcha.ttf';    //字体
        //生成指定长度的验证码
        for($i =0, $len =strlen($code); $i<$len; ++$i)
        {
            imagettftext($im,
            30,
            rand(0,20) - rand(0,25),
            32 + $i*40, rand(30,50),
            $fontColor,
            $fontStyle,
            $code[$i]
            );
        }

        //干扰线
        for($i = 0;$i<8;++$i)
        {
            $lineColor = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
            imageline($im, rand(0,$x),0,rand(0,$x),$y,$lineColor);
        }

        //干扰点
        for($i =0;$i<250;++$i)
        {
            imagesetpixel($im, rand(0,$x),rand(0,$y),$fontColor);
        }

        header('Content-type:image/png');
        imagepng($im);
        imagedestroy($im); //释放图片占用内存
    }
}
