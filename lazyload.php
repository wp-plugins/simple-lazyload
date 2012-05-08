<?php
/*
Plugin Name: simple-lazyload
Plugin URI: http://blog.brunoxu.info/simple-lazyload/
Description: This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.　　本插件实现真实的图片迟加载功效，自动保存、替换图片的实际地址，只有当用户需要看到时，才会向服务器去请求图片内容，否则是一张空白图片，对服务器没有负担。　　本插件可以与【auto-highslide】插件配合使用，效果更佳。当然你也可以使用另一个超强组合插件【<a href="http://blog.brunoxu.info/auto-lazyload-and-auto-highslide/" target="_blank">auto-lazyload-and-auto-highslide</a>】来取代它俩的功能。
Version: 1.2
Author: Bruno Xu
Author URI: http://blog.brunoxu.info/
*/

define('SIMPLE_LAZYLOAD_VER', '1.2');


add_filter('the_content', 'simple_lazyload_replace', 11);
//add_action('wp_head', 'simple_lazyload_head', 11);
add_action('wp_footer', 'simple_lazyload_footer', 11);


/* simple_lazyload_replace */
function simple_lazyload_replace($content)
{
	global $post;

	$blank_image_src = get_bloginfo('wpurl') . '/wp-content/plugins/simple-lazyload/loading_1.gif';//blank_image.gif
	$pattern = "/<img([^<>]*)(src=)('|\")([^<>]*)\.(bmp|gif|jpeg|jpg|png)('|\")([^<>]*)>/i";
	$replacement = '<img$1src="'.$blank_image_src.'" file="$4.$5"$7>';
	$content = preg_replace($pattern, $replacement, $content);

	return $content;
}

/* simple_lazyload_footer */
function simple_lazyload_footer() {
	print('
<!-- simple-lazyload -->
<script type="text/javascript">
var needtodoFunctions = new Array();
function needtodosth() {
	for (var i=0;i<needtodoFunctions.length;i++) {
		needtodoFunctions[i].call();
	}
}

function realize_lazyload() {
	jQuery(document).ready(function($) {
		function lazyload(){
			$("img").each(function(){
				if ($(this).attr("file")
						&& (!$(this).attr("src")
							|| ($(this).attr("src") && $(this).attr("file")!=$(this).attr("src"))
							)
					) {
					if(($(this).offset().top)<$(window).height()+$(document).scrollTop()&&($(this).offset().left)<$(window).width()+$(document).scrollLeft()) {
						$(this).attr("src",$(this).attr("file"));
					}
				}
			});
		}
		lazyload();
		$(window).scroll(lazyload);
		$(window).resize(lazyload);
	});
}
needtodoFunctions.push(realize_lazyload);

var isJqueryLazyLoaded = false;
var limitWaitMillionseconds = 4000;
var waitStep = 200;
var waitedMillionseconds = 0;
function loadjqAndDosth() {
	if (typeof(jQuery)=="undefined") {
		if (! isJqueryLazyLoaded) {
			var jq = document.createElement("script");
			jq.type = "text/javascript";
			jq.src = "' . get_bloginfo('wpurl') . '/wp-content/plugins/simple-lazyload/jquery.js";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(jq, s);

			isJqueryLazyLoaded = true;
		}

		waitedMillionseconds += waitStep;
		if (waitedMillionseconds <= limitWaitMillionseconds) {
			setTimeout("loadjqAndDosth()", waitStep);
		}
	} else {
		needtodosth();
	}
}
loadjqAndDosth();
</script>
<!-- simple-lazyload end -->
');
}

?>