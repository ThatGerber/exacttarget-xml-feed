<?php
/**
 * XML Feed
 */

$more = 1;
global $xt_xml;
global $xt_xml_feed;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>


<rss>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<channel>

		<?php while( have_posts()) : the_post();

			include('item-template.php');

		endwhile; ?>

	</channel>
</rss>