=== Transform ===
Contributors: nillc
Tags: transform, json, xml, dust, xslt, data, transformation, integration, MVC, controller
Requires at least: 4.6
Tested up to: 5.0
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://netinnovationsllc.com/

Transform reads URL or file JSON/XML data feeds and converts those feeds to HTML using Dust or XSLT, acting as a controller in an MVC architecture.

== Description ==

Transform is a WordPress plugin that reads JSON or XML* feeds and transforms the feeds to HTML using display templates written in Dust (for JSON) or XSLT (for XML). Feeds and displays can be URLs to pages on the local or a remote web server, a path to a file on the site’s web server or static text in the WordPress database. The plugin is the controller in an MVC environment.

* Transforming XML feeds requires XSLT support in PHP (see http://php.net/manual/en/xsl.installation.php).

The unregistered version of the Transform plugin allows the creation of five Transform instances. To have more than five instances the Transform plugin needs to be registered.

Documentation and other information about Transform is available at [netinnovationsllc.com/transform](https://netinnovationsllc.com/transform/).

== Installation ==

There are two different ways to download and install Transform. The first is to select the “Plugins” option in the site’s dashboard, click on the “Add New” button then search for “Transform”. Find the Transform plugin by Net Innovations LLC and click the “Install Now” button.

The second option is to download the zip archive of the plugin, extract the archive then upload the “transform” folder to your site’s wp-content/plugins folder.

Once installed, go to the site’s dashboard, open the Plugins option, find the Transform plugin and click “Activate” to enable the plugin.


== Frequently Asked Questions ==

= Why am I not seeing options for XML or XSLT transformations? =

You server's PHP configuration does not support XSLT. Ask you web hosting provider to enable the XSLT extension for PHP.

= What templating engines does Transform support? =

Currently Transform support Dust templates for JSON data and XSL templates for XML data.

== Screenshots ==

1. See [netinnovationsllc.com/transform/documentation](https://netinnovationsllc.com/transform/documentation/) for detailed screenshots.

== Changelog ==

= 1.4.1 =
* Moved initialization of AJAX routines

= 1.4 =
* Added support for the Gutenberg editor

= 1.3 =
* UI improvements when Ajax work is being done
* Minor bug fix to selecting instances

= 1.2 =
* Replaced the Dust engine with https://github.com/Bloafer/dust-php

= 1.1 =
* Minor fixes and licensing improvements

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.4.1 =
Latest version of Transform
