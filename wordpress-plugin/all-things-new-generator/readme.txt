=== All Things New – Social Media Generator ===
Contributors: allthingsnew
Tags: shortcode, image generator, social media, campaign
Requires at least: 5.5
Tested up to: 6.6
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a photo-composition tool for the "All Things New" campaign via a shortcode.

== Description ==

Adds the `[all_things_new_generator]` shortcode. Visitors upload their own photo,
drag/zoom/rotate it into place, and download it composited with the campaign's
branded frame in three formats: horizontal (1200x630), story (1080x1920), and
square (1080x1080). The card also offers direct downloads of the official logo
files (color, black, white — horizontal and vertical).

== Installation ==

1. In your WordPress admin, go to Plugins > Add New > Upload Plugin.
2. Choose the `all-things-new-generator.zip` file and click Install Now.
3. Activate the plugin.
4. Add the shortcode `[all_things_new_generator]` to any page or post where you
   want the generator to appear.

== Social sharing image ==

On any page/post containing the shortcode, the plugin automatically prints
Open Graph and Twitter Card meta tags pointing at the bundled
`assets/img/cover.jpg`, so the link shows that image as its preview when
shared on Facebook, WhatsApp, Twitter/X, LinkedIn, etc. To change the image,
replace `assets/img/cover.jpg` in the plugin folder (keep the same filename)
and update the `og:image:width` / `og:image:height` values in
`all-things-new-generator.php` if the new image has different dimensions.

If Yoast SEO, Rank Math, or All in One SEO is active, this plugin skips its
own tags automatically (to avoid duplicates) — set the social/OG image for
the page in that plugin's settings instead.

== Changelog ==

= 1.0.0 =
* Initial release.
* Automatic Open Graph / Twitter Card social preview image (cover.jpg).
