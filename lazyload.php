<?php
/*
Plugin Name: simple-lazyload
Plugin URI: http://blog.brunoxu.info/simple-lazyload/
Description: This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.　　本插件实现真实的图片迟加载功效，自动保存、替换图片的真实地址，只有当用户真实需要看到时，图片才会向服务器去请求内容，否着它是一张空白图片，对服务器没有负担。本插件可以与auto-highslide插件配合使用，效果更佳。当然你也可以使用另一个超强组合插件(http://blog.brunoxu.info/auto-lazyload-and-auto-highslide/)来取代它俩的功能。
Version: 1.0
Author: Bruno Xu
Author URI: http://blog.brunoxu.info/
*/


add_filter('the_content', 'simple_lazyload_replace', 11);
add_action('wp_head', 'simple_lazyload_head', 11);


/* simple_lazyload_replace */
function simple_lazyload_replace($content)
{
	global $post;

	$blank_image_src = get_bloginfo('wpurl') . '/wp-content/plugins/simple-lazyload/blank_image.gif';
	$pattern = "/<img([^<>]*)(src=)('|\")([^<>]*)\.(bmp|gif|jpeg|jpg|png)('|\")([^<>]*)>/i";
	$replacement = '<img$1src="'.$blank_image_src.'" file="$4.$5"$7>';
	$content = preg_replace($pattern, $replacement, $content);

	return $content;
}

/* simple_lazyload_head */
function simple_lazyload_head() {
	print('
<script type="text/javascript">
var isJqueryLoaded = false;
var limitWaitMillionseconds = 1000;
var waitedMillionseconds = 0;
var waitStep = 100;
var needtodoFunctions = new Array();

function loadjq() {
	if (typeof(jQuery)=="undefined") {
		if (! isJqueryLoaded) {
			var jq = document.createElement("script");
			jq.type = "text/javascript";
			jq.src = "' . get_bloginfo('wpurl') . '/wp-content/plugins/simple-lazyload/jquery.js";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(jq, s);

			isJqueryLoaded = true;
		}

		waitedMillionseconds += waitStep;
		if (waitedMillionseconds <= limitWaitMillionseconds) {
			setTimeout("loadjq()", waitStep);
		}
	} else {
		setTimeout("needtodosth()", 1000);
	}
}
loadjq();

function needtodosth() {
	for (var i=0;i<needtodoFunctions.length;i++) {
		needtodoFunctions[i].call();//needtodoFunctions[i](); the same in chrome 14
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
</script>
');
}

?>