=== Plugin Name ===
Contributors: techxplorer
Tags: tags, shortcode, media, metadata, statistics, posts, instagram
Requires at least: 4.5
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin that implements a few small tweaks to post content, and media, that I find useful.

== Description ==

This plugin implements a few small tweaks to post content, and media, that I find useful. I've published this
plugin in the hope that other users will find it useful as well.

**Important Support Notice**
Development of this plugin has ceased, and it is now officially unsupported.
If you are still using this plugin you are strongly encouraged to find an alternative.

The tweaks implemented by the plugin are:

* Automatically adding WordPress tags to posts based on hashtags found on the post content.
* Adding three custom fields to uploaded media to make it easier to track attribution. The fields are:
    1. Author name.
    1. Author profile URL.
    1. Source URL.
* Automatically calculating, and displaying, various statistics on an individual post. The statistics include:
    1. Word count.
    1. Estimated reading time.
    1. Date and time of last update.
* Automatically importing photos from Instagram when added via IFTTT.

Original version of the logo by [Bryan Nielsen](https://openclipart.org/user-detail/bnielsen) and [available here](https://openclipart.org/detail/101335/cartoon-monkey-with-wrench).

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/techxplorers-content-tweaks` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Configure the plugin using the 'Content Tweaks' settings page.

== Frequently Asked Questions ==

= How does the tag matching tweak work? =

The tag matching tweak works by finding hashtags in the post title and post content. Any found tags are
matched to WordPress tags, and added to the post.

= Will the tag matching tweak add WordPress tags? =

Yes. If enabled the tweak will add missing WordPress tags. By default this option is not enabled.

= What fields are added to media uploads? =

This tweak adds three fields to uploaded media. I find these fields useful in recording the information necessary
to keep track attribution of the author of the media. The following three fields are added:

1. Author name.
1. Author profile URL.
1. Source URL.

= What statistics are calculated by the post statistics tweak? =

The following three statistics are calculated:

1. Word count.
1. Estimated reading time (number of words / 180 words per minute)
1. Date and time of last update.

= Is there a performance impact on the generation of statistics? =

The performance impact is minimal. When the tweak is enabled the statistics are generated when a post is saved or updated. They are then
stored in a post metadata field. The contents of this field are then used for display.

If the metadata field is missing, or the data is out of date, then the data is regenerated.

= Is it possible to override the default styling? =

Yes. Each tweak is enclosed in a `div` with a unique ID, this makes the content easier to target. Additionally there is a setting that
can be enabled to disable the output of the CSS supplied with the plugin.

== Changelog ==

= 1.3.0 =
* Fix code style errors using latest WordPress code style
* Add ability to import photos from Instagram when added via IFTTT

= 1.2.1 =
* Confirm compatibility with WordPress 4.8
* Fix code style errors using latest WordPress code style

= 1.2.0 =
* Confirm compatibility with WordPress 4.7
* Remove unused old post notification functionality
* Various minor bug fixes

= 1.1.0 =
* Add option to sync post date with last modified date
* Fix WordPress code style violations

= 1.0.1 =
* Released to the WordPress plugins repository.

= 1.0 =
* Initial release.
