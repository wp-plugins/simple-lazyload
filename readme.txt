=== simple-lazyload ===
Contributors: xiaoxu125634
Donate link: http://www.brunoxu.com/simple-lazyload.html
Tags: true lazyload, highslide, reduce http requests
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: trunk

simple-lazyload is an automatic image true lazyload plugin for WordPress, it can helps to reduce http requests effectively.


== Description ==

This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.

Besides lazyload, if you also need images slideshow effect or image popup view effect, better to use <a href="http://www.brunoxu.com/images-lazyload-and-slideshow.html" target="_blank">Images Lazyload and Slideshow</a> instead.

/**********************************************************/

<a href="http://webdev.brunoxu.com/archives/223.html" target="_blank">View Lazyload Effect Example 1</a>

<a href="http://webdev.brunoxu.com/archives/219.html" target="_blank">View Lazyload Effect Example 2</a>

<a href="http://webdev.brunoxu.com/archives/1115.html" target="_blank">View Lazyload Effect Example 3</a>

/**********************************************************/

本插件实现真实的图片迟加载功效，自动保存、替换图片的实际地址，只有当用户需要看到时，才会向服务器去请求图片内容，否则是一张空白图片，对服务器没有负担。

如果你同时也需要图片滑动效果或者图片弹出放大效果，可以选择使用  <a href="http://www.brunoxu.com/images-lazyload-and-slideshow.html" target="_blank">Images Lazyload and Slideshow</a> 作为代替。


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `simple-lazyload` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress Background.


== Frequently Asked Questions ==

Still Not Working, Leave a message to me in   http://www.brunoxu.com/simple-lazyload.html


== Screenshots ==

1. /screenshot-1.jpg


== Changelog ==

= 2.3 =
* 2014-06-19
* 	upgrade : optimize lazyload script, not to load images far from current screen above.
* 	upgrade : change links of plugin homepage and author homepage.

= 2.2 =
* 2012-07-17
* 	upgrade : add exception for plugin WP-PostRatings, for displaying reason.
* 	upgrade : do not lazyload images for feeds, previews, mobile. refer to Lazy Load plugin.

= 2.1 =
* 2012-06-07
* 	upgrade : for better performance, images with width or height use blank_1x1.gif as placeholder, while images without width and height use blank_250x250.gif as placeholder(except: smilies)
* 	upgrade : add a loading.gif background to each image, if image's loading is timeout, visitors will understand what happened.

= 2.0 =
* 2012-06-06
* 	upgrade : expand the scope of lazyload. previously only the content images take effect, now all the images work.

= 1.3 =
* 2012-05-29
* 	upgrade : images are visible even when javascript has been forbidden
* 	upgrade : optimize lazyload, reduce the Performance Loss
* 	upgrade : use wp_enqueue_script method instead of js loading jQuery library
* 	upgrade : add js to head instead of footer

= 1.2 =
* 2012-05-08
* 	upgrade : use loading picture instead of blank image

= 1.1 =
* 2012-04-15
* 	fixbug : lazyload sometime don't work, images can't showing
* 	upgrade : add javascripts to footer, no longer to the head

= 1.0 =
* 2012-03-25 simple-lazyload released
