=== Simple Lazyload ===
Contributors: xiaoxu125634
Donate link: http://www.brunoxu.com/simple-lazyload.html
Tags: simple-lazyload, lazy load, lazyload, images lazy load, images, lazy loading, optimize, performance, bandwidth
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: trunk

Lazy load all images without configurations. It helps to decrease number of requests and improve page loading time.


== Description ==

Lazy loading makes your site load faster and saves bandwidth.

This plugin replaces all images with a placeholder and loads the content as it gets close to enter the browser window when the visitor scrolls the page.

Besides lazy load, if you also need lightbox effect or gallery slideshow effect for images, better to use <a href="http://www.brunoxu.com/images-lazyload-and-slideshow.html" target="_blank">Images Lazyload and Slideshow</a> instead.

迟加载可以提高网站的加载速度，节约带宽。

插件用占位图来替换所有图片，当用户滚动窗口将要看到图片时才加载图片的真实内容。

如果你同时也需要图片弹出放大浏览效果或者相册滑动浏览效果，可以选择另一个插件 <a href="http://www.brunoxu.com/images-lazyload-and-slideshow.html" target="_blank">Images Lazyload and Slideshow</a> 看看。


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `simple-lazyload` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress Background.


== Frequently Asked Questions ==

Still Not Working, Leave a message to me in   http://www.brunoxu.com/simple-lazyload.html


== Screenshots ==

1. Plugin Overview.


== Changelog ==

= 2.7 =
* 2014-10-16
* fixbug: prevent image's multiple loading.
* upgrade: fix height when post images use percent width.
* upgrade: remove mobile exclusion.
* upgrade: use new loading icon, more prominent.
* upgrade: add 'simple_lazyload_loading_icon' filter for customizing loading icon.
* upgrade: reload images which has error occuring during first loading.

= 2.6 =
* 2014-09-26
* upgrade: optimize process of lazy loading for better performance and comfortable experience.

= 2.5 =
* 2014-09-21
* upgrade: add 'simple_lazyload_skip_lazyload' filter, use this you can customize lazy load scope.
* upgrade: add lazy load switch for images, when an image contains 'skip_lazyload' class or attribute, it will no longer be lazy loaded.

= 2.4 =
* 2014-08-25
* upgrade: optimize lazyload realization codes.
* upgrade: optimize description and tags.

= 2.3 =
* 2014-06-19
* upgrade: optimize lazyload script, not to load images far from current screen above.
* upgrade: change links of plugin homepage and author homepage.

= 2.2 =
* 2012-07-17
* upgrade: add exception for plugin WP-PostRatings, for displaying reason.
* upgrade: do not lazyload images for feeds, previews, mobile. refer to Lazy Load plugin.

= 2.1 =
* 2012-06-07
* upgrade: for better performance, images with width or height use blank_1x1.gif as placeholder, while images without width and height use blank_250x250.gif as placeholder(except: smilies)
* upgrade: add a loading.gif background to each image, if image's loading is timeout, visitors will understand what happened.

= 2.0 =
* 2012-06-06
* upgrade: expand the scope of lazyload. previously only the content images take effect, now all the images work.

= 1.3 =
* 2012-05-29
* upgrade: images are visible even when javascript has been forbidden
* upgrade: optimize lazyload, reduce the Performance Loss
* upgrade: use wp_enqueue_script method instead of js loading jQuery library
* upgrade: add js to head instead of footer

= 1.2 =
* 2012-05-08
* upgrade: use loading picture instead of blank image

= 1.1 =
* 2012-04-15
* fixbug: lazyload sometime don't work, images can't showing
* upgrade: add javascripts to footer, no longer to the head

= 1.0 =
* 2012-03-25 simple-lazyload released
