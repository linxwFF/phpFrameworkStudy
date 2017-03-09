<?php
//图像处理类
class Image{
	//定义常量，表示缩略图缩放类型
	const FILL = 0;			//等比例填充白色背景
	const SCALE = 1;		//等比例缩放
	const SCALE_FILL = 2;	//等比例缩放，最大缩放
	/**
	 * 为图片生成缩略图
	 * @param int $mode 缩略图生成模式
	 * @param string $file_path 原图路径
	 * @param int $new_width 目标宽度
	 * @param int $new_height 目标高度
	 * @param array $config 保存选项
	 * @return array [0]表示成功或失败，成功时[1]表示文件路径，失败时[1]表示错误信息
	 */
	public static function thumb($mode, $file_path, $new_width, $new_height, $config=[]){
		//获取原图的宽高，并判断文件类型
		$info = getimagesize($file_path);
		$width = $info[0];      //图片宽度
		$height = $info[1];     //图片高度
		$mime = $info['mime'];  //图片类型
		//关联图像 MIME 类型和文件扩展名
		$file_type = [
			'image/png' => '.png',
			'image/jpeg' => '.jpg'
		];
		//判断图像类型，只允许 JPG 和 PNG 两种类型
		if(!isset($file_type[$mime])){
			throw new Exception('图片创建缩略图失败：只支持 jpg 和 png 类型的图片。');
		}
		//生成缩略图
		$im = self::_create($mime, $file_path);
		switch($mode){
			case self::FILL:
				$im = self::_thumb_fill($im, $width, $height, $new_width, $new_height);
				break;
			case self::SCALE_FILL:
				$im = self::_thumb_scale($im, $width, $height, $new_width, $new_height, true);
				break;
			default: 
				$im = self::_thumb_scale($im, $width, $height, $new_width, $new_height);
		}
		//准备保存选项
		$config = array_merge([
			'base_path' => './',			//上传基本路径
			'sub_path' => date('Y-m/d/'),	//自动生成的子目录
			'name'=> md5(uniqid(rand())).$file_type[$mime] //自动创建文件名
		], $config);
		//自动创建目录
		$save_path = $config['base_path'].$config['sub_path'];
		if(!file_exists($save_path) && !mkdir($save_path, 0777, true)){
			throw new Exception('文件保存失败：创建目录失败');
		}
		//将保存缩略图到指定目录
		self::_save($mime, $im, $save_path.$config['name']);
		//返回文件路径（不包括base_path）
		return $config['sub_path'].$config['name'];
	}
	/**
	 * 生成缩略图（等比例填充白色背景）
	 * @param resource $im 原图资源
	 * @param int $width 原图宽度
	 * @param int $height 原图高度
	 * @param int $new_width 目标宽度
	 * @param int $new_height 目标高度
	 * @return 缩略图资源
	 */
	private static function _thumb_fill($im, $width, $height, $new_width, $new_height){
		//等比例缩放计算
		if($width/$new_width > $height/$new_height) {
			$dst_width = $new_width;
			$dst_height = round($new_width / $width * $height);
		}else{
			$dst_height = $new_height;
			$dst_width = round($new_height / $height * $width);
		}
		//创建缩略图资源
		$thumb = imagecreatetruecolor($new_width, $new_height);
		//填充白色背景
		imagefill($thumb, 0, 0, imagecolorallocate($thumb, 255, 255, 255));
		//计算缩略图在画布上的位置
		$dst_x = ($new_width - $dst_width) / 2;
		$dst_y = ($new_height - $dst_height) / 2;
		//将按比例将缩略图重新采样，调整其位置
		imagecopyresampled($thumb, $im, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $width, $height);
		return $thumb;
	}

	/**
	 * 生成缩略图（等比例缩放）
	 * @param resource $im 原图资源
	 * @param int $width 原图宽度
	 * @param int $height 原图高度
	 * @param int $max_width 最大宽度
	 * @param int $max_height 最大高度
	 * @param bool $fill  是否最大缩放
	 * @return 缩略图资源
	 */
	private static function _thumb_scale($im, $width, $height, $max_width, $max_height, $fill=false){
		$dst_width = $width;
		$dst_height = $height;
		if($width/$max_width > $height/$max_height) {
			if($fill || ($width > $max_width)){
				$dst_width = $max_width;
				$dst_height = round($max_width / $width * $height);
			}
		}else{
			if($fill || ($height > $max_height)){
				$dst_height = $max_height;
				$dst_width = round($max_height / $height * $width);
			}
		}
		//创建缩略图资源
		$thumb = imagecreatetruecolor($dst_width, $dst_height);
		//将原图缩放填充到缩略图画布中
		imagecopyresampled($thumb, $im, 0, 0, 0, 0, $dst_width, $dst_height, $width, $height);
		return $thumb;
	}

	/**
	 * 根据原图文件创建图像资源
	 * @param string 图像MIME类型
	 * @param string 原图文件路径
	 * @return 返回创建的图像资源
	 */
	private static function _create($mime, $file_path){
		switch($mime){
			case 'image/png':  return imagecreatefrompng($file_path);
			case 'image/jpeg': return imagecreatefromjpeg($file_path);
		}
	}
	/**
	 * 保存图像资源
	 * @param string $mime 图像MIME类型
	 * @param resource $im 图像资源
	 * @param string $save_path 保存路径
	 * @return 成功返回true
	 */
	private static function _save($mime, $im, $save_path){
		switch($mime){
			case 'image/png':  return imagepng($im, $save_path);
			case 'image/jpeg': return imagejpeg($im, $save_path, 100);
		}
	}
	
	
}