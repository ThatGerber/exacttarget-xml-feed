<?php
/**
 * XML Feed
 */

header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
$more = 1;
global $xt_xml;

var_dump( get_the_category()[0]->name );

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>


<rss>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<channel>

		<?php while( have_posts()) : the_post();

			include('item-template.php');

		endwhile; ?>

	</channel>
</rss>