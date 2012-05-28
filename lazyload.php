<?php
/*
Plugin Name: simple-lazyload
Plugin URI: http://blog.brunoxu.info/simple-lazyload/
Description: This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.　　本插件实现真实的图片迟加载功效，自动保存、替换图片的实际地址，只有当用户需要看到时，才会向服务器去请求图片内容，否则是一张空白图片，对服务器没有负担。
Version: 1.3
Author: Bruno Xu
Author URI: http://blog.brunoxu.info/
*/

define('SIMPLE_LAZYLOAD_VER', '1.3');


add_filter('the_content', 'simple_lazyload_filter', 11);

add_action('wp_head', 'simple_lazyload_js', 11);
//add_action('wp_footer', 'simple_lazyload_js', 11);


add_action('wp_enqueue_scripts', 'simple_lazyload_script');
function simple_lazyload_script()
{
	wp_enqueue_script('jquery');
}

/* simple_lazyload_filter */
function simple_lazyload_filter($content)
{
	$content = preg_replace_callback(
		"/<img([^<>]*)>/i",
		"lazyimg_str_handler",
		$content
	);

	return $content;
}
function lazyimg_str_handler($matches) {
	$alt_image_src = get_bloginfo('wpurl') . '/wp-content/plugins/simple-lazyload/loading.gif';//blank.gif

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

	$lazyimg_str = preg_replace(
		"/<img([^<>]*)src=['\"]([^<>]*)\.(bmp|gif|jpeg|jpg|png)['\"]([^<>]*)>/i",
		'<img$1src="'.$alt_image_src.'" file="$2.$3"$4><noscript>'.$matches[0].'</noscript>',
		$lazyimg_str
	);

	return $lazyimg_str;
}

/* simple_lazyload_js */
function simple_lazyload_js()
{
	print('
<!-- hidden lazyload image -->
<noscript>
<style type="text/css">
.lh_lazyimg{display:none;}
</style>
</noscript>
<!-- hidden lazyload image end -->

<!-- lazyload -->
<script type="text/javascript">
jQuery(document).ready(function($) {
	function lazyload(){
		$("img.lh_lazyimg").each(function(){
			_self = $(this);
			if (!_self.attr("lazyloadpass")
					&& _self.attr("file")
					&& (!_self.attr("src")
							|| (_self.attr("src") && _self.attr("file")!=_self.attr("src"))
						)
				) {
				if((_self.offset().top) < $(window).height()+$(document).scrollTop()
						&& (_self.offset().left) < $(window).width()+$(document).scrollLeft()
					) {
					_self.attr("src",_self.attr("file"));
					_self.attr("lazyloadpass", "1");
				}
			}
		});
	}
	lazyload();

	var itv;
	$(window).scroll(function(){clearTimeout(itv);itv=setTimeout(lazyload,500);});
	$(window).resize(function(){clearTimeout(itv);itv=setTimeout(lazyload,500);});
});
</script>
<!-- lazyload end -->
');
}

?>