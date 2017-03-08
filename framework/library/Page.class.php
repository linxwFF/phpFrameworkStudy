<?php
//分页类
class Page{
	//生成URL参数
	private static function _url(){
		$params = $_GET;
		unset($params['page']);
		return http_build_query($params);
	}
	//获取分页HTML
	public static function html($total, $page, $size, $num=8){
		$page = max((int)$page, 1);             //当前访问的页码，最低为1
		$maxpage = max(ceil($total/$size), 1);  //计算总页数
		if($maxpage <= 1){
			return '';              //小于1页时不显示分页
		}
		$num = floor($num/2);       //计算当期页前后显示的相关链接个数
		$url = self::_url();		//获取URL参数字符串
		//拼接URL
		$url = $url ? "?$url&page=" : '?page=';
		$html = ''; //保存拼接结果
		//生成首页、上一页
		if($page > 1){
			$html .= "<a href=\"{$url}1\">首页</a>";
			$html .= '<a href="'.$url.($page-1).'">上一页</a>';
		}else{
			$html .= '<span>首页</span>';
			$html .= '<span>上一页</span>';
		}
		//生成分页导航
		$start = $page - $num;
		$end = $page + $num;
		if($start < 1){
			$end = $end+(1-$start);
			$start = 1;
		}
		if($end > $maxpage){
			$start = $start-($end-$maxpage);
			$end = $maxpage;
		}
		($start < 1) && $start = 1;
		($start > 1) && $html .= '<i>...</i>';
		for($i=$start; $i<=$end; ++$i){
			if($i == $page){
				$html .= "<a href=\"{$url}{$i}\" class=\"curr\">$i</a>";
			} else {
				$html .= "<a href=\"{$url}{$i}\">$i</a>";
			}
		}
		($end < $maxpage) && $html .= '<i>...</i>';
		//生成下一页、尾页
		if($page == $maxpage){
			$html .= '<span>下一页</span>';
			$html .= '<span>尾页</span>';
		}else{
			$html .= '<a href="'.$url.($page+1).'">下一页</a>';
			$html .= "<a href=\"{$url}{$maxpage}\">尾页</a>";
		}
		//返回结果
		return $html;
	}
}
