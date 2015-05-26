<?php
global $post;
global $xt_xml_feed;
?>
<item>
	<title><?php the_title_rss() ?></title>
	<link><?php the_permalink_rss() ?></link>
	<image><?php $xt_xml_feed->feed_image( ); ?></image>
	<description>
		<![CDATA[ <?php $xt_xml_feed->the_description(); ?> ]]>
	</description>
	<?php do_action('rss2_item'); ?>
</item>