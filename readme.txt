=== SSP Director Tools ===
Contributors: parelius
Tags: SlideShowPro, images, gallery, album, photos, fancybox, API, SSP, SSP Director, widget, shortcode, photo feed, RSS, feed
Requires at least: 3.0
Tested up to: 4.3
Stable tag: 1.3

SSP Director Tools give you means for integrating SlideShowPro Director content into a WordPress blog.

== Description ==

This Plugin uses the SSP Director API to pull content from [SlideShowPro Director][sspd].

Features:

* Insert a single image from SSP Director into a post using a shortcode. 
* Insert a photo grid with multiple images from SSP Director into a post using a shortcode. Query SSP Director for albums, galleries, tags and configure the sorting of the output. Photo grids with random images are possible.
* Insert widgets into your theme if you want to display a photo grid in a widget area.
* Sizing and image handling are according to your default settings but can be overridden by shortcode attributes.
* Use image metadata provided by SSP Director for formatting image captions.
* Enable/disable photo feed functionality which generates an encrypted RSS photo feed URL for photo grids.
* Use [FancyBox][] to display photos in lightbox style.

[sspd]: http://slideshowpro.net/products/slideshowpro_director/ "SlideShowPro Director"
[fancybox]: http://fancybox.net/ "Fancybox"

== Installation ==

Follow one of the standard installation processes:

* Upload `ssp-director-tools.php` to the `/wp-content/plugins/` directory and activate it
* OR use the search function within WordPress Admin, search for the Plugin, install and activate.

After activation you must set the API key and API path to make this plugin work.

If you want to use the photo feed feature, follow these steps:

* It is recommended to copy the `feed` directory to a directory outside of your WordPress installation.
* You might consider to copy it to the server hosting your director installation, e.g. to `http://director.myserver.com/feed/`.
* Then, enter the path to the feed installation into the SSP Director Toos Options page, e.g. `http://director.mysserver.com/feed/`.
* Define a secret for your photo feed installation, enter it into the SSP Director Tools Options page and set the `$sspdt_secret`variable in the `config.php`file to the same value.
* Finally, set the `$sspd_api_key`and `$sspd_api_path` variables in the `config.php`file to the values for your SSP Director installation.

== Frequently Asked Questions ==

= Can I integrate Flash slide shows? =

No. This is not within the scope of this plugin. Use [SlidePress] to embed Flash slide shows.

= Can I integrate videos? =

No. Currently this is not possible, but it is planned for a future version.

= What exaclty does the photo feed functionality do? =

Photo feeds are RSS feeds which can be consumed by other clients. You might for example provide a photo feed for all images shown in a photo grid. This feed could be imported into an application like iPhoto and thus always keep your iPhoto album up to date.

However, you cannot protect this feed by password. In order to provide minimum protection, the feed URLs are encrypted in a way that they can't be guessed. So, in situations where you restrict access to WordPress, you may want to prevent easy access to your photo feeds and thus use the encrypted feed URLs.

Be aware that any person who has acces to the feed URL can acces the photos.

= How can I configure the size of the images provided by photo feeds? =

You can do this by editing the configuration file of your photo feed installation. When `http://director.myserver.com/feed/` is the location of your photo feed installation, the configuration file is `http://director.myserver.com/feed/includes/config.php`.

Edit the parameters of the `$sspd_feed_full` variable as desired. The variable `$sspd_feed_preview` is used by some feed readers (like Safari or Firefox) to display image previews. This variable can be configured, too.

[slidepress]: http://wordpress.org/extend/plugins/slidepress/ "SlidePress"

== Screenshots ==

1. Options pane
2. Photo Grid Widget
3. Example output of a photo grid with RSS photo link

== Changelog ==

= 1.3 =
* Updated for compatibility with WordPress 4.3

= 1.2 =
* Tested for compatibility with WordPress 4.0
* Implemented watermarked content. Can be set in plugin options.
* Check, if jquery is already enqueued by other theme or plugin
* Minor fixes

= 1.1.2.2 =
* Fixed the version tag

= 1.1.2.1 =
* Tested for compatibility with WordPress 3.7

= 1.1.2 =
* Integrated sr_RS (Serbian) localisation. Thanks to Borisa Djuraskovic (borisad@webhostinghub.com) for the translation.

= 1.1.1 =
* Changed default settings for number of images to show in photo grids (24) and caption position (over)
* Updated de_DE localisation

= 1.1 =
* Configurable captions using a subset of IPTC and EXIF tags
* Some minor fixes (including this Readme)

= 1.0.1 =
* Fixed de_DE localisation
* Some maintenance fixes

= 1.0 =
* First public release

== Upgrade Notice ==

This version should work without problems when upgrading from earlier versions.

== Shortcode Help ==

Use this shortcode: `[sspd]`.

List of allowed attributes:

* `album`: The id of an album to show (integer).
* `gallery`: The id of a gallery to show (integer).
* `image`: The id of a single image to show (integer).
* `align`: Alignment of a single image (left|center|right, default: left).
* `caption`: Whether or not to show the caption of a single image (yes|1|no|0, default: 1).
* `limit`: The maximum number of images to be shown in a grid (integer, default: 0). 0 if unlimited. 
* `tags`: List of tags to filter the content by, separated by commas.
* `tagmode`: Mode in which the tag filter works. Match all tags or any out of the list (all|one, default: one).
* `sort_on`: The sort field (null|created_on|captured_on|modified_on|filename|random, default: null).
* `sort_direction`: The sort order (ASC|DESC, default: DESC).
* `rss`: Whether or not to show a RSS photo feed link below the photo grid (yes|1|no|0, default: 1).

If no attributes are specified, the default plugin settings apply as defined in the sections "Photo Grid Defaults" and "Image Sizes and Handling".

Example:

`[sspd gallery="6" limit="10" tags="technology" sort_on="captured_on" sort_direction="DESC"]`: This will output the 10 most recent photos of gallery 6 which are tagged with "technology".

== Using captions ==

Image captions are automatically generated using image metadata. You can define different captions for preview images and images presented by FancyBox. Use placehoders like `%placeholder%` in your caption definitions and define how dates will be formatted.

List of allowed placeholders:

* `%caption%`: The image caption as provided by Director. If the caption is not set in Director, the IPTC caption is used instead.
* `%byline%`: The IPTC byline
* `%city%`: The IPTC city
* `%country%`: The IPTC country
* `%date%`: The image capture date from the EXIF record

Date formatting: Use common [PHP date][] formats.

[php date]: http://www.php.net/manual/en/function.date.php "PHP date"

Using HTML in your captions: You may use the following HTML elements and attributes:

`<div style="">, <p style="">, <b>, <i> <strong>, <em>, <br>`

Example: `<b>%caption%</b> (%date%)<br><i>Photograph by %byline%</i>`

== Known Issues ==

* A bug in the Director API v. 1.5.0 beta generates a wrong result when a query with multiple tags is done. See [SSP Director Forum][sspd forum 29339].
* photo grids and photo feeds don't work for smart galleries and albums. (Seems to be a Director bug.)
* The plugin currently doesn't check if the photo feed feature is installed correctly.
* The photo feeds don't show the real sizes of the downloadable enclosures, but the sizes of the originals.

[sspd forum 29339]: http://forums.slideshowpro.net/viewtopic.php?id=29339 "API 1.5 : Bug with Tags filter on gallery"
