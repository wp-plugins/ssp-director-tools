=== SSP Director Tools ===
Contributors: parelius
Tags: SlideShowPro, images, gallery, album, photos, fancybox, API, SSP, SSP Director, widget, shortcode
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 1.0

SSP Director Tools give you means for integrating SlideShowPro Director content into a WordPress blog.

== Description ==

This Plugin uses the SSP Director API to pull content from [SlideShowPro Director](http://slideshowpro.net/products/slideshowpro_director/).

Features:

* Insert a single image from SSP Director into a post using a shortcode. 
* Insert a photo grid with multiple images from SSP Director into a post using a shortcode. Query SSP Director for albums, galleries, tags and configure the sorting of the output. Photo grids with random images are possible.
* Insert widgets into your theme if you want to display a photo grid in a widget area.
* Sizing and image handling are according to your default settings but can be overridden by shortcode attributes.
* Use image metadata provided by SSP Director.
* Enable/disable photo feed functionality which generates an encrypted RSS photo feed URL for photo grids.
* Use [FancyBox](http://fancybox.net/) to display photos in lightbox style.

== Installation ==

Follow one of the standard installation processes:

* Upload `ssp-director-tools.php` to the `/wp-content/plugins/` directory and activate it
* OR use the search function within WordPress Admin, search for the Plugin, install and activate.

After activation you must set the API key and API path to make this plugin work.

If you want to use the photo feed feature, follow these steps:

* It is recommended to copy the `feed` directory to a directory outside of your WordPress installation.
* You might consider to copy it to the server hosting your director installation, e.g. to `http://director.myserver.com/feed/`.
* Then, enter the path to the `feed.php` into the SSP Director Toos Options page, e.g. `http://director.mysserver.com/feed/feed.php`.
* Define a secret for your photo feed installation, enter it into the SSP Director Tools Options page and set the `$sspdt_secret`variable in the `config.php`file to the same value.
* Finally, set the `$sspd_api_key`and `$sspd_api_path` variables in the `config.php`file to the values for your SSP Director installation.

== Frequently Asked Questions ==

= Can I integrate Flash slide shows? =

No. This is not within the scope of this plugin. Use [SlidePress](http://wordpress.org/extend/plugins/slidepress/) to embed Flash slide shows.

= Can I integrate videos? =

No. Currently this is not possible, but it is planned for a future version.

== Screenshots ==

1. Options pane
2. Photo Grid Widget
3. Example output of a photo grid with RSS photo link

== Changelog ==

= 1.0 =
* First public release

== Upgrade Notice ==

No upgrades, yet.

== Shortcode Help ==

Use this shortcode: `[sspd]`.

List of allowed attributes:

* `album`: The id of an album to show (integer).
* `gallery`: The id of a gallery to show (integer).
* `image`: The id of a single image to show (integer).
* `align`: Alignment of a single image (left|center|right, default: left).
* `caption`: Whether or not to show the caption of a single image (yes|1|no|0, default: 1).
* `limit`: The maximum number of images to be shown in a grid (integer, default: 0). 0 if unlimited. 
* `tags: List of tags to filter the content by, separated by commas.
* `tagmode`: Mode in which the tag filter works. Match all tags or any out of the list (all|one, default: one).
* `sort_on`: The sort field (null|created_on|captured_on|modified_on|filename|random, default: null).
* `sort_direction`: The sort order (ASC|DESC, default: ASC).
* `rss`: Whether or not to show a RSS photo feed link below the photo grid (yes|1|no|0, default: 1).

If no attributes are specified, the default plugin settings apply as defined in the sections "Photo Grid Defaults" and "Image Sizes an Handling".

Example:

`[sspd gallery="6" limit="10" tags="technology" sort_on="captured_on" sort_direction="DESC"]`: This will output the 10 most recent photos of gallery 6 which are tagged with "technology".


== Known Issues ==

* A bug in the Director API v. 1.5.0 beta generates a wrong result when a query with multiple tags is done. See [SSP Director Forum](http://forums.slideshowpro.net/viewtopic.php?id=29339).
