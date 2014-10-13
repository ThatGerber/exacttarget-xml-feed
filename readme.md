Exact Target XML Feed
========================

Contributors: chriswgerber  
Tags: feed, xml, exact target  
Requires at least: 3.0.0  
Tested up to: 4.0  
Stable tag: 0.1.0  
License: GPLv2  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Custom XML feed formatted for Exact Target

## Description

*Note:*  
This plugin is still being actively developed and isn't suitable for testing. Don't install it expecting it to work.

This plugin creates a custom xml feed, formatted to be imported directly into Exact Target.

The format is: 

````
<item> 
    <title>story title</title> 
    <link>story link</link> 
    <image>associated image</image> 
    <author>story author</author> 
</item> 
````

Feeds can be located at `example.com/?feed=xtxml` or `example.com/category/category_name/?feed=xtxml`

## Installation

1. Upload `exacttarget-xml-feed` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done