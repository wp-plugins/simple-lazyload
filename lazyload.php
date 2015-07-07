<?php if (!defined('ABSPATH')) exit;
/*
Plugin Name: Simple Lazyload
Plugin URI: http://www.brunoxu.com/simple-lazyload.html
Description: Lazy load all images without configurations. It helps to decrease number of requests and improve page loading time. 延迟加载所有图片，无需配置，有助于减少请求数，提高页面加载速度。
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 2.7
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( is_admin() || in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) ) {
	return;
}

define('SIMPLE_LAZYLOAD_VER', '2.7');
define('SIMPLE_LAZYLOAD_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('SIMPLE_LAZYLOAD_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

$simple_lazyload_is_strict_lazyload = FALSE;


add_action('wp_enqueue_scripts', 'simple_lazyload_script');
function simple_lazyload_script()
{
	wp_enqueue_script('jquery');
}

function simple_lazyload_lazyload()
{
	add_action('template_redirect','simple_lazyload_obstart');
	function simple_lazyload_obstart() {
		ob_start('simple_lazyload_obend');
	}
	function simple_lazyload_obend($content) {
		return simple_lazyload_content_filter_lazyload($content);
	}
	function simple_lazyload_content_filter_lazyload($content)
	{
		$skip_lazyload = apply_filters('simple_lazyload_skip_lazyload', false);

		// don't lazyload for feeds, previews
		if ( $skip_lazyload || is_feed() || is_preview() ) {
			return $content;
		}

		global $simple_lazyload_is_strict_lazyload;

		if ($simple_lazyload_is_strict_lazyload) {
			$regexp = "/<img([^<>]*)\.(bmp|gif|jpeg|jpg|png)([^<>]*)>/i";
		} else {
			$regexp = "/<img([^<>]*)>/i";
		}

		$content = preg_replace_callback(
			$regexp,
			"simple_lazyload_lazyimg_str_handler",
			$content
		);

		return $content;
	}
	function simple_lazyload_lazyimg_str_handler($matches)
	{
		global $simple_lazyload_is_strict_lazyload;

		$lazyimg_str = $matches[0];

		// no need to use lazy load
		if (stripos($lazyimg_str, 'src=') === FALSE) {
			return $lazyimg_str;
		}
		if (stripos($lazyimg_str, 'skip_lazyload') !== FALSE) {
			return $lazyimg_str;
		}
		if (preg_match("/\/plugins\/wp-postratings\//i", $lazyimg_str)) {
			return $lazyimg_str;
		}

		if (preg_match("/width=/i", $lazyimg_str)
				|| preg_match("/width:/i", $lazyimg_str)
				|| preg_match("/height=/i", $lazyimg_str)
				|| preg_match("/height:/i", $lazyimg_str)) {
			$alt_image_src = SIMPLE_LAZYLOAD_PLUGIN_URL.'blank_1x1.gif';
		} else {
			if (preg_match("/\/smilies\//i", $lazyimg_str)) {
				$alt_image_src = SIMPLE_LAZYLOAD_PLUGIN_URL.'blank_1x1.gif';
			} else {
				$alt_image_src = SIMPLE_LAZYLOAD_PLUGIN_URL.'blank_250x250.gif';
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

		if ($simple_lazyload_is_strict_lazyload) {
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
		$loading_icon = SIMPLE_LAZYLOAD_PLUGIN_URL.'loading2.gif';
		$loading_icon = apply_filters('simple_lazyload_loading_icon', $loading_icon);
		print('
<!-- Simple Lazyload '.SIMPLE_LAZYLOAD_VER.' - css and js -->
<style type="text/css">
.sl_lazyimg{
opacity:0.1;filter:alpha(opacity=10);
background:url('.$loading_icon.') no-repeat center center;
}
</style>

<noscript>
<style type="text/css">
.sl_lazyimg{display:none;}
</style>
</noscript>

<script type="text/javascript">
Array.prototype.S = String.fromCharCode(2);
Array.prototype.in_array = function(e) {
	var r = new RegExp(this.S+e+this.S);
	return (r.test(this.S+this.join(this.S)+this.S));
};

Array.prototype.pull=function(content){
	for(var i=0,n=0;i<this.length;i++){
		if(this[i]!=content){
			this[n++]=this[i];
		}
	}
	this.length-=1;
};

jQuery(function($) {
window._lazyimgs = $("img.sl_lazyimg");
if (_lazyimgs.length == 0) {
	return;
}
var toload_inds = [];
var loaded_inds = [];
var failed_inds = [];
var failed_count = {};
var lazyload = function() {
	if (loaded_inds.length==_lazyimgs.length) {
		return;
	}
	var threshold = 200;
	_lazyimgs.each(function(i){
		_self = $(this);
		if ( _self.attr("lazyloadpass")===undefined && _self.attr("file")
			&& ( !_self.attr("src") || (_self.attr("src") && _self.attr("file")!=_self.attr("src")) )
			) {
			if( (_self.offset().top) < ($(window).height()+$(document).scrollTop()+threshold)
				&& (_self.offset().left) < ($(window).width()+$(document).scrollLeft()+threshold)
				&& (_self.offset().top) > ($(document).scrollTop()-threshold)
				&& (_self.offset().left) > ($(document).scrollLeft()-threshold)
				) {
				if (toload_inds.in_array(i)) {
					return;
				}
				toload_inds.push(i);
				if (failed_count["count"+i] === undefined) {
					failed_count["count"+i] = 0;
				}
				_self.css("opacity",1);
				$("<img ind=\""+i+"\"/>").bind("load", function(){
					var ind = $(this).attr("ind");
					if (loaded_inds.in_array(ind)) {
						return;
					}
					loaded_inds.push(ind);
					var _img = _lazyimgs.eq(ind);
					_img.attr("src",_img.attr("file")).css("background-image","none").attr("lazyloadpass","1");
				}).bind("error", function(){
					var ind = $(this).attr("ind");
					if (!failed_inds.in_array(ind)) {
						failed_inds.push(ind);
					}
					failed_count["count"+ind]++;
					if (failed_count["count"+ind] < 2) {
						toload_inds.pull(ind);
					}
				}).attr("src", _self.attr("file"));
			}
		}
	});
}
lazyload();
var ins;
$(window).scroll(function(){clearTimeout(ins);ins=setTimeout(lazyload,100);});
$(window).resize(function(){clearTimeout(ins);ins=setTimeout(lazyload,100);});
});

jQuery(function($) {
var calc_image_height = function(_img) {
	var width = _img.attr("width");
	var height = _img.attr("height");
	if ( !(width && height && width>=300) ) return;
	var now_width = _img.width();
	var now_height = parseInt(height * (now_width/width));
	_img.css("height", now_height);
}
var fix_images_height = function() {
	_lazyimgs.each(function() {
		calc_image_height($(this));
	});
}
fix_images_height();
$(window).resize(fix_images_height);
});
</script>
<!-- Simple Lazyload '.SIMPLE_LAZYLOAD_VER.' - css and js END -->
');
	}
}
simple_lazyload_lazyload();
