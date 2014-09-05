<?php
/*
Plugin Name: simple-lazyload
Plugin URI: http://www.brunoxu.com/simple-lazyload.html
Description: Lazy load all images without configurations. It helps to decrease number of requests and improve page loading time. 延迟加载所有图片，无需配置，有助于减少请求数，提高页面加载速度。
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 2.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('SIMPLE_LAZYLOAD_VER', '2.4');

$is_strict_lazyload = FALSE;

function simple_lazyload_get_url($path='')
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
		ob_start('simple_lazyload_obend');
	}
	function simple_lazyload_obend($content) {
		return simple_lazyload_content_filter_lazyload($content);
	}
	function simple_lazyload_content_filter_lazyload($content)
	{
		// Don't lazyload for feeds, previews, mobile
		if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) )
			return $content;

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
	function lazyimg_str_handler($matches)
	{
		global $is_strict_lazyload;

		$lazyimg_str = $matches[0];

		//不需要lazyload的情况
		if (preg_match("/\/plugins\/wp-postratings\//i", $lazyimg_str)) {
			return $lazyimg_str;
		}

		if (preg_match("/width=/i", $lazyimg_str)
				|| preg_match("/width:/i", $lazyimg_str)
				|| preg_match("/height=/i", $lazyimg_str)
				|| preg_match("/height:/i", $lazyimg_str)) {
			$alt_image_src = simple_lazyload_get_url("blank_1x1.gif");
		} else {
			if (preg_match("/\/smilies\//i", $lazyimg_str)) {
				$alt_image_src = simple_lazyload_get_url("blank_1x1.gif");
			} else {
				$alt_image_src = simple_lazyload_get_url("blank_250x250.gif");
			}
		}

		if (stripos($lazyimg_str, "class=") === FALSE) {
			$lazyimg_str = preg_replace(
				"/<img(.*)>/i",
				'<img class="sl_lazyimg"$1>',
				$lazyimg_str
			);
		} else {
			$lazyimg_str = preg_replace(
				"/<img(.*)class=['\"]([\w\-\s]*)['\"](.*)>/i",
				'<img$1class="$2 sl_lazyimg"$3>',
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

	//add_action('wp_head', 'simple_lazyload_footer_lazyload', 11);
	add_action('wp_footer', 'simple_lazyload_footer_lazyload', 11);
	function simple_lazyload_footer_lazyload()
	{
		print('
<!-- lazyload images -->
<style type="text/css">
.sl_lazyimg{
opacity:0.2;filter:alpha(opacity=20);
background:url('.simple_lazyload_get_url("loading.gif").') no-repeat center center;
}
</style>
<!-- lazyload images end -->

<!-- case nojs, hidden lazyload images -->
<noscript>
<style type="text/css">
.sl_lazyimg{display:none;}
</style>
</noscript>
<!-- case nojs, hidden lazyload images end -->

<!-- lazyload -->
<script type="text/javascript">
jQuery(document).ready(function($) {
var sl_lazyload = function() {
	var threshold = 200;
	$("img.sl_lazyimg").each(function(){
		_self = $(this);
		if (_self.attr("lazyloadpass")===undefined
				&& _self.attr("file")
				&& ( !_self.attr("src") || (_self.attr("src") && _self.attr("file")!=_self.attr("src")) )
				) {
			if( (_self.offset().top) < ($(window).height()+$(document).scrollTop()+threshold)
				&& (_self.offset().left) < ($(window).width()+$(document).scrollLeft()+threshold)
				&& (_self.offset().top) > ($(document).scrollTop()-threshold)
				&& (_self.offset().left) > ($(document).scrollLeft()-threshold)
				) {
				_self.attr("src",_self.attr("file"));
				_self.attr("lazyloadpass","1");
				_self.animate({opacity:1},400);
			}
		}
	});
}
sl_lazyload();

var sl_itv;
$(window).scroll(function(){clearTimeout(sl_itv);sl_itv=setTimeout(sl_lazyload,400);});
$(window).resize(function(){clearTimeout(sl_itv);sl_itv=setTimeout(sl_lazyload,400);});
});
</script>
<!-- lazyload end -->
');
	}
}
simple_lazyload_lazyload();
