<?php
/**
 * XML Feed
 */

header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<root>

	<language><?php bloginfo_rss( 'language' ); ?></language>

	<?php while( have_posts()) : the_post();

		include('item-template.php');

	endwhile; ?>

</root>
