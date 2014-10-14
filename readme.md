Exact Target XML Feed
========================

Contributors: chriswgerber  
Tags: feed, xml, exact target  
Requires at least: 3.0.0  
Tested up to: 4.0  
Stable tag: 0.1.0 - Beta  
License: GPLv2  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Custom XML feed formatted for Exact Target

----------------------------------------

***Note:***  
This plugin is still being developed and isn't ready for release. 

*Issues:*

* Able to add settings for a category only. There exists no functionality to delete them after creation.
* Because of the nature of image sizes, I will not add functionality to go back and resize all images for the correct categories. If you need to change it and go back to change image sizes, try the [Regenerate Thumbnails plugin](https://wordpress.org/plugins/regenerate-thumbnails/)

----------------------------------------

## Description

This plugin creates a custom xml feed, formatted to be imported directly into Exact Target.

The format is: 

````
<item> 
    <title>story title</title> 
    <link>story link</link> 
    <image>associated image</image> 
    <description>story author</description> 
</item> 
````

Feeds can be located at `example.com/?feed=xtxml` or `example.com/category/category_name/?feed=xtxml`

## Installation

1. Upload `exacttarget-xml-feed` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add new configurations for categories.
4. Done