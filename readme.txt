=== simple-lazyload ===
Contributors: xiaoxu125634
Donate link: http://blog.brunoxu.info/simple-lazyload/
Tags: true lazyload, highslide, reduce http requests
Requires at least: 3.0
Tested up to: 3.3
Stable tag: trunk

simple-lazyload is an automatic image true lazyload plugin for WordPress, it can helps to reduce http requests effectively.


== Description ==

This plugin automatically copy image's src value to file attribute, replace src value with a blank image's url before showing, when the page is loaded, lazyload js will decide to load the images' actual content automatically, only when user wants to see them.

This plugin has a more perfect performance working with the "auto-highslide" plugin.

Also, you can use another super pugin "<a href="http://blog.brunoxu.info/auto-lazyload-and-auto-highslide/" target="_blank">auto-lazyload-and-auto-highslide</a>" instead of the two above.

<a href="http://webdev.brunoxu.info/archives/223.html" target="_blank">Check the true lazyload effect Example 1</a>

<a href="http://webdev.brunoxu.info/archives/1115.html" target="_blank">Check the true lazyload effect Example 2</a>

本插件实现真实的图片迟加载功效，自动保存、替换图片的实际地址，只有当用户需要看到时，才会向服务器去请求图片内容，否则是一张空白图片，对服务器没有负担。

本插件可以与【auto-highslide】插件配合使用，效果更佳。

当然你也可以使用另一个超强组合插件【<a href="http://blog.brunoxu.info/auto-lazyload-and-auto-highslide/" target="_blank">auto-lazyload-and-auto-highslide</a>】来取代它俩的功能。


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `simple-lazyload` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress Background.


== Frequently Asked Questions ==

Still Not Working, Leave a message to me in   http://blog.brunoxu.info/simple-lazyload/


== Screenshots ==


== Changelog ==

= 1.2 =
* 2012-05-08
* 	upgrade : use loading picture instead of blank image.

= 1.1 =
* 2012-04-15
* 	fixbug : lazyload sometime don't work, images can't showing.
* 	upgrade : add javascripts to footer, no longer to the head.

= 1.0 =
* 2012-03-25 simple-lazyload released.
