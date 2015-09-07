=== WpF Ultimate Carousel ===
Tags: carousel, slider, slider carousel, post carousel slider, custom post carousel slider, post slider, custom post slider.
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fully customizable Carousel plugin. Display post or custom posts contents in anywhere in your theme in carousel view.

== Description ==

WpF Ultimate Carousel is a responsive wordpress carousel slider plugin which through you can add unlimited carousel slider into your theme.
You can change styles according to your theme design. 

Live Demo: http://wpfreeware.com/preview/wpf-ultimate-carousel

<b>Features:</b>

1. Unlimited Carousel.
2. Very easy installation (just install & use shortcode, Thats it!)
3. Shortcode system
4. unlimited color variation by using shortcode attributes.
5. Responsive
6. custom post supported
7. custom taxonomy supported
8. carousel controls 
9 & more.


<b>Shortcodes</b>

Remember: Must put a unique name in each shortcode, If you want to use 3 carousel in one page then you can put in the first carousel name="carousel-1", second name="carousel-2" & 3rd name="carousel-3".
If you are using one news ticker in one page then leave it.

----- General Carousel -----------

<pre>[wpf_carousel name="my-carousel" title="Title Goes here"] //name=(unique-name)</pre>

----- Carousel form category ---------

<pre>[wpf_carousel name="my-carousel-2" category="category name" title="Title Goes here"] //name=(unique-name)</pre> 

 

----- Carousel form custom taxonomy  ----------

<pre>[wpf_carousel name="my-carousel-3" taxonomy="your-taxonomy-name" category="category name" title="Title goes here"] //name=(unique-name)</pre>

 
----- Shortcode attributes are: ------

<pre>post_type="post"  // default is "post"</pre>
info: Which post type you want to display.

<pre>category="category name" // default is empty</pre>
info: Put a category name,if you want to display contents from a specific category.

<pre>taxonomy="taxonomy name"	// default is category_name </pre>
info: If you want to display custom post contents from a specific custom taxonomy then put the custom taxonomy name here. default is category_name.

<pre>name="carousel-name" // default is wpf_carousel</pre>
info: Put any unique name (without spaces) for each carousel for using multiple in one page.

<pre>title="Carousel title goes here." // default is More...</pre>		
info: Carousel Title text.

<pre>count="posts amount to display" // default is unlimited</pre>
info: Put a numeric value to display posts amount.

<pre>title_color="color name or hex code" // default is #000000</pre>
info: put carousel title color code. Ex: #000000 or black.

<pre>title_bg_color="Title background color" // default is none</pre>
info: put carousel title background color code. Ex: #000000 or black.

<pre>link_color=" Post title Link color" // default is #222222</pre>
info : change link color.

<pre>slides="numeric-value" // default is 4</pre>
info : Put a numeric value to display slides.

<pre>scroll="numeric-value" // default is 1</pre>
info : Put a numeric value to scroll slides.

<pre>autoplay="true-false" // default is false</pre>
info : enable autoplay. available values are true/false.

<pre>speed="numeric-value" // default is 2000</pre>
info : higher value will slower & lower value will faster the carousel.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-directory` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode in page, post or in widgets.
4. If you want carousel in your theme php, Place `<?php echo do_shortcode('[wpf_carousel name="my-carousel" title="Title Goes here"]'); ?>` in your templates. More shortcodes attributes are available. See description tab.


== Frequently Asked Questions ==

How to install the plugin?

Answer: Read install tab for letting about installation.

Where I can use this plugin?

Answer: You can use this plugin anywhere you want through shortcode. Read shortcode details in description tab.

What to do after install this plugin?

Answer: No need to do anything, it will works on the fly. Install the plugin & use plugin shortcode anywhere in your theme you want.

== Screenshots ==

1. 4 slides
2. 3 slides with different title
3. 2 slides with different title
4. 1 big slide with different title
5. 5 slides with different title


== Changelog ==

= 1.0 =
* Initial Release