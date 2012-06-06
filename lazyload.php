<?php
/*
Plugin Name: simple-lazyload
Plugin URI: http://blog.brunoxu.info/simple-lazyload/
Description: This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.　　本插件实现真实的图片迟加载功效，自动保存、替换图片的实际地址，只有当用户需要看到时，才会向服务器去请求图片内容，否则是一张空白图片，对服务器没有负担。
Version: 2.0
Author: Bruno Xu
Author URI: http://blog.brunoxu.info/
*/

define('SIMPLE_LAZYLOAD_VER', '2.0');


function get_url($path='')
{
	return plugins_url(ltrim($path, '/'), __FILE__);
}

add_action('wp_enqueue_scripts', 'simple_lazyload_script');
function simple_lazyload_script()
{
	wp_enqueue_script('jquery');
}

function simple_lazyload_lazyload()
{
	//init,get_header,wp_head
	add_action('get_header','simple_lazyload_obstart');
	function simple_lazyload_obstart() {
		ob_start();
	}

	//get_footer,wp_footer,shutdown(NG)
	add_action('wp_footer','simple_lazyload_obend');
	function simple_lazyload_obend() {
		$echo = ob_get_contents(); //获取缓冲区内容
		ob_clean(); //清楚缓冲区内容，不输出到页面
		print simple_lazyload_content_filter_lazyload($echo); //重新写入的缓冲区
		ob_end_flush(); //将缓冲区输入到页面，并关闭缓存区
	}

	function lazyimg_str_handler($matches)
	{
		global $is_strict_lazyload;

		$alt_image_src = get_url("blank.gif");

		$lazyimg_str = $matches[0];

		if (stripos($lazyimg_str, "class=") === FALSE) {
			$lazyimg_str = preg_replace(
				"/<img(.*)>/i",
				'<img class="lh_lazyimg"$1>',
				$lazyimg_str
			);
		} else {
			$lazyimg_str = preg_replace(
				"/<img(.*)class=['\"]([\w\-\s]*)['\"](.*)>/i",
				'<img$1class="$2 lh_lazyimg"$3>',
				$lazyimg_str
			);
		}

		if ($is_strict_lazyload) {
			$regexp = "/<img([^<>]*)src=['\"]([^<>'\"]*)\.(bmp|gif|jpeg|jpg|png)([^<>'\"]*)['\"]([^<>]*)>/i";
			$replace = '<img$1src="'.$alt_image_src.'" file="$2.$3$4"$5><noscript>'.$matches[0].'</noscript>';
		} else {
			$regexp = "/<img([^<>]*)src=['\"]([^<>'\"]*)['\"]([^<>]*)>/i";
			$replace = '<img$1src="'.$alt_image_src.'" file="$2"$3><noscript>'.$matches[0].'</noscript>';
		}

		$lazyimg_str = preg_replace(
			$regexp,
			$replace,
			$lazyimg_str
		);

		return $lazyimg_str;
	}

	function simple_lazyload_content_filter_lazyload($content)
	{
		global $is_strict_lazyload;

		if ($is_strict_lazyload) {
			$regexp = "/<img([^<>]*)\.(bmp|gif|jpeg|jpg|png)([^<>]*)>/i";
		} else {
			$regexp = "/<img([^<>]*)>/i";
		}

		$content = preg_replace_callback(
			$regexp,
			"lazyimg_str_handler",
			$content
		);

		return $content;
	}


	//add_action('wp_head', 'simple_lazyload_footer_lazyload', 11);
	add_action('wp_footer', 'simple_lazyload_footer_lazyload', 11);
	function simple_lazyload_footer_lazyload()
	{
		print('
<!-- case nojs, hidden lazyload images -->
<noscript>
<style type="text/css">
.lh_lazyimg{display:none;}
</style>
</noscript>
<!-- case nojs, hidden lazyload images end -->

<!-- lazyload -->
<script type="text/javascript">
jQuery(document).ready(function($) {
	function lazyload(){
		$("img.lh_lazyimg").each(function(){
			_self = $(this);
			if (_self.attr("lazyloadpass")===undefined
					&& _self.attr("file")
					&& (!_self.attr("src")
							|| (_self.attr("src") && _self.attr("file")!=_self.attr("src"))
						)
				) {
				if((_self.offset().top) < $(window).height()+$(document).scrollTop()
						&& (_self.offset().left) < $(window).width()+$(document).scrollLeft()
					) {
					_self.css("opacity", 0);
					_self.attr("src",_self.attr("file"));
					_self.attr("lazyloadpass", "1");
					_self.animate({opacity:1}, 500);
				}
			}
		});
	}
	lazyload();

	var itv;
	$(window).scroll(function(){clearTimeout(itv);itv=setTimeout(lazyload,400);});
	$(window).resize(function(){clearTimeout(itv);itv=setTimeout(lazyload,400);});
});
</script>
<!-- lazyload end -->
');
	}
}
simple_lazyload_lazyload();

?>